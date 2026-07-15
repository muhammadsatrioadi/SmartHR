# Diagram Alur Keamanan dan Presensi SmartHR

## 1. Alur Autentikasi dan Keamanan

```mermaid
sequenceDiagram
    autonumber
    participant User as Karyawan
    participant Browser as Browser/App
    participant Login as LoginController
    participant Auth as Laravel Auth
    participant DB as Database
    participant Hash as Hash::make
    
    User->>Browser: Masukkan Email & Password
    Browser->>Login: POST /actionlogin
    Login->>Auth: Auth::attempt()
    Auth->>DB: Cari user by email
    DB-->>Auth: Return user data (password hashed)
    Auth->>Hash: Verifikasi password
    Hash-->>Auth: Valid/invalid
    alt Valid
        Auth-->>Login: Success
        Login->>Session: Simpan user data
        Login->>User: Redirect ke portal/home
    else Invalid
        Auth-->>Login: Gagal
        Login->>User: Flash error message
    end
```

### Detail Keamanan Password:
- **Hashing**: Menggunakan Laravel Hash (Bcrypt/Argon2)
- **Enkripsi**: Password tidak pernah disimpan dalam bentuk plain text
- **Verifikasi**: Hash::check() membandingkan password input dengan hash di database

---

## 2. Alur Device Binding dan Fingerprint

```mermaid
sequenceDiagram
    autonumber
    participant User as Karyawan
    participant JS as portal.js (Client)
    participant FP as Fingerprint Generator
    participant DeviceSvc as DeviceBindingService
    participant DB as Database
    
    Note over User,DB: Langkah 1: Generate Device Fingerprint
    User->>JS: Buka halaman presensi
    JS->>FP: getDeviceFingerprint()
    FP->>FP: Kumpulkan data (userAgent, language, screen, timezone)
    FP->>FP: Encode Base64 & Potong 64 karakter
    FP-->>JS: Fingerprint siap
    
    Note over User,DB: Langkah 2: Register/Verifikasi Device
    JS->>DeviceSvc: POST /device/register
    DeviceSvc->>DB: Cek UserDevice by user_id
    alt Device baru (belum terdaftar)
        DB-->>DeviceSvc: Tidak ada
        DeviceSvc->>DB: Insert UserDevice (fingerprint, device_label, user_agent, platform)
        DB-->>DeviceSvc: Success
        DeviceSvc-->>JS: { success: true, registered: true }
    else Device sudah terdaftar
        DB-->>DeviceSvc: Ada device
        alt Fingerprint sama
            DeviceSvc->>DB: Update last_used_at
            DB-->>DeviceSvc: Success
            DeviceSvc-->>JS: { success: true, registered: false }
        else Fingerprint berbeda
            DeviceSvc-->>JS: { success: false, message: "Akun terdaftar di perangkat lain" }
            JS->>User: Tampilkan pesan error
        end
    end
```

### Komponen Fingerprint:
- **User Agent**: Informasi browser dan OS
- **Bahasa**: Bahasa browser
- **Resolusi Layar**: Lebar x tinggi dan color depth
- **Zona Waktu**: Timezone browser

---

## 3. Alur Verifikasi Biometrik (Face ID / Fingerprint)

```mermaid
sequenceDiagram
    autonumber
    participant User as Karyawan
    participant JS as portal.js (Client)
    participant Bio as WebAuthn API
    participant LocalStorage as localStorage
    
    Note over User,LocalStorage: Cek dukungan WebAuthn
    JS->>Bio: Cek window.PublicKeyCredential
    alt Tidak didukung
        Bio-->>JS: Tidak ada
        JS->>LocalStorage: Cek hr_biometric_credential
        alt Credential tersimpan
            LocalStorage-->>JS: Ada credential
            JS-->>JS: verified = true
        else Tidak ada
            JS-->>JS: verified = false
        end
    else Didukung
        Note over User,LocalStorage: Buat Credential Baru
        JS->>Bio: navigator.credentials.create()
        Bio->>User: Tampilkan prompt Face ID/Fingerprint
        User->>Bio: Verifikasi biometrik
        Bio-->>JS: Credential object (id, publicKey)
        JS->>LocalStorage: Simpan credentialId
        JS-->>JS: verified = true
    end
    
    Note over User,LocalStorage: Pada saat absensi
    JS->>JS: Sertakan biometric_verified dan credentialId dalam payload
```

### Detail WebAuthn:
- **Protokol**: Web Authentication (WebAuthn) API
- **Autentikator**: Platform (Touch ID, Face ID, Windows Hello)
- **User Verification**: Diwajibkan (userVerification: 'required')
- **Algoritme**: ES256 (alg: -7)

---

## 4. Alur Pemetaan Lokasi Absensi (Geofencing)

```mermaid
sequenceDiagram
    autonumber
    participant User as Karyawan
    participant JS as portal.js (Client)
    participant GPS as Geolocation API
    participant GeoSvc as GeolocationService
    participant DB as Database
    
    Note over User,DB: Daftar Lokasi Absensi
    JS->>JS: window.ATTENDANCE_LOCATIONS = [ { lat, lng, radius, nama } ]
    
    Note over User,DB: Dapatkan Lokasi Karyawan
    User->>JS: Klik "Refresh Lokasi GPS" / Auto watch
    JS->>GPS: navigator.geolocation.getCurrentPosition()
    GPS->>User: Minta izin lokasi
    User->>GPS: Izinkan
    GPS-->>JS: { latitude, longitude, accuracy }
    
    Note over User,DB: Hitung Jarak (Haversine Formula)
    JS->>JS: calculateDistance(userLat, userLng, locLat, locLng)
    Note over JS: R = 6371000 m (radius bumi)
    Note over JS: dLat = (lat2 - lat1) * π / 180
    Note over JS: dLon = (lon2 - lon1) * π / 180
    Note over JS: a = sin²(dLat/2) + cos(lat1) * cos(lat2) * sin²(dLon/2)
    Note over JS: c = 2 * atan2(√a, √(1-a))
    Note over JS: distance = R * c
    
    loop Setiap lokasi absensi
        JS->>JS: distance <= radius?
        alt Ya (dalam radius)
            JS->>JS: isWithinAny = true
            JS->>User: Tampilkan "Dalam jangkauan (Xm)"
        else Tidak (luar radius)
            JS->>User: Tampilkan "Di luar jangkauan (Xm dari Lokasi)"
        end
    end
    
    alt Lokasi Dinas Luar di-check
        JS->>User: Tampilkan "Mode Dinas Luar Aktif"
        JS->>JS: Izinkan absensi tanpa lokasi
    else Tidak dinas
        alt isWithinAny = true
            JS->>User: Tampilkan tombol Check In aktif
        else isWithinAny = false
            JS->>User: Tombol Check In dinonaktifkan / pesan error
        end
    end
    
    Note over User,DB: Backend Verification (double-check)
    JS->>GeoSvc: Kirim lat, lng saat absensi
    GeoSvc->>GeoSvc: distanceMeters(userLat, userLng, targetLat, targetLng)
    GeoSvc->>GeoSvc: isWithinRadius(...)
    GeoSvc-->>DB: Simpan jarak_meter dan attendance_location_id
```

### Rumus Haversine (Jarak antara 2 titik):
```
R = radius bumi (6371000 meter)
dLat = (lat2 - lat1) × π/180
dLon = (lon2 - lon1) × π/180
a = sin²(dLat/2) + cos(lat1) × cos(lat2) × sin²(dLon/2)
c = 2 × atan2(√a, √(1-a))
jarak = R × c
```

---

## 5. Alur Absensi Lengkap (End-to-End)

```mermaid
sequenceDiagram
    autonumber
    participant User as Karyawan
    participant JS as portal.js
    participant GPS as Geolocation
    participant Bio as Biometric
    participant Device as Device Binding
    participant Portal as PortalController
    participant GeoSvc as GeolocationService
    participant DeviceSvc as DeviceBindingService
    participant DB as Database
    
    User->>JS: Klik CHECK IN/OUT
    JS->>Device: registerDevice()
    Device->>DeviceSvc: Verifikasi fingerprint
    DeviceSvc-->>JS: Ok
    
    JS->>GPS: getCurrentPosition()
    GPS-->>JS: lat, lng, accuracy
    
    JS->>Bio: verifyBiometric()
    Bio-->>JS: verified, credentialId
    
    JS->>JS: Buat payload (tipe_absen, lat, lng, fingerprint, biometric, dll)
    JS->>Portal: POST /checkin
    
    Portal->>DeviceSvc: Validasi fingerprint cocok dengan user
    alt Valid
        Portal->>GeoSvc: Hitung jarak dari lokasi absensi
        GeoSvc-->>Portal: jarak_meter, isWithinRadius
        alt Dalam radius / dinas luar
            Portal->>DB: Insert absensi (semua metadata)
            DB-->>Portal: Success
            Portal-->>JS: { success: true, message: "Berhasil absen" }
            JS->>User: SweetAlert sukses → Reload
        else Luar radius
            Portal-->>JS: { success: false, message: "Lokasi di luar jangkauan" }
            JS->>User: SweetAlert error
        end
    else Tidak valid
        Portal-->>JS: { success: false, redirect: "/login" }
        JS->>User: Redirect ke login
    end
```

---

## 6. Skema Database (Tabel Terkait)

```mermaid
erDiagram
    users ||--o{ user_devices : has
    users ||--o{ attendance_consents : has
    users ||--|| karyawans : linked_by_email
    karyawans ||--o{ absensis : makes
    karyawans }o--|| attendance_locations : assigned_to
    attendance_locations ||--o{ absensis : recorded_at
    
    users {
        bigint id PK
        string name
        string email
        string password
        string role
        datetime created_at
        datetime updated_at
    }
    
    user_devices {
        bigint id PK
        bigint user_id FK
        string device_fingerprint UK
        string device_label
        text user_agent
        string platform
        datetime registered_at
        datetime last_used_at
        datetime created_at
        datetime updated_at
    }
    
    attendance_locations {
        bigint id PK
        string nama
        decimal latitude
        decimal longitude
        smallint radius_meter
        boolean is_aktif
        datetime created_at
        datetime updated_at
    }
    
    absensis {
        bigint id PK
        bigint karyawan_id FK
        date tanggal_absensi
        time time
        string status_absen
        enum tipe_absen "masuk|pulang"
        decimal latitude
        decimal longitude
        decimal accuracy
        smallint jarak_meter
        bigint attendance_location_id FK
        string device_fingerprint
        string biometric_credential_id
        boolean biometric_verified
        boolean lokasi_dinas
        text catatan
        string user_agent
        datetime created_at
        datetime updated_at
    }
    
    attendance_consents {
        bigint id PK
        bigint user_id FK
        enum jenis "perjanjian_absensi|task_list_flowchart"
        boolean disetujui
        datetime disetujui_pada
        string ip_address
        text catatan
        datetime created_at
        datetime updated_at
    }
```

---

## Ringkasan Fitur Keamanan:

1. **Autentikasi**:
   - Login email/password dengan Laravel Auth
   - Password di-hash dengan Bcrypt/Argon2
   - Session-based authentication

2. **Device Binding**:
   - Satu akun hanya bisa dipakai di satu perangkat
   - Fingerprint di-generate dari karakteristik browser/device
   - Penyimpanan last_used_at untuk audit

3. **Biometric Verification**:
   - WebAuthn API untuk Face ID / Fingerprint / Windows Hello
   - Fallback ke stored credential jika WebAuthn tidak didukung
   - User verification diwajibkan

4. **Geofencing**:
   - Verifikasi lokasi dengan GPS (enableHighAccuracy)
   - Rumus Haversine untuk perhitungan jarak
   - Backend double-check untuk keamanan
   - Mode dinas luar untuk pengecualian lokasi

5. **Data Integrity**:
   - Semua metadata absensi disimpan (lat, lng, accuracy, device_fingerprint, biometric, user_agent)
   - Attendance consent sebelum absensi diizinkan
