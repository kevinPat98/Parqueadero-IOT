/*
  Rui Santos
  Complete project details at Complete project details at https://RandomNerdTutorials.com/cloud-weather-station-esp32-esp8266/

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

*/

#ifdef ESP32
  #include <WiFi.h>
  #include <HTTPClient.h>
#else
  #include <ESP8266WiFi.h>
  #include <ESP8266HTTPClient.h>
  #include <WiFiClient.h>
#endif

//#include <Wire.h>
//#include <Adafruit_Sensor.h>
//#include <Adafruit_BME280.h>

const char* ssid = "Patiño S.";
const char* password = "fps29450";

//Your Domain name with URL path or IP address with path
//const char* serverName = "https://201621.000webhostapp.com/esp-post-data.php";

// Keep this API Key value to be compatible with the PHP code provided in the project page.
// If you change the apiKeyValue value, the PHP file /esp-post-data.php also needs to have the same key
String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorName = "ESP32";
String sensorLocation = "UPTC";

/*#include <SPI.h>
#define BME_SCK 18
#define BME_MISO 19
#define BME_MOSI 23
#define BME_CS 5*/

//#define SEALEVELPRESSURE_HPA (1013.25)

//Adafruit_BME280 bme;  // I2C
//Adafruit_BME280 bme(BME_CS);  // hardware SPI
//Adafruit_BME280 bme(BME_CS, BME_MOSI, BME_MISO, BME_SCK);  // software SPI

// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)
//unsigned long timerDelay = 600000;
// Set timer to 30 seconds (30000)
unsigned long timerDelay = 30000;


int detector1 = 13;
int detector2 = 15;
int detector3 = 19;
int detector4 = 33;

const int LED1=14;
const int LED2=27;
const int LED3=26;
const int LED4=25;
const int LED5=21;
const int LED6=32;
const int LED7=18;
const int LED8=5;

void setup() {
  Serial.begin(115200);
  pinMode(detector1, INPUT);
  pinMode(detector2, INPUT);
  pinMode(detector3, INPUT);
  pinMode(detector4, INPUT);
   pinMode(LED1,OUTPUT);
  pinMode(LED2,OUTPUT);
  pinMode(LED3,OUTPUT);
  pinMode(LED4,OUTPUT);
  pinMode(LED5,OUTPUT);
 pinMode(LED6,OUTPUT);
  pinMode(LED7,OUTPUT);
  pinMode(LED8,OUTPUT);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

 
  
  Serial.println("Timer set to 30 seconds (timerDelay variable), it will take 30 seconds before publishing the first reading.");
}


int value1 = 0;
int value2 = 0;
int value3 = 0;
int value4 = 0;
String sensor1;
String sensor2;
String sensor3;
String sensor4;
String currentValuesSensor = "0000";

void loop() {

  value1 = digitalRead(detector1);
  value2 = digitalRead(detector2);
  value3 = digitalRead(detector3);
  value4 = digitalRead(detector4);
 
  if (value1 == HIGH) {
    sensor1= "0";
    digitalWrite(LED2,LOW);
    digitalWrite(LED1,HIGH);
  } else {
    sensor1= "1";
     digitalWrite(LED2,HIGH);
  digitalWrite(LED1,LOW);
  }

    if (value2 == HIGH) {
      sensor2= "0";
       digitalWrite(LED4,LOW);
    digitalWrite(LED3,HIGH);
  } else {
      sensor2= "1";
      digitalWrite(LED4,HIGH);
  digitalWrite(LED3,LOW);
  }

  if (value3 == HIGH) {
      sensor3= "0";
        digitalWrite(LED8,LOW);
    digitalWrite(LED7,HIGH);
  } else {
      sensor3= "1";
      digitalWrite(LED8,HIGH);
  digitalWrite(LED7,LOW);
  }
  if (value4 == HIGH) {
      sensor4= "0";
  digitalWrite(LED6,LOW);
    digitalWrite(LED5,HIGH);
  } else {
      sensor4= "1";
        digitalWrite(LED6,HIGH);
  digitalWrite(LED5,LOW);
  }

  
  String slotsOccupied = sensor1 + sensor2 + sensor3 + sensor4 + "";

  if (slotsOccupied == currentValuesSensor) {
    return;
  }
  currentValuesSensor = slotsOccupied;
  //Send an HTTP POST request every 10 minutes
  //if ((millis() - lastTime) > timerDelay) {
    //Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED){
      //WiFiClient client;
      HTTPClient http;

      // Your Domain name with URL path or IP address with path
      http.begin("https://parkingiotuptc.000webhostapp.com/esp-post-data.php");

      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      // Prepare your HTTP POST request data
      String httpRequestData = "api_key=" + apiKeyValue + "&sensor=" + sensorName
                            + "&location=" + sensorLocation + "&value1=" + sensor1
                            + "&value2=" + sensor2 + "&value3=" + sensor3 + "&value4=" + sensor4 +"";
      Serial.print("httpRequestData: ");
      Serial.println(httpRequestData);

      // You can comment the httpRequestData variable above
      // then, use the httpRequestData variable below (for testing purposes without the BME280 sensor)
      //String httpRequestData = "api_key=tPmAT5Ab3j7F9&sensor=BME280&location=Office&value1=24.75&value2=49.54&value3=1005.14";

      // Send HTTP POST request
      int httpResponseCode = http.POST(httpRequestData);

      // If you need an HTTP request with a content type: text/plain
      //http.addHeader("Content-Type", "text/plain");
      //int httpResponseCode = http.POST("Hello, World!");

      // If you need an HTTP request with a content type: application/json, use the following:
      //http.addHeader("Content-Type", "application/json");
      //int httpResponseCode = http.POST("{\"value1\":\"19\",\"value2\":\"67\",\"value3\":\"78\"}");

      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      // Free resources
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected");
    }
    lastTime = millis();
  //}
}
