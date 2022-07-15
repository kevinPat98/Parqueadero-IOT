int detector = 33;
int detector1 = 32;
int detector2 = 35;
int detector3 = 34;

const int LED=13;

const int LED2=12;
void setup() {
  Serial.begin(9600);
  pinMode(detector, INPUT);
  pinMode(detector1, INPUT);
  pinMode(detector2, INPUT);
  pinMode(detector3, INPUT);
  pinMode(LED,OUTPUT);
  pinMode(LED2,OUTPUT);
}

void loop() {
  int value = 0;
  int value1 = 0;
  int value2 = 0;
  int value3 = 0;
  String sensor1;
  String sensor2;
  String sensor3;
  String sensor4;
  
  value = digitalRead(detector);
  value1 = digitalRead(detector1);
  value2 = digitalRead(detector2);
  value3 = digitalRead(detector3);


  if (value == HIGH) {
    sensor1= "1,V";
    Serial.println (sensor1);
    digitalWrite(LED,LOW);
    digitalWrite(LED2,HIGH);
  } else {
    sensor1= "1,O";
    Serial.println (sensor1);
    digitalWrite(LED,HIGH);
  digitalWrite(LED2,LOW);
  }

  delay (5000);
  
  if (value1 == HIGH) {
    sensor2= "2,V";
    Serial.println (sensor2);
  } else {
    sensor2= "2,O";
    Serial.println (sensor2);

  }
  delay (5000);
  if (value2 == HIGH) {
    sensor3= "3,V";
    Serial.println (sensor3);
  } else {
    sensor3= "3,O";
    Serial.println (sensor3);
  }
  delay (5000);
  if (value3 == HIGH) {
    sensor4= "4,V";
    Serial.println (sensor4);

  } else {
    sensor4= "4,O";
    Serial.println (sensor4);
  }
  delay (5000);

}
