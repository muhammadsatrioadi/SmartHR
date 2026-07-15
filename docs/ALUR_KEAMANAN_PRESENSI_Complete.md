# 📘 Alur Keamanan & Presensi SmartHR (Versi Lengkap tapi Mudah Dipahami)

---

## 1️⃣ Alur Login & Autentikasi

### Diagram:
```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser as Browser/Frontend
    participant Login as LoginController
    participant Auth as Laravel Auth
    participant DB as Database

    Karyawan->>Browser: Masukkan Email & Password
    Browser->>Login: POST /actionlogin
    Login->>Auth: Auth::attempt(email, password)
    Auth->>DB: Cari user by email
    DB-->>Auth: Kembalikan data user (password hash)
    Auth->>Auth: Verifikasi password dengan Hash::check()
    alt Password Cocok
        Auth->>Login: Success
        Login->>Login: Simpan user ke session
        Login->>Karyawan: Redirect ke portal/home
    else Password Salah
        Auth->>Login: Gagal
        Login->>Karyawan: Tampilkan pesan "Email atau Password Salah"
    end
```

### Penjelasan Detail:
1. **Karyawan memasukkan email dan password** di halaman login
2. **Browser mengirim data ke server** via `POST /actionlogin`
3. **LoginController memanggil `Auth::attempt()`** untuk otentikasi
4. **Sistem mencari user di database** berdasarkan email
5. **Verifikasi password**: Password yang dimasukkan di-hash dan dibandingkan dengan hash di database
6. **Jika cocok**: Simpan session dan redirect ke portal karyawan
7. **Jika tidak cocok**: Kembali ke halaman login dengan pesan error

---

## 2️⃣ Alur Device Binding & Fingerprint

### Diagram:
```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant JS as portal.js (Client)
    participant FP as Fingerprint Generator
    participant DeviceSvc as DeviceBindingService
    participant DB as Database

    Karyawan->>JS: Buka halaman presensi
    JS->>FP: getDeviceFingerprint()
    FP->>FP: Kumpulkan data (userAgent, language, screen, timezone)
    FP->>FP: Encode Base64 & ambil 64 karakter pertama
    FP-->>JS: Fingerprint siap
    JS->>DeviceSvc: POST /device/register
    DeviceSvc->>DB: Cari UserDevice by user_id
    alt Device Baru (Belum Terdaftar)
        DB-->>DeviceSvc: Tidak ada data
        DeviceSvc->>DB: INSERT UserDevice (fingerprint, label, user_agent, platform)
        DB-->>DeviceSvc: Berhasil disimpan
        DeviceSvc-->>JS: { success: true, registered: true }
        JS->>Karyawan: Tampilkan "Perangkat terverifikasi"
    else Device Sudah Terdaftar
        DB-->>DeviceSvc: Ada data device
        alt Fingerprint Sama
            DeviceSvc->>DB: UPDATE last_used_at
            DB-->>DeviceSvc: Berhasil diupdate
            DeviceSvc-->>JS: { success: true, registered: false }
        else Fingerprint Berbeda
            DeviceSvc-->>JS: { success: false, message: "Akun ini terdaftar di perangkat lain" }
            JS->>Karyawan: Tampilkan pesan error merah
        end
    end
```

### Detail Fingerprint:
Fingerprint di-generate dari kombinasi:
- `navigator.userAgent`: Jenis browser dan sistem operasi
- `navigator.language`: Bahasa yang digunakan browser
- `screen.width` & `screen.height`: Resolusi layar
- `screen.colorDepth`: Kedalaman warna layar
- `Intl.DateTimeFormat().resolvedOptions().timeZone`: Zona waktu

Data ini di-encode dengan Base64 dan dipotong jadi 64 karakter agar mudah disimpan.

---

## 3️⃣ Alur Verifikasi Biometrik (Face ID / Fingerprint)

### Diagram:
```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant JS as portal.js
    participant WebAuthn as WebAuthn API
    participant LocalStorage as LocalStorage

    Karyawan->>JS: Klik tombol Check In
    JS->>WebAuthn: Cek window.PublicKeyCredential
    alt Browser Mendukung WebAuthn
        WebAuthn-->>JS: Tersedia
        JS->>WebAuthn: navigator.credentials.create()
        WebAuthn->>Karyawan: Tampilkan prompt Face ID/Fingerprint
        Karyawan->>WebAuthn: Verifikasi wajah/jari
        WebAuthn-->>JS: Credential object (id, publicKey)
        JS->>LocalStorage: Simpan credentialId ke hr_biometric_credential
        JS->>JS: Set biometric_verified = true
    else Browser Tidak Mendukung
        WebAuthn-->>JS: Tidak tersedia
        JS->>LocalStorage: Cek apakah credentialId sudah ada
        alt Credential Sudah Tersimpan
            LocalStorage-->>JS: Ada credentialId
            JS->>JS: Set biometric_verified = true
        else Tidak Ada Credential
            JS->>JS: Set biometric_verified = false
        end
    end
```

### Detail WebAuthn:
- **Protokol**: Web Authentication API (standar W3C)
- **Autentikator**: Platform (Touch ID, Face ID, Windows Hello)
- **User Verification**: Diwajibkan (`userVerification: 'required'`)
- **Algoritme Kriptografi**: ES256 (Elliptic Curve Digital Signature Algorithm)

---

## 4️⃣ Alur Lokasi & Geofencing

### Diagram:
```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant JS as portal.js
    participant GPS as Geolocation API
    participant GeoCalc as Kalkulator Jarak

    Karyawan->>JS: Klik "Refresh Lokasi GPS"
    JS->>GPS: navigator.geolocation.getCurrentPosition()
    GPS->>Karyawan: Minta izin lokasi
    Karyawan->>GPS: Izinkan akses lokasi
    GPS-->>JS: { latitude, longitude, accuracy }
    JS->>JS: Dapatkan daftar lokasi absensi (window.ATTENDANCE_LOCATIONS)
    loop Setiap Lokasi Absensi
        JS->>GeoCalc: Hitung jarak antara user & lokasi
        Note over GeoCalc: Rumus Haversine
        GeoCalc->>GeoCalc: dLat = (lat2 - lat1) × π/180
        GeoCalc->>GeoCalc: dLon = (lon2 - lon1) × π/180
        GeoCalc->>GeoCalc: a = sin²(dLat/2) + cos(lat1) × cos(lat2) × sin²(dLon/2)
        GeoCalc->>GeoCalc: c = 2 × atan2(√a, √(1-a))
        GeoCalc->>GeoCalc: jarak = 6371000 × c (dalam meter)
        GeoCalc-->>JS: jarak_meter
        JS->>JS: jarak <= radius?
        alt Dalam Radius
            JS->>JS: isWithinAny = true
            JS->>Karyawan: Tampilkan "Dalam jangkauan (Xm)" ✓
        else Luar Radius
            JS->>Karyawan: Tampilkan "Di luar jangkauan (Xm dari Lokasi)" ✗
        end
    end
    alt Mode Dinas Luar Di-check
        JS->>Karyawan: Tampilkan "Mode Dinas Luar Aktif"
        JS->>JS: Izinkan absensi tanpa lokasi
    end
```

### Rumus Haversine (Untuk Ngitung Jarak):
Fungsinya untuk menghitung jarak antara 2 titik di permukaan bumi:

```
R = 6371000 meter (radius bumi)
dLat = (lat2 - lat1) × π / 180
dLon = (lon2 - lon1) × π / 180
a = sin²(dLat/2) + cos(lat1) × cos(lat2) × sin²(dLon/2)
c = 2 × atan2(√a, √(1-a))
jarak = R × c
```

---

## 5️⃣ Alur Absensi Lengkap (End-to-End)

### Diagram:
```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant JS as portal.js
    participant GPS as Geolocation
    participant Bio as Biometric
    participant Device as Device Binding
    participant Portal as PortalController
    participant GeoSvc as GeolocationService
    participant DB as Database

    Karyawan->>JS: Klik tombol CHECK IN/OUT
    JS->>Device: registerDevice()
    Device->>Device: Verifikasi fingerprint cocok
    Device-->>JS: Ok
    JS->>GPS: getCurrentPosition()
    GPS-->>JS: lat, lng, accuracy
    JS->>Bio: verifyBiometric()
    Bio-->>JS: verified, credentialId
    JS->>JS: Buat payload absensi
    JS->>Portal: POST /checkin
    Portal->>Device: Validasi fingerprint
    alt Fingerprint Valid
        Portal->>GeoSvc: Hitung jarak dari lokasi absensi
        GeoSvc-->>Portal: jarak_meter, isWithinRadius
        alt Dalam Radius / Mode Dinas
            Portal->>DB: INSERT absensis
            Note over Portal,DB: Simpan semua metadata: lat, lng, jarak, fingerprint, biometric, user_agent
            DB-->>Portal: Berhasil
            Portal-->>JS: { success: true, message: "Berhasil absen!" }
            JS->>Karyawan: Tampilkan SweetAlert sukses
            JS->>JS: Reload halaman
        else Luar Radius
            Portal-->>JS: { success: false, message: "Lokasi di luar jangkauan" }
            JS->>Karyawan: Tampilkan SweetAlert error
        end
    else Fingerprint Invalid
        Portal-->>JS: { success: false, redirect: "/login" }
        JS->>Karyawan: Redirect ke halaman login
    end
```

---

## 📊 Skema Database Lengkap

```mermaid
erDiagram
    users ||--o{ user_devices : has
    users ||--o{ attendance_consents : has
    users ||--|| karyawans : linked_by
    karyawans ||--o{ absensis : makes
    karyawans }o--|| attendance_locations : assigned_to
    attendance_locations ||--o{ absensis : recorded_at

    users {
        bigint id PK
        string name
        string email
        string password
        string role "enum: admin, karyawan, manajer, atasan"
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

    karyawans {
        bigint id PK
        string nik
        string nama
        bigint jabatan_id FK
        bigint department_id FK
        bigint attendance_location_id FK "opsional"
        datetime created_at
        datetime updated_at
    }

    attendance_locations {
        bigint id PK
        string nama
        decimal latitude "10,7"
        decimal longitude "10,7"
        smallint radius_meter "default:5"
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
        decimal latitude "10,7"
        decimal longitude "10,7"
        decimal accuracy "8,2"
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

## 🎯 Ringkasan Fitur Keamanan

| Fitur | Teknologi | Fungsi |
|-------|-----------|--------|
| 🔐 **Password Hashing** | Bcrypt/Argon2 (Laravel Hash) | Menyimpan password secara aman (tidak plain text) |
| 📱 **Device Binding** | Fingerprint (userAgent + screen + timezone) | Satu akun hanya bisa dipakai di satu perangkat |
| 👤 **Biometric Verification** | WebAuthn API | Verifikasi wajah/jari untuk keamanan tambahan |
| 📍 **Geofencing** | Geolocation API + Rumus Haversine | Pastikan absensi hanya di area kantor |
| 📝 **Audit Trail** | Database logging | Semua metadata absensi disimpan untuk audit |

---

## 📁 Referensi File Kode:
- [LoginController.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Http/Controllers/LoginController.php)
- [DeviceBindingService.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Services/DeviceBindingService.php)
- [GeolocationService.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Support/GeolocationService.php)
- [portal.js](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/public/js/portal.js)
- [Migrasi Database](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/database/migrations/2026_05_16_100000_add_portal_attendance_features.php)
