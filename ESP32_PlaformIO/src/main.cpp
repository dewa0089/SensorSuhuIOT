#include <WiFi.h>
#include <FirebaseESP32.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <time.h> // Untuk sinkronisasi waktu

#define WIFI_SSID "ESP32_IOT"
#define WIFI_PASSWORD "dewa0000"

// Informasi Firebase
#define FIREBASE_HOST "tes-iot-9e4d4-default-rtdb.asia-southeast1.firebasedatabase.app/"
#define FIREBASE_AUTH "cdfTUUDSV7Uow1ltLOUvEwL4Ne1pvQVKzm7RCq3F"

// Deklarasi pin data untuk masing-masing sensor
#define ONE_WIRE_PIN1 15
#define ONE_WIRE_PIN2 4

// Inisialisasi OneWire untuk masing-masing sensor
OneWire oneWire1(ONE_WIRE_PIN1);
OneWire oneWire2(ONE_WIRE_PIN2);

// Inisialisasi library DallasTemperature
DallasTemperature sensor1(&oneWire1);
DallasTemperature sensor2(&oneWire2);

// Konfigurasi Firebase
FirebaseData firebaseData;
FirebaseAuth firebaseAuth;
FirebaseConfig firebaseConfig;

// Zona waktu WIB (UTC+7)
const long GMT_OFFSET_SEC = 7 * 3600;
const int DAYLIGHT_OFFSET_SEC = 0;

// Fungsi untuk menghasilkan key mirip Firebase
String generateFirebaseKey() {
  const char charset[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  String key = "-"; // Firebase key biasanya diawali dengan "-"
  for (int i = 0; i < 19; i++) { // Key memiliki panjang 19 karakter
    int randomIndex = random(0, sizeof(charset) - 1); // Pilih karakter acak
    key += charset[randomIndex];
  }
  return key;
}

void setup(void) {
  // Inisialisasi Serial Monitor
  Serial.begin(115200);
  delay(2);

  // Aktifkan pull-up internal pada pin data
  pinMode(ONE_WIRE_PIN1, INPUT_PULLUP);
  pinMode(ONE_WIRE_PIN2, INPUT_PULLUP);

  // Inisialisasi sensor
  sensor1.begin();
  sensor2.begin();
  delay(20);

  // Hubungkan ke WiFi
  Serial.print("Connecting to Wi-Fi");
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\nConnected to Wi-Fi");

  // Sinkronisasi waktu menggunakan NTP
  configTime(GMT_OFFSET_SEC, DAYLIGHT_OFFSET_SEC, "pool.ntp.org", "time.nist.gov");
  Serial.println("Time synchronized!");

  // Konfigurasi Firebase
  firebaseConfig.host = FIREBASE_HOST;
  firebaseConfig.signer.tokens.legacy_token = FIREBASE_AUTH;

  // Inisialisasi Firebase
  Firebase.begin(&firebaseConfig, &firebaseAuth);
  Firebase.reconnectWiFi(true);

  // Inisialisasi random seed untuk key
  randomSeed(analogRead(0));
}

void loop(void) {
  // Baca suhu dari sensor pertama
  sensor1.requestTemperatures();
  float temp1C = sensor1.getTempCByIndex(0);
  float temp1F = temp1C * 9.0 / 5.0 + 32;
  Serial.print("Temperature 1 in Celsius: ");
  Serial.println(temp1C);
  Serial.print("Temperature 1 in Fahrenheit: ");
  Serial.println(temp1F);

  // Baca suhu dari sensor kedua
  sensor2.requestTemperatures();
  float temp2C = sensor2.getTempCByIndex(0);
  float temp2F = temp2C * 9.0 / 5.0 + 32;
  Serial.print("Temperature 2 in Celsius: ");
  Serial.println(temp2C);
  Serial.print("Temperature 2 in Fahrenheit: ");
  Serial.println(temp2F);

  // Dapatkan waktu saat ini dalam WIB
  struct tm timeInfo;
  if (!getLocalTime(&timeInfo)) {
    Serial.println("Failed to obtain time");
    return;
  }

  char buffer[30];
  strftime(buffer, sizeof(buffer), "%Y-%m-%d %H:%M:%S", &timeInfo); // Format waktu: YYYY-MM-DD HH:MM:SS
  String formattedTime = String(buffer);

  Serial.print("Current Time (WIB): ");
  Serial.println(formattedTime);

 // Kirim data JSON ke Firebase menggunakan push()
FirebaseJson json1;
json1.set("temperature_celsius", temp1C);
json1.set("temperature_fahrenheit", temp1F);
json1.set("timestamp", formattedTime);

FirebaseJson json2;
json2.set("temperature_celsius", temp2C);
json2.set("temperature_fahrenheit", temp2F);
json2.set("timestamp", formattedTime);

// Menggunakan push() untuk menambahkan data dengan key yang dihasilkan secara otomatis
if (Firebase.pushJSON(firebaseData, "/sensor1", json1)) {
  Serial.println("Temperature 1 data sent to Firebase");
} else {
  Serial.print("Failed to send Temperature 1 data: ");
  Serial.println(firebaseData.errorReason());
}

if (Firebase.pushJSON(firebaseData, "/sensor2", json2)) {
  Serial.println("Temperature 2 data sent to Firebase");
} else {
  Serial.print("Failed to send Temperature 2 data: ");
  Serial.println(firebaseData.errorReason());
}

  delay(60000);
}
