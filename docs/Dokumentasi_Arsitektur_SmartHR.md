# Dokumentasi Arsitektur dan Keamanan SmartHR

## Daftar Isi
1. [Arsitektur Sistem](#arsitektur-sistem)
2. [Presentation Layer](#presentation-layer)
3. [Application Layer](#application-layer)
4. [Business Layer](#business-layer)
5. [Data Layer](#data-layer)
6. [Database Layer](#database-layer)
7. [Security Layer](#security-layer)
8. [Ringkasan Fitur Keamanan](#ringkasan-fitur-keamanan)

---

## Arsitektur Sistem

SmartHR dibangun dengan arsitektur berlapis (Layered Architecture) menggunakan Laravel Framework, yang terdiri dari:

1. Presentation Layer
2. Application Layer
3. Business Layer
4. Data Layer
5. Database Layer
6. Security Layer (melindungi seluruh lapisan)

---

## Presentation Layer

Lapisan antarmuka pengguna yang berinteraksi langsung dengan pengguna sistem.

### Aktor Pengguna
- **HR Admin**: Memiliki akses penuh ke seluruh fitur sistem
- **Manager / Atasan**: Memiliki akses ke data timnya (approval, absensi bawahan)
- **Employee / Karyawan**: Memiliki akses ke fitur pribadi (presensi, cuti, lihat gaji)

### Akses
- Semua aktor mengakses melalui Web Browser
- Koneksi menggunakan HTTPS untuk keamanan

---

## Application Layer

Lapisan inti aplikasi yang dibangun dengan Laravel Framework, berisi modul-modul utama:

| Modul | Deskripsi |
|-------|-----------|
| Authentication | Mengelola login, logout, dan otentikasi pengguna |
| User Management | Mengelola data pengguna sistem |
| Employee Management | Mengelola data karyawan master |
| Department Management | Mengelola departemen |
| Position Management | Mengelola jabatan, golongan, dan pangkat |
| Attendance | Mengelola presensi masuk & pulang |
| Leave | Mengelola pengajuan dan persetujuan cuti |
| Overtime | Mengelola pengajuan lembur |
| Reimbursement | Mengelola klaim biaya |
| Payroll Management | Mengelola gaji dan slip gaji |
| Work Schedule Management | Mengelola shift dan jadwal kerja |
| Dashboard | Menampilkan ringkasan data sesuai role |

---

## Business Layer

Lapisan yang mengimplementasikan logika bisnis inti:

### Komponen
1. **Controllers**: Gerbang masuk untuk request dari pengguna
2. **Services**:
   - `DeviceBindingService`: Mengelola binding perangkat (satu akun satu perangkat)
   - `LeaveBalanceService`: Mengelola perhitungan saldo cuti
   - `HolidaySyncService`: Menyinkronkan hari libur
3. **Validation**: Memvalidasi semua input pengguna
4. **Business Rules**:
   - **Geofencing**: Memverifikasi lokasi presensi menggunakan rumus Haversine
   - **Device Binding**: Memastikan akun hanya dipakai di perangkat terdaftar
   - **Leave Calculation**: Menghitung hari cuti dengan mengecualikan akhir pekan dan hari libur

---

## Data Layer

Lapisan yang berkomunikasi dengan database menggunakan **Laravel Eloquent ORM** (Object-Relational Mapping), memudahkan interaksi dengan data tanpa menulis SQL secara manual.

---

## Database Layer

Lapisan penyimpanan data menggunakan **MySQL**, berisi tabel-tabel berikut:

| Tabel | Deskripsi |
|-------|-----------|
| users | Data pengguna sistem (email, password, role) |
| karyawans | Data karyawan master |
| departments | Data departemen |
| jabatans | Data jabatan |
| golongans | Data golongan |
| pangkats | Data pangkat |
| employee_statuses | Data status pegawai |
| work_units | Data unit kerja |
| absensis | Riwayat presensi karyawan |
| cutis | Data pengajuan cuti |
| leave_types | Jenis cuti |
| employee_leave_balances | Saldo cuti karyawan |
| holidays | Hari libur nasional |
| shift_groups | Grup shift kerja |
| work_shifts | Shift kerja |
| employee_schedules | Jadwal karyawan |
| overtimes | Pengajuan lembur |
| reimbursements | Pengajuan reimbursement |
| salary_steps | Skala gaji berkala |
| employee_salary_histories | Riwayat gaji karyawan |
| gajis | Data gaji bulanan |
| attendance_locations | Titik lokasi untuk geofencing |
| attendance_consents | Persetujuan peraturan presensi |
| user_devices | Data perangkat yang terikat dengan akun |
| provinces, regencies, districts, villages | Data wilayah Indonesia |

---

## Security Layer

Lapisan keamanan yang melindungi seluruh sistem, terdiri dari:

### 1. Authentication
- Menggunakan Laravel Auth + Session
- Role-based access control

### 2. Password Hashing
- Menggunakan Bcrypt / Argon2 untuk melindungi password
- Password tidak pernah disimpan dalam bentuk plain text

### 3. Role-Based Login Redirect
- Admin diarahkan ke dashboard utama
- Manajer/Karyawan diarahkan ke portal karyawan

### 4. Session Management
- Mengelola sesi pengguna dengan aman

### 5. CSRF Protection
- Perlindungan dari Cross-Site Request Forgery (Laravel default)

### 6. Input Validation
- Memvalidasi semua input pengguna untuk keamanan

### 7. Device Binding / Fingerprint
- Satu akun hanya bisa dipakai di satu perangkat
- Fingerprint di-generate dari karakteristik perangkat (UserAgent, bahasa, resolusi, timezone)

### 8. Biometric Verification
- Menggunakan WebAuthn API untuk Face ID / Fingerprint / Windows Hello
- Fallback ke stored credential jika WebAuthn tidak didukung

### 9. Geofencing
- Memverifikasi lokasi presensi menggunakan GPS dengan akurasi tinggi
- Menggunakan rumus Haversine untuk menghitung jarak dari lokasi kantor
- Mode dinas luar untuk pengecualian lokasi

### 10. Attendance Consent
- Karyawan harus menyetujui perjanjian absensi dan task list flowchart sebelum bisa presensi

### 11. Audit Trail & Data Integrity
- Semua data absensi disimpan dengan metadata lengkap
- Metadata: latitude, longitude, accuracy, jarak, device fingerprint, biometric status, user agent, timestamp

### 12. Attendance Metadata
- Menyimpan detail setiap presensi untuk keperluan audit

---

## Ringkasan Fitur Keamanan

Berikut adalah ringkasan fitur keamanan di SmartHR:

| Fitur | Teknologi |
|-------|-----------|
| Authentication | Laravel Auth + Session |
| Password Hashing | Bcrypt / Argon2 |
| Role-Based Login | Laravel Redirect |
| Session Management | Laravel Session |
| CSRF Protection | Laravel CSRF |
| Input Validation | Laravel Validation |
| Device Binding | Fingerprint + UserDevice Model |
| Biometric Verification | WebAuthn API |
| Geofencing | GPS + Rumus Haversine |
| Attendance Consent | AttendanceConsent Model |
| Audit Trail | Metadata di Absensi Model |

---

## Alur Kerja Umum

1. Pengguna mengakses sistem via Web Browser (Presentation Layer)
2. Request dikirim ke Laravel Framework (Application Layer)
3. Controller memproses request dan memvalidasi input
4. Controller memanggil Service untuk menangani logika bisnis (Business Layer)
5. Service berinteraksi dengan database via Eloquent ORM (Data Layer)
6. Data diambil atau disimpan di MySQL (Database Layer)
7. Seluruh proses dilindungi oleh Security Layer

---

## Kesimpulan

Arsitektur SmartHR dirancang dengan prinsip keamanan dan keterpeliharaan (maintainability) yang tinggi. Setiap lapisan memiliki tanggung jawab yang jelas, dan seluruh sistem dilindungi oleh fitur keamanan yang komprehensif.
