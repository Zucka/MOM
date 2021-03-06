#include <json_arduino.h>
#include <SPI.h>
#include <Ethernet.h>
#include <SoftwareSerial.h>

//Setting up the Arduino
char devID[4] = "123"; //Device ID. Limited to 3 bytes.
char* useID = (char*) malloc(3 * sizeof(char));  //ID of logged in User, Limited to 3.
int* timeLeft = (int*) malloc(1 * sizeof(int));
unsigned long* lastTime = (unsigned long *) malloc(1 * sizeof(long));
int* state = (int *) malloc(1 * sizeof(int));

//Setting up the Shield's addresses.
byte mac[] = { 0x90, 0xA2, 0xDA, 0x0E, 0xC5, 0x94 };
//IPAddress ip(172,25,11,177); For Backup, unlikely to be used.

//The Server we are connecting to. (DNS)
char server[] = "spcadmin.tk";

// Initialize the Ethernet client library
EthernetClient client;

/*Preparing the RFID Reader*/
SoftwareSerial rfid(7, 8); //Sets up the Digital Pins 7 and 8 which the RFID reader Communicates through.

char* rf_block_responce = (char*) malloc(23* sizeof(char));
//char rf_block_responce[23] = "";
int block_length = 23;
/* The RFID output in UART for reading a block: On a success. (The length would be 0x12.)
 * Slot 0-3 contains the message "Header", "Reserved", "Length" and "Command".
 * slot 4 contains the number of the block read. 
 * Slot 5-20 contains the data stored in the block.
 * slot 21 contains the Checksum.
 *****
 * On a Fail (The length is 0x02.)
 * Slot 0-3 contains the message "Header", "Reserved", "Length" and "Command".
 * Slot 4 contains the error code:
 *        - 0x4E: 'N' No tag present.
 *        - 0x46: 'F' Failed to read.
 * Slot 5 Contains the Checksum. 
 */

//char* seek_responce = (char*) malloc(11* sizeof(char)); 
//char seek_responce[11] = "";
int seek_length = 11;
/* The RFID output in UART for seeking for tag: On 'Tag Found'. (The length would be 0x06.)
 * Slot Slot 0-3 contains the message "Header", "Reserved", "Length" and "Command".
 * Slot 4 contains the tag type. 
 * Slot 5-8 contains the data stored in the block.
 * Slot 9 contains the Checksum.
 *****
 * On no tag found. (The Length is 0x02.)
 * Slot 0-3 contains the message "Header", "Reserved", "Length" and "Command".
 * Slot 4 is the Error Code.
 *        - 0x4C: 'L' Command in progress.
 *        - 0x55: 'U' Command in progress but RF field is off.
 * Slot 5 Contains the Checksum.
 */

//char* authenticate_responce = (char*) malloc(7* sizeof(char)); 
//char authenticate_responce[7] = "";
int authenticate_length = 7;
/* The RFID output in UART for Authenticating a Data block.
 * Slot Slot 0-3 contains the message "Header", "Reserved", "Length" and "Command".
 * Slot 4 contains the Status/Error Code. 
 *        - 0x4C: 'L' - Login Successfull.
 *        - 0x4E: 'N' - No Tag Present or Login Failed.
 *        - 0x55: 'U' - Login Failed.
 *        - 0x45: 'E' - Invalid Key format in E2PROM.
 * Slot 5 Contains the Checksum.
 */


void setup()
{
  //starting the Serial Output
  Serial.begin(9600);
  
  *lastTime = millis();
  *state = 0;
  *timeLeft = 0;

  //Starting up the Ethernet.
  if (Ethernet.begin(mac) == 0) 
  {
    Serial.println(F("Failed to configure Ethernet using DHCP"));
    while(true);
  }
  delay(1000);
  rfid.begin(19200);
  delay(1000);
  
  Serial.println("Start");
}

void loop()
{  
  client.flush();
  delay(10);
  if(checkTimeStatus())
  {
    switch(*state)
    {
      case 0: 
              getStatus();
              break;
              
      case 1: 
              getStatus();
              break;
             
      case 2: 
              *state = 0;
              break;           
    }
  }
    
  switch (*state)
  {
    case 0: Serial.println(F("State 0"));
            stateZero();
            break;
            
    case 1: Serial.println(F("State 1"));
            if(checkNoTimeLeft)
            {
              turnOff();
              break;
            }
            else
            {
              stateOne();
              break;
            }
            
    case 2: Serial.println(F("State 2"));
            break; //TODO: Write code
            
  }
}

/* Start: On Device Calls */
boolean checkTimeStatus()
{
  if(millis() < *lastTime)
  {
    unsigned long* tempTime = (unsigned long *) malloc(1 * sizeof(long));
    *tempTime = (0xffffffff - *lastTime) + millis();
    if(*tempTime > 300000 ) //tempTime Larger than Five Minutes.
    {      
      *lastTime = millis();
      free(tempTime);
      
      return true;
    }
    else
    {
      free(tempTime);
      
      return false;
    }
  }
  else if ((millis() - *lastTime) > 300000)
  {
    *lastTime = millis();
    
    return true;
  }
  else
  {
    return false;  
  }  
}

boolean checkNoTimeLeft()
{
  unsigned long* tempLeft = (unsigned long *) malloc(1 * sizeof(long));
  *tempLeft = *timeLeft * 60000;
  
  if(millis() < *lastTime)
  {
    unsigned long* tempTime = (unsigned long *) malloc(1 * sizeof(long));
    *tempTime = (0xffffffff - *lastTime) + millis();
    
    if(*tempTime > *tempLeft ) //tempTime Larger than Five Minutes.
    {      
      free(tempTime);
      free(tempLeft);
      
      return true;
    }
    else
    {
      free(tempTime);
      free(tempLeft);
      
      return false;
    }
  }
  else if ((millis() - *lastTime) > *tempLeft)
  {
    free(tempLeft);
    
    return true;
  }
  else
  {
    free(tempLeft);
    
    return false;  
  }  
}

void stateZero()
{
  seek();
  delay(10);
  parse_responce(rf_block_responce, seek_length);
  delay(10);
  if(rf_block_responce[2] == 6)
  {
    Serial.println(F("Authenticating."));
    authenticate();
    parse_responce(rf_block_responce, authenticate_length);
    delay(10);
    if(rf_block_responce[4] == 0x4C)
    {
      Serial.println(F("Reading."));
      read_block_RFID();
      delay(10);
      parse_responce(rf_block_responce, block_length);
      delay(10);
      if(rf_block_responce[2] == 0x12)
      {
        Serial.println(F("Read Successfull:  "));
        for(int i = 5; i < 8; i++) //TODO: Length of ID.
        {        
          useID[i-5] = rf_block_responce[i];
        }
        Serial.println(useID);
        turnOn();
        Serial.println(F("Stop"));
      }
      else{
        Serial.println(F("Read Failed"));
      }
    }
    else
    {
      Serial.println(F("Authentication failed"));
    }
  }
  else
  {
    for(int i=1;i<11;i++)
    {
      Serial.println(rf_block_responce[i], HEX);
    }
    
    Serial.println(F("Wait for it"));  
    delay(5000);
  }
}

void stateOne(void)
{
  seek();
  delay(10);
  parse_responce(rf_block_responce, seek_length);
  delay(10);
  if(rf_block_responce[2] == 6)
  {
    Serial.println(F("Authenticating."));
    authenticate();
    parse_responce(rf_block_responce, authenticate_length);
    delay(10);
    if(rf_block_responce[4] == 0x4C)
    {
      Serial.println(F("Reading."));
      read_block_RFID();
      delay(10);
      parse_responce(rf_block_responce, block_length);
      delay(10);
      if(rf_block_responce[2] == 0x12)
      {
        char* tempID = (char *) malloc(3 * sizeof(char));
        Serial.println(F("Read Successfull:  "));
        for(int i = 5; i < 8; i++) //TODO: Length of ID.
        {        
          tempID[i-5] = rf_block_responce[i];
        } 
        
        if(tempID == useID)
        {
          turnOff();
          useID = "";
        }
        else
        {
          turnOff();
          *useID = *tempID;
          turnOn();
        }
        delay(10);
        free(tempID);
        Serial.println(F("Stop"));
      }
      else{
        Serial.println(F("Read Failed"));
      }
    }
    else
    {
      Serial.println(F("Authentication failed"));
    }
  }
  else
  {
    for(int i=1;i<11;i++)
    {
      Serial.println(rf_block_responce[i], HEX);
    }
    
    Serial.println(F("Wait for it"));  
    delay(5000);
  }
}


int freeRam() 
{
  extern int __heap_start, *__brkval; 
  int v; 
  return (int) &v - (__brkval == 0 ? (int) &__heap_start : (int) __brkval); 
}

void getJSON(char* output, char input[], char token[])
{ 
  token_list_t *token_list = NULL;
  token_list = create_token_list(25); // Create the Token List. (Potential Memory Waste)
  json_to_token_list(input, token_list); // Convert JSON String to a Hashmap of Key/Value Pairs
  output = json_get_value(token_list, token);
  release_token_list(token_list); 
}
/* End: On device calls */

/* Start: Calls to Website */

void getStatus(void)
{
  if(client.connect(server, 80))
  {
    char get[25] = "GET /api/api.php/status/"; //Assigning the Get code for the HTML Request. 
    strcat(get, devID); //Limited so that the Device ID's cannot surpoass 3 char length.
    strcat(get, " HTTP/1.1");
     
    Serial.println(F("Connected")); 
    client.println(get);
    client.println(F("Host: spcadmin.tk")); //TODO: Correct this.
    client.println(F("Connection: close"));
    client.println();
    
    free(get);
        
    Serial.println(F("Message Sent"));
    
    while(!client.available());
    
    boolean toggle = false;
    char o[75] = "" ;
    char i[1] = "";
    
    while(client.available())
    { 
      i[0] = client.read();
      if( i[0] == '{')
      {
        toggle = !toggle;
        strcat(o, i);
      }
      else if(i[0] == '}')
      {
        toggle = !toggle;
        strcat(o, i);
      }
      else if(toggle)
      {
        strcat(o, i);
      }     
    }
    delay(10);
    
    Serial.print(F("Status: "));
    Serial.println(o);
    
    char* stat = (char*) malloc(25);        
    getJSON(stat, o, "status");  
    
    if(stat == "GREEN")
    {
      getJSON(stat, o, "timeRemaining");
      *timeLeft = int(*stat);
      free(stat);
    }
    else
    {
      free(stat);
    }
  }
  else
  {
    Serial.println("Connection Failed");
    if(*state == 1)
    {
      *timeLeft = 3;
    }
  }
}

void turnOn(void)
{
  if(client.connect(server, 80))
  {
    char getOn[50] = "GET /api/api.php/turnOn/"; //Assigning the Get code for the HTML Request. 
    strcat(getOn, devID);
    strcat(getOn, "/");
    strcat(getOn, useID);
    strcat(getOn, " HTTP/1.1");
       
    Serial.println(F("Connected")); 
    client.println(getOn);
    client.println(F("Host: spcadmin.tk")); //TODO: Correct this.
    client.println(F("Connection: close"));
    client.println();
    
    free(getOn);
    
    Serial.println(F("Message Sent"));
    
    while(!client.available()); //Waits for the Server to Answer, potential freeze point.
    Serial.println(freeRam());
    
    boolean toggle = false;
    char* on = (char*) malloc(75 * sizeof(char));
    //char on[75] = "" ;
    char i[1] = "";
    
    
    while(client.available()) //Builds the JSON string from the data passed by the website.
    { 
      i[0] = client.read();   //Bytes are passed through the Ethernet Shield with client.Read();
      if( i[0] == '{')        //The JSON string starts with '{' and stops with '}'.
      {
        toggle = !toggle;
        strcat(on, i);
      }
      else if(i[0] == '}')
      {
        toggle = !toggle;
        strcat(on, i);
      }
      else if(toggle)
      {
        strcat(on, i);
      }     
    }
    delay(10);
    
    Serial.println(freeRam());
    Serial.print(F("On: "));
    Serial.println(on);
        
    char* p = (char*) malloc(25);
    getJSON(p, on, "status");
    Serial.println(p);
    
    if(p == "OK")
    {
      Serial.println(F("TTO IF"));
      getJSON(p, on, "timeRemaining");
      *timeLeft = int(*p);
      *state = 1;
      free(p);
    }
    else
    {
      Serial.println(F("TTO Else"));
      useID = "";
      *state = 0;
      free(p);
    }
  }
  else
  {
    Serial.println(F("Connection Failed"));
    *state = 0;
  }
}

void turnOff(void)
{  
  if(client.connect(server, 80))
  {
    char getOff[50] = "GET /api/api.php/turnOff/"; //Assigning the Get code for the HTML Request. 
    strcat(getOff, devID); 
    strcat(getOff, "/");
    strcat(getOff, useID); 
    strcat(getOff, " HTTP/1.1");
       
    Serial.println(F("Connected")); 
    client.println(getOff);
    client.println(F("Host: spcadmin.tk")); //TODO: Correct this.
    client.println(F("Connection: close"));
    client.println();
    
    free(getOff);
    
    Serial.println(F("Message Sent"));
    
    while(!client.available());
    
    boolean toggle = false;
    char off[75] = "" ;
    char i[1] = "";
    
    while(client.available())
    { 
      i[0] = client.read();
      if( i[0] == '{')
      {
        toggle = !toggle;
        strcat(off, i);
      }
      else if(i[0] == '}')
      {
        toggle = !toggle;
        strcat(off, i);
      }
      else if(toggle)
      {
        strcat(off, i);
      }     
    }
    delay(10);
    
    Serial.print(F("Off: "));
    Serial.println(off);
        
    //getJSON(off);   TODO:
    
    *state = 0;
    
  }
  else
  {
    Serial.println(F("Connection Failed"));
    *state = 2;
  }
}

/* End: Calls to Website */

/* Begin: Commands for RFID*/

void seek(void)
{
  //search for RFID tag, sent in UART.
  rfid.write((uint8_t)255);
  rfid.write((uint8_t)0);
  rfid.write((uint8_t)1);
  rfid.write((uint8_t)130);
  rfid.write((uint8_t)131);
  delay(10);
  Serial.println("Seek");
}

void authenticate(void)
{
  //Authenticate Block in UART, Sent to the SM130. Must be done before the block can be read or writen to.
  rfid.write((uint8_t)0xFF); //Header: 1 byte, must always be 0xFF.
  rfid.write((uint8_t)0x00); //Reserved: 1 byte, must always be 0x00.  
  rfid.write((uint8_t)0x03); //Message Length: 1 byte, both for Command and Data (here: 3 bytes).
  rfid.write((uint8_t)0x85); //Command: 1 byte, 0x86 is the Read Block Command
  rfid.write((uint8_t)0x01); //Data(Block Number): 1 byte, read block nr 0x01. TODO: Maybe replace with input so other than one can be read.
  rfid.write((uint8_t)0xFF); //Data(Key Type): 1 byte, authenticate with keytype A and transporty key "0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF"
  rfid.write((uint8_t)0x88); //The Message Checksum.
  delay(10);
}

void read_block_RFID(void)
{
  //Read Block Command in UART, sendt to the SM130. The Block needs to be Authenticated beforehand.
  rfid.write((uint8_t)0xFF); //Header: 1 byte, must always be 0xFF.
  rfid.write((uint8_t)0x00); //Reserved: 1 byte, must always be 0x00.  
  rfid.write((uint8_t)0x02); //Message Length: 1 byte, both for Command and Data (here: 2 bytes).
  rfid.write((uint8_t)0x86); //Command: 1 byte, 0x86 is the Read Block Command
  rfid.write((uint8_t)0x01); //Data(Block Number): 1 byte, read block nr 0x01. TODO: Maybe replace with input so other than one can be read.
  rfid.write((uint8_t)0x89); //The Message Checksum.
  delay(10); 
}

void halt(void)
{
 //Halt tag
  rfid.write((uint8_t)255);
  rfid.write((uint8_t)0);
  rfid.write((uint8_t)1);
  rfid.write((uint8_t)147);
  rfid.write((uint8_t)148);
}
/* End: Commands for RFID */

/* Stores the responce to a waiting char array. 
 * Needs to be called after any Command is sent to the SM130 module. */
void parse_responce(char PH[], int length)
{
  for(int i=1;i<length;i++)
  {
    PH[i] = 0;
  }
  
  while(rfid.available())
  {
    if(rfid.read() == 255)
    {
      for(int i=1;i<length;i++)
      {
        PH[i]= rfid.read();
      }
    }
  }
}

