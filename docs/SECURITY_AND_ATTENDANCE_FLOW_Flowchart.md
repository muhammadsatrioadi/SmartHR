# Diagram Alur Keamanan dan Presensi SmartHR (Versi Flowchart)

---

## 1. Alur Autentikasi dan Keamanan

```mermaid
flowchart TD
    Start([Mulai Login]) --> Input[Masukkan Email & Password]
    Input --> Submit[Klik Login]
    Submit --> CekEmail{Cek Email di Database}
    CekEmail -->|Tidak Terdaftar| Error[❌ Email atau Password Salah]
    CekEmail -->|Terdaftar| CekPassword{Cek Password Cocok?}
    CekPassword -->|Tidak Cocok| Error
    CekPassword -->|Cocok| SimpanSession[Simpan Session Login]
    SimpanSession --> Redirect[Redirect ke Halaman Utama]
    Error --> Input
```

### Detail Keamanan Password:
- **Hashing**: Menggunakan Laravel Hash (Bcrypt/Argon2)
- **Enkripsi**: Password tidak pernah disimpan dalam bentuk plain text
- **Verifikasi**: Hash::check() membandingkan password input dengan hash di database

---

## 2. Alur Device Binding dan Fingerprint

```mermaid
flowchart TD
    BukaPresensi[Buka Halaman Presensi] --> GenerateFP[Generate Device Fingerprint]
    GenerateFP --> KumpulkanData[Kumpulkan Data: UserAgent, Bahasa, Resolusi, Timezone]
    KumpulkanData --> Encode[Encode Base64 & Potong 64 Karakter]
    Encode --> CekDB{Cek Device di Database}
    CekDB -->|Belum Terdaftar| SimpanDevice[Simpan Device ke Database]
    CekDB -->|Sudah Terdaftar| CocokFP{Cek Fingerprint Cocok?}
    CocokFP -->|Ya| UpdateWaktu[Update Waktu Terakhir Pakai]
    CocokFP -->|Tidak| ErrorDevice[❌ Akun Terdaftar di Perangkat Lain]
    SimpanDevice --> Berhasil[✅ Perangkat Terverifikasi]
    UpdateWaktu --> Berhasil
```

### Komponen Fingerprint:
- **User Agent**: Informasi browser dan OS
- **Bahasa**: Bahasa browser
- **Resolusi Layar**: Lebar x tinggi dan color depth
- **Zona Waktu**: Timezone browser

---

## 3. Alur Verifikasi Biometrik (Face ID / Fingerprint)

```mermaid
flowchart TD
    KlikCheckIn[Klik Check In] --> CekSupport{Cek Browser Support WebAuthn?}
    CekSupport -->|Tidak| CekCredential{Lama: Credential Tersimpan?}
    CekCredential -->|Ya| Verified[✅ Biometric Verified]
    CekCredential -->|Tidak| NotVerified[❌ Tanpa Biometric]
    CekSupport -->|Ya| Prompt[Tampilkan Prompt Face ID/Fingerprint]
    Prompt --> VerifUser{Verifikasi Berhasil?}
    VerifUser -->|Ya| SimpanCredential[Simpan Credential ke LocalStorage]
    VerifUser -->|Tidak| CekCredential
    SimpanCredential --> Verified
```

### Detail WebAuthn:
- **Protokol**: Web Authentication (WebAuthn) API
- **Autentikator**: Platform (Touch ID, Face ID, Windows Hello)
- **User Verification**: Diwajibkan (userVerification: 'required')
- **Algoritme**: ES256 (alg: -7)

---

## 4. Alur Pemetaan Lokasi Absensi (Geofencing)

```mermaid
flowchart TD
    RefreshGPS[Klik Refresh Lokasi GPS] --> MintaIzin[Minta Izin Lokasi]
    MintaIzin --> Izin{Diberikan?}
    Izin -->|Tidak| ErrorGPS[❌ Gagal Dapatkan Lokasi]
    Izin -->|Ya| DapatkanKoordinat[Dapatkan Latitude & Longitude]
    DapatkanKoordinat --> Loop[Untuk Setiap Lokasi Absensi]
    Loop --> HitungJarak[Hitung Jarak dengan Rumus Haversine]
    HitungJarak --> CekRadius{Jarak <= Radius?}
    CekRadius -->|Ya| DalamRadius[✅ Dalam Jangkauan]
    CekRadius -->|Tidak| LuarRadius[❌ Luar Jangkauan]
    DalamRadius --> CekDinas{Mode Dinas Luar?}
    LuarRadius --> CekDinas
    CekDinas -->|Ya| BisaAbsen[✅ Bisa Absen]
    CekDinas -->|Tidak| CekLagi{Dalam Radius?}
    CekLagi -->|Ya| BisaAbsen
    CekLagi -->|Tidak| TidakBisaAbsen[❌ Tidak Bisa Absen]
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
flowchart TD
    MulaiAbsen([Mulai Absen]) --> BukaPresensi[Buka Halaman Presensi]
    BukaPresensi --> VerifDevice[Verifikasi Perangkat]
    VerifDevice --> DeviceOk{Perangkat Valid?}
    DeviceOk -->|Tidak| GagalDevice[❌ Perangkat Tidak Valid]
    DeviceOk -->|Ya| DapatkanGPS[Dapatkan Lokasi GPS]
    DapatkanGPS --> LokasiOk{Lokasi Valid?}
    LokasiOk -->|Tidak| CekModeDinas{Mode Dinas Luar?}
    CekModeDinas -->|Tidak| GagalLokasi[❌ Lokasi Tidak Valid]
    CekModeDinas -->|Ya| VerifBiometric[Verifikasi Biometric]
    LokasiOk -->|Ya| VerifBiometric
    VerifBiometric --> KlikCheckIn[Klik Check In/Out]
    KlikCheckIn --> KirimData[Kirim Data ke Server]
    KirimData --> ServerVerif[Server Verifikasi Ulang]
    ServerVerif --> ServerOk{Semua Valid?}
    ServerOk -->|Ya| SimpanAbsen[Simpan ke Database]
    SimpanAbsen --> BerhasilAbsen[✅ Berhasil Absen!]
    ServerOk -->|Tidak| GagalServer[❌ Gagal: Lihat Pesan Error]
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
