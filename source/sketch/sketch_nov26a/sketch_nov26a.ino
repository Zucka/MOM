#include <SoftwareSerial.h>

SoftwareSerial rfid(7, 8); //Sets up the Digital Pins 7 and 8 which the RFID reader Communicates through.

//Remember to Adjust Checksum when the write input changes.
char write_input[16] = { 0x32, 0x33, 0x35}; //{ 0x32, 0x33, 0x34 }; //{ 0x48, 0x65, 0x6C, 0x6C, 0x6F, 0x57, 0x6F, 0x72, 0x6C, 0x64 };

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
 
char seek_responce[11] = "";
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
 
char authenticate_responce[7] = "";
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

  rfid.begin(19200);
  delay(1000);
  
  Serial.println("Start");
}

void loop()
{
  seek();
  delay(10);
  parse_responce(seek_responce, seek_length);
  delay(10);
  if(seek_responce[2] == 6)
  {
    Serial.println("Authenticating.");
    authenticate();
    parse_responce(authenticate_responce, authenticate_length);
    Serial.println(authenticate_responce[4], HEX);
    if(authenticate_responce[4] == 0x4C)
    {
      Serial.println("Writing.");
      write_block_RFID();
      delay(10);
      parse_responce(rf_block_responce, block_length);
      if(rf_block_responce[2] == 0x12)
      {
        for(int i=5;i<sizeof(rf_block_responce);i++)
        {
          Serial.print(rf_block_responce[i]);
        }
        Serial.println();
        Serial.println("Stop");
        while(true);
      }
      else
      {
        Serial.print("Write Failed: ");
        Serial.println(rf_block_responce[4]);
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
}

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
  rfid.write((uint8_t)0x12); //Message Length: 1 byte, both for Command and Data (here: 2 bytes).
  rfid.write((uint8_t)0x86); //Command: 1 byte, 0x86 is the Read Block Command
  rfid.write((uint8_t)0x01); //Data(Block Number): 1 byte, read block nr 0x01. TODO: Maybe replace with input so other than one can be read.
  rfid.write((uint8_t)0x89); //The Message Checksum.
  delay(10); 
}

void write_block_RFID(void)
{
  //Write Block Command in UART, sendt to the SM13. The Block needs to Authenticated Beforehand.
  rfid.write((uint8_t)0xFF); //Header: 1 byte, must always be 0xFF.
  rfid.write((uint8_t)0x00); //Reserved: 1 byte, must always be 0x00.  
  rfid.write((uint8_t)0x12); //Message Length: 1 byte, both for Command and Data (here: 18 bytes).
  rfid.write((uint8_t)0x89); //Command: 1 byte, 0x89 is the Write Block Command
  rfid.write((uint8_t)0x01); //Data(Block Number): 1 byte, read block nr 0x01. TODO: Maybe replace with input so other than one can be read.
  for(int i  = 0; i < 16; i++)
  {
    rfid.write((uint8_t)write_input[i]);
  }
  rfid.write((uint8_t)0x136);
  //rfid.write((uint8_t)0x135);
  //rfid.write((uint8_t)0x498); //The Message Checksum for the data { 0x48, 0x65, 0x6C, 0x6C, 0x6F, 0x57, 0x6F, 0x72, 0x6C, 0x64 }
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
  delay(10);
  
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
