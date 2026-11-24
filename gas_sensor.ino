
#include <LiquidCrystal.h>

// Gas sensor and output pins
#define GAS_SENSOR A0
#define RED_LED 9
#define GREEN_LED 10
#define YELLOW_LED 11
#define BUZZER 8

// Normal LCD: RS, E, D4, D5, D6, D7
LiquidCrystal lcd(13, 12, 4, 5, 6, 7);

int gasValue = 0;

void setup() {
  Serial.begin(115200);

  pinMode(RED_LED, OUTPUT);
  pinMode(GREEN_LED, OUTPUT);
  pinMode(YELLOW_LED, OUTPUT);
  pinMode(BUZZER, OUTPUT);

  // Initialize the 16x2 LCD
  lcd.begin(16, 2);
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("Gas Detection");
  lcd.setCursor(0, 1);
  lcd.print("System Ready");
  delay(2000);
  lcd.clear();
}

void loop() {
  gasValue = analogRead(GAS_SENSOR);

  // Display gas value on LCD
  lcd.setCursor(0, 0);
  lcd.print("Gas Value: ");
  lcd.print(gasValue);
  lcd.print("    ");

  Serial.print("Gas sensor value: ");
  Serial.println(gasValue);

  // Condition 1: Gas value <= 23
  if (gasValue <= 23) {
    digitalWrite(RED_LED, HIGH);
    digitalWrite(GREEN_LED, LOW);
    digitalWrite(YELLOW_LED, LOW);
    digitalWrite(BUZZER, HIGH);

    lcd.setCursor(0, 1);
    lcd.print("RED: LOW GAS    ");
  }

  // Condition 2: Gas value 24 to 45
  else if (gasValue <= 45) {
    digitalWrite(RED_LED, LOW);
    digitalWrite(GREEN_LED, HIGH);
    digitalWrite(YELLOW_LED, LOW);
    digitalWrite(BUZZER, HIGH);

    lcd.setCursor(0, 1);
    lcd.print("GREEN: MEDIUM   ");
  }

  // Condition 3: Gas value above 50
  else if (gasValue > 50) {
    digitalWrite(RED_LED, LOW);
    digitalWrite(GREEN_LED, LOW);
    digitalWrite(YELLOW_LED, HIGH);
    digitalWrite(BUZZER, HIGH);

    lcd.setCursor(0, 1);
    lcd.print("YELLOW: HIGH!");
    
  }

  // Condition 4: Gas value 46 to 50
  else {
    digitalWrite(RED_LED, LOW);
    digitalWrite(GREEN_LED, LOW);
    digitalWrite(YELLOW_LED, LOW);
    digitalWrite(BUZZER, HIGH);

    lcd.setCursor(0, 1);
    lcd.print("GAS: 46 TO 50   ");
  }

  delay(500);
}
