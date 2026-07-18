# 🎤 Cheat Sheet Presentasi Keamanan SmartHR

---

## 📋 Daftar Isi
1. [Perkenalan](#1-perkenalan)
2. [Role Login](#2-role-login)
3. [Device Binding (Satu Akun Satu Perangkat)](#3-device-binding-satu-akun-satu-perangkat)
4. [Verifikasi Biometrik (Face ID / Fingerprint)](#4-verifikasi-biometrik-face-id--fingerprint)
5. [Geofencing (Pembatasan Lokasi Absensi)](#5-geofencing-pembatasan-lokasi-absensi)
6. [Ringkasan & Kesimpulan](#6-ringkasan--kesimpulan)

---

## 1. Perkenalan
- **Apa itu SmartHR?** Sistem Informasi Kepegawaian berbasis web dengan fitur keamanan berlapis
- **Tujuan**: Memastikan absensi & data karyawan aman, akurat, dan sesuai aturan

---

## 2. Role Login
### Tiga Role Utama
| Role | Akses | Fungsi |
|------|-------|--------|
| **Admin HR** | Full akses semua modul | Kelola data master, approve lembur manajer |
| **Manajer/Atasan** | Akses portal & approval | Approve cuti/lembur karyawan |
| **Karyawan** | Akses portal only | Presensi, ajukan cuti/lembur, lihat slip gaji |

### Alur Login Singkat
1. Masukkan email & password
2. Password di-hash dengan Bcrypt/Argon2 (tidak disimpan plain text!)
3. Redirect sesuai role

---

## 3. Device Binding (Satu Akun Satu Perangkat)
### Konsep
- Satu akun hanya bisa dipakai di **satu perangkat** saja
- Mencegah akun dipakai bersama oleh banyak orang

### Cara Kerja
- **Fingerprint Perangkat**: Dibuat dari data browser & perangkat (UserAgent, bahasa, resolusi, timezone)
- **Alur**:
  1. Generate fingerprint saat buka halaman presensi
  2. Cek di database: apakah perangkat sudah terdaftar?
  3. Jika sudah, cek apakah fingerprint cocok?
  4. Jika tidak cocok → error!

---

## 4. Verifikasi Biometrik (Face ID / Fingerprint)
### Teknologi
- Menggunakan **WebAuthn API** (standar W3C)
- Mendukung: Touch ID, Face ID, Windows Hello, sidik jari
- Algoritme: ES256

### Alur Kerja
1. Klik "Check In"
2. Browser menampilkan prompt verifikasi biometrik
3. Jika berhasil → bisa absen
4. Credential disimpan di LocalStorage untuk next time

---

## 5. Geofencing (Pembatasan Lokasi Absensi)
### Konsep
- Absensi hanya bisa dilakukan di **lokasi yang ditentukan** (kantor)
- Menggunakan GPS untuk verifikasi lokasi

### Cara Kerja
1. Dapatkan koordinat GPS (latitude & longitude)
2. Hitung jarak ke lokasi kantor dengan **Rumus Haversine**
3. Cek apakah jarak ≤ radius yang ditentukan
4. Jika ya → bisa absen; jika tidak → tidak bisa!

### Rumus Haversine (Singkat)
Untuk menghitung jarak antara 2 titik koordinat di bumi:
```
jarak = radius_bumi × 2 × atan2(√a, √(1-a))
```

---

## 6. Ringkasan & Kesimpulan
### Fitur Keamanan Utama
| Fitur | Teknologi |
|-------|-----------|
| Role Login | Laravel Auth |
| Password Hashing | Bcrypt/Argon2 |
| Device Binding | Fingerprint perangkat |
| Biometrik | WebAuthn API |
| Geofencing | GPS + Rumus Haversine |
| Audit Trail | Metadata lengkap |

### Poin Penting untuk Presentasi
- **Keamanan Berlapis**: Dari login sampai audit trail
- **User Friendly**: Biometrik & geofencing mudah dipakai
- **Terukur**: Semua aktivitas tercatat dengan baik

---

## 🎯 Tips Presentasi
1. Gunakan diagram dari PANDUAN_KEAMANAN_SmartHR.md untuk visualisasi
2. Contohkan: Bagaimana jika akun dipakai di perangkat lain?
3. Tunjukkan: Bagaimana geofencing bekerja dengan lokasi kantor
4. Tekankan: Keamanan tidak ribet, tapi efektif!
