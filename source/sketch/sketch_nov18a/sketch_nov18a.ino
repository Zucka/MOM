#include <json_arduino.h>
#include <SPI.h>
#include <Ethernet.h>
#include <SoftwareSerial.h>

//Setting up the Arduino
char devID[4] = "123"; //Device ID. Limited to 3 bytes.
char useID[5] = "";  //ID of logged in User, Limited to 5.

//Setting up the Shield's addresses.
byte mac[] = { 0x90, 0xA2, 0xDA, 0x0E, 0xC5, 0x94 };
//IPAddress ip(172,25,11,177); For Backup, unlikely to be used.

//The Server we are connecting to. (DNS)
char server[] = "spcadmin.tk"; //TODO: Correct this.

// Initialize the Ethernet client library
EthernetClient client;

/*Preparing the RFID Reader*/
SoftwareSerial rfid(7, 8); //Sets up the Digital Pins 7 and 8 which the RFID reader Communicates through.

char rf_block_responce[23] = "";
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

  //Starting up the Ethernet.
  if (Ethernet.begin(mac) == 0) 
  {
    Serial.println("Failed to configure Ethernet using DHCP");
    while(true);
  }
  delay(1000);
  rfid.begin(19200);
  delay(1000);
  
  Serial.println("Start");
}

void loop()
{
  getStatus();
  Serial.println(F("Exit"));
  while(true);
  
  /*
  seek();
  delay(10);
  parse_responce(seek_responce, seek_length);
  delay(10);
  if(seek_responce[2] == 6)
  {
    Serial.println("Authenticating.");
    authenticate();
    parse_responce(authenticate_responce, authenticate_length);
    if(authenticate_responce[4] == 0x4C)
    {
      Serial.println("Reading.");
      read_block_RFID();
      parse_responce(rf_block_responce, block_length);
      if(rf_block_responce[2] == 0x12)
      {
        for(int i=5;i<sizeof(rf_block_responce);i++)
        {
          Serial.print(rf_block_responce[i]);
        }
        Serial.println("Stop");
        while(true);
      }
    }
    else
    {
      Serial.println("Authentication failed");
    }
  }
  else
  {
    for(int i=1;i<sizeof(seek_responce);i++)
    {
      Serial.println(seek_responce[i], HEX);
    }
    
    Serial.println("Wait for it");  
    delay(10000);
  }
  */
}

/* Start: On Device Calls */

void getJSON(char input[])
{  
    char* output;
    token_list_t *token_list = NULL;
    token_list = create_token_list(25); // Create the Token List. (Potential Memory Waste)
    json_to_token_list(input, token_list); // Convert JSON String to a Hashmap of Key/Value Pairs
    output = json_get_value(token_list, "status");
    release_token_list(token_list);
    Serial.println(output);  
}

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
    
    delay(1000);
    
    boolean toggle = false;
    char o[75] = ""; 
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
        /*
        if(i[0] == '"')
        {
          strcat(o, "\"");
        }
        else
        {
          strcat(o, i);
        }*/
        strcat(o, i);
      }     
    }
    free(i);
    
    Serial.println();
    Serial.println(o);
    //TODO: why doesn't JSON work without this unrelated array and print?
    char o2[75] = "{\"status\":\"RED\",\"action\":\"none\",\"timeRemaining\":0}";
    
    
    if( o2 == o)
    {
      Serial.println(F("banal"));
    }
    Serial.println(o2);
    
    getJSON(o);
  
    
    Serial.println(F("Tokens Baby"));
  }
  else
  {
    Serial.println("Connection Failed");
  }
}
/*
void turnOn(void)
{
  char json[255] = "";
  
  if(client.connect(server, 80))
  {
    char get[20] = "GET /"; //Assigning the Get code for the HTML Request. 
    strcat(get, devID); //Limited so that the Device ID's cannot surpoass 7 char length.
    strcat(get, "/");
    strcat(get, useID); //Currently limited to 5 chars.
    strcat(get, " HTTP/1.1");
       
    Serial.println("Connected"); 
    client.println(get);
    client.println("Host: www.test.com/api/turnOn"); //TODO: Correct this.
    client.println("Connection: close");
    client.println();
    
    delay(1000);
    
    while(client.available())
    {
      char io[1] = "";
      io[0] = client.read();
      strcat(json, io);
    }
    
    token_list_t *token_list = NULL;
    token_list = create_token_list(25); // Create the Token List. ((Potential Memory Waste)
    json_to_token_list(json, token_list); // Convert JSON String to a Hashmap of Key/Value Pairs

    
  }
  else
  {
    Serial.println("Connection Failed");
  }
}

void turnOff(void)
{
  char json[255] = "";
  
  if(client.connect(server, 80))
  {
    char get[20] = "GET /"; //Assigning the Get code for the HTML Request. 
    strcat(get, devID); //Limited so that the Device ID's cannot surpoass 7 char length.
    strcat(get, "/");
    strcat(get, useID); //Currently limited to 5 chars.
    strcat(get, " HTTP/1.1");
       
    Serial.println("Connected"); 
    client.println(get);
    client.println("Host: www.test.com/api/turnOff"); //TODO: Correct this.
    client.println("Connection: close");
    client.println();
    
    delay(1000);
    
    while(client.available())
    {
      char io[1] = "";
      io[0] = client.read();
      strcat(json, io);
    }
    
    token_list_t *token_list = NULL;
    token_list = create_token_list(25); // Create the Token List. ((Potential Memory Waste)
    json_to_token_list(json, token_list); // Convert JSON String to a Hashmap of Key/Value Pairs

    
  }
  else
  {
    Serial.println("Connection Failed");
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

