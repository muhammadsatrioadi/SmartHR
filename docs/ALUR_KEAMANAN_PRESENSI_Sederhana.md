# 📘 Alur Keamanan & Presensi SmartHR (Versi Mudah Dipahami)

---

## 1️⃣ Alur Login & Autentikasi

### Diagram Sederhana:
```mermaid
flowchart TD
    A[Karyawan] -->|Masukkan Email+Password| B[Login Page]
    B -->|Cek ke Database| C{Password Cocok?}
    C -->|Ya| D[Simpan Session]
    C -->|Tidak| E[Tampilkan Error]
    D --> F[Masuk ke Portal Karyawan]
    E --> B
```

### Penjelasan Langkah demi Langkah:
1. **Karyawan buka halaman login**
2. **Masukkan email dan password**
3. **Sistem cek ke database**:
   - Apakah email terdaftar?
   - Apakah passwordnya cocok (dengan hash)?
4. **Jika berhasil**:
   - Simpan session login
   - Arahkan ke halaman portal karyawan
5. **Jika gagal**:
   - Tampilkan pesan "Email atau password salah"
   - Kembali ke halaman login

### Keamanan:
- ✅ Password **tidak disimpan mentah-mentah** (di-hash dengan Bcrypt)
- ✅ Session yang aman untuk menjaga status login

---

## 2️⃣ Alur Device Binding (Satu Akun = Satu Perangkat)

### Diagram Sederhana:
```mermaid
flowchart TD
    A[Buka Halaman Presensi] --> B{Perangkat Sudah Terdaftar?}
    B -->|Belum| C[Generate Fingerprint]
    C --> D[Simpan ke Database]
    B -->|Sudah| E{Cocok dengan Fingerprint?}
    E -->|Ya| F[Update Waktu Pakai]
    E -->|Tidak| G[Error: Akun dipakai perangkat lain!]
    D --> H[Perangkat Terverifikasi]
    F --> H
```

### Penjelasan Langkah demi Langkah:
1. **Karyawan buka halaman presensi**
2. **Sistem generate "fingerprint" perangkat**:
   - Diambil dari: jenis browser, resolusi layar, bahasa, zona waktu
   - Di-encode jadi string unik
3. **Cek di database**:
   - **Jika baru**: Simpan fingerprint perangkat ke database
   - **Jika sudah ada**: Cocokkan fingerprintnya
4. **Jika fingerprint cocok**: Lanjut!
5. **Jika berbeda**: Tampilkan error "Akun ini terdaftar di perangkat lain"

### Tujuan:
- 🛡️ Mencegah akun dipakai bersama di perangkat berbeda
- 📱 Pastikan absensi hanya dari perangkat yang terdaftar

---

## 3️⃣ Alur Face ID / Fingerprint (Biometric)

### Diagram Sederhana:
```mermaid
flowchart TD
    A[Klik Check In] --> B{Cek Apakah Bisa Face ID?}
    B -->|Ya| C[Tampilkan Prompt Face ID]
    C --> D{Verifikasi Berhasil?}
    D -->|Ya| E[Simpan Credential]
    B -->|Tidak| F[Cek Credential Lama]
    F -->|Ada| E
    F -->|Tidak| G[Tanpa Biometric]
    E --> H[Lanjut ke Absensi]
    G --> H
```

### Penjelasan Langkah demi Langkah:
1. **Klik tombol Check In**
2. **Sistem cek apakah browser mendukung Face ID/Fingerprint**:
   - Jika ya: Muncul prompt untuk verifikasi wajah/jari
   - Jika tidak: Cek apakah sudah pernah verifikasi sebelumnya
3. **Verifikasi biometrik**:
   - Jika berhasil: Lanjut proses absensi
   - Jika gagal atau tidak didukung: Tetap bisa absensi (tanpa biometric)

### Catatan:
- 📱 Ini **opsional**, tapi menambah keamanan
- 💾 Credential disimpan di browser (localStorage)

---

## 4️⃣ Alur Lokasi & Geofencing (Pemetaan Lokasi Absen)

### Diagram Sederhana:
```mermaid
flowchart TD
    A[Refresh Lokasi GPS] --> B[Dapatkan Koordinat]
    B --> C{Hitung Jarak ke Lokasi Kantor}
    C --> D{Dalam Radius?}
    D -->|Ya| E[Lokasi Valid ✓]
    D -->|Tidak| F[Lokasi Invalid ✗]
    G{Mode Dinas Luar?} -->|Ya| E
    F --> G
```

### Penjelasan Langkah demi Langkah:
1. **Dapatkan lokasi GPS**:
   - Browser minta izin akses lokasi
   - Dapatkan latitude & longitude
2. **Hitung jarak ke lokasi kantor**:
   - Pakai rumus **Haversine** (menghitung jarak di bola bumi)
3. **Cek apakah dalam radius**:
   - Misal radius 5 meter dari kantor
   - Jika ya: Bisa absensi
   - Jika tidak: Tidak bisa absensi
4. **Mode Dinas Luar**:
   - Jika dicentang: Bisa absensi dari mana saja

### Contoh Kasus:
- ✨ **Karyawan di kantor**: Jarak 2 meter → Bisa absen
- ✨ **Karyawan di warung depan**: Jarak 10 meter → Tidak bisa (kecuali mode dinas)
- ✨ **Karyawan dinas luar**: Centang mode dinas → Bisa absen dari mana saja

---

## 5️⃣ Alur Absensi Lengkap (Semua Langkah Bersama!)

### Diagram Besar:
```mermaid
flowchart TD
    Start([Mulai]) --> A[Buka Halaman Presensi]
    A --> B[Verifikasi Perangkat]
    B --> C{Dapatkan Lokasi GPS}
    C --> D{Cek Lokasi Valid?}
    D -->|Tidak| E{Mode Dinas?}
    E -->|Tidak| X([Gagal: Lokasi Invalid])
    E -->|Ya| F
    D -->|Ya| F[Verifikasi Biometric]
    F --> G[Klik Check In]
    G --> H[Simpan ke Database]
    H --> I([Berhasil!])
```

### Penjelasan Lengkapnya:
1. **Buka halaman presensi**
2. **Verifikasi perangkat**: Apakah perangkat ini terdaftar?
3. **Dapatkan lokasi GPS**: Pastikan di kantor (atau mode dinas)
4. **Verifikasi biometric**: Face ID/Fingerprint (opsional)
5. **Klik Check In**: Data dikirim ke server
6. **Simpan ke database**: Semua data disimpan (lokasi, perangkat, waktu, dll)
7. **Berhasil!**: Tampilkan pesan sukses

---

## 📊 Skema Database Tabel Penting

```mermaid
flowchart LR
    Users[Users<br/>Akun Login]
    Karyawan[Karyawans<br/>Data Karyawan]
    Devices[User Devices<br/>Perangkat Terdaftar]
    Locations[Attendance Locations<br/>Lokasi Absensi]
    Absensis[Absensis<br/>Riwayat Absensi]
    
    Users -->|1-ke-1| Karyawan
    Users -->|1-ke-banyak| Devices
    Karyawan -->|1-ke-banyak| Absensis
    Locations -->|1-ke-banyak| Absensis
    Karyawan -->|banyak-ke-1| Locations
```

### Penjelasan Tabel:
1. **Users**: Simpan email, password, role
2. **Karyawans**: Data lengkap karyawan (nama, NIK, jabatan, dll)
3. **User Devices**: Fingerprint perangkat yang terdaftar
4. **Attendance Locations**: Titik lokasi kantor (latitude, longitude, radius)
5. **Absensis**: Riwayat absensi (waktu, lokasi, perangkat, dll)

---

## 🎯 Ringkasan Semua Fitur Keamanan

| Fitur | Fungsi |
|-------|--------|
| 🔐 **Login Password** | Otentikasi dasar dengan password yang di-hash |
| 📱 **Device Binding** | Satu akun hanya bisa dipakai di satu perangkat |
| 👤 **Face ID/Fingerprint** | Verifikasi biometrik untuk keamanan tambahan |
| 📍 **Geofencing** | Pastikan absensi hanya di lokasi kantor |
| 📝 **Audit Trail** | Semua data absensi disimpan (lokasi, perangkat, waktu) |

---

Semoga penjelasan ini lebih mudah dipahami! 🚀
