# C-4: IMPLEMENTASI DAN PEMASANGAN SISTEM

> **Catatan sebelum dipakai:** Draf ini disusun berdasarkan source code PILAR HR yang ada pada repositori lokal (`c:\xampp\htdocs\Aplikasi HR Karyawan`), dokumentasi arsitektur (`docs/Dokumentasi_Arsitektur_SmartHR.md`, `docs/DOKUMENTASI_LENGKAP_SmartHR.md`), ringkasan laporan (`docs/rapih lanjut.md`), dan Panduan Capstone Project PSTI UNISA 2026.
>
> Bagian yang **belum dapat diverifikasi** ditandai `[?]`, dan bukti yang **belum tersedia** ditandai placeholder `[Gambar/Tabel ...]`. Silakan lengkapi sebelum dikumpulkan.

---

## A. Pendahuluan

### A.1 Ringkasan Hasil C-1 sampai C-3

C-1 (Proposal Proyek) menetapkan bahwa proyek **PILAR HR** bertujuan membangun sistem Human Resource Management (HRM) berbasis web yang terintegrasi untuk mengatasi permasalahan pengelolaan data karyawan yang masih manual/terpisah, seperti duplikasi data, keterlambatan informasi, sulitnya memantau kehadiran, dan kurang efektifnya evaluasi kinerja. Metode pengembangan yang dipilih adalah **Agile Scrum**.

C-2 (Spesifikasi Produk) merinci kebutuhan fungsional utama: autentikasi berbasis peran, manajemen data master karyawan, absensi dengan keamanan lokasi/perangkat, pengajuan dan persetujuan cuti, serta kebutuhan nonfungsional berupa keamanan (password hashing, device binding, geofencing, biometrik) dan aksesibilitas berbasis web. Spesifikasi teknis C-2 menyebutkan frontend HTML/CSS/JS + Bootstrap 5, backend Laravel 11/PHP, basis data MySQL, server XAMPP, dan keamanan Bcrypt/Argon2, WebAuthn, Device Binding, serta Geofencing.

C-3 (Perancangan) menetapkan arsitektur **Layered Architecture** dengan enam lapisan: Presentation, Application, Business, Data, Database, dan Security. Perancangan mencakup ERD, activity diagram tiap modul, desain antarmuka, serta rencana implementasi bertahap melalui Sprint 3 (autentikasi & manajemen pengguna), Sprint 4 (master data), Sprint 5 (absensi, cuti, approval), Sprint 6 (dashboard, reporting, integrasi), dan Sprint 7 (pengujian).

### A.2 Tujuan Implementasi

Tahap C-4 merealisasikan rancangan C-3 menjadi sistem yang dapat dijalankan, meliputi penulisan kode, integrasi antarmodul, koneksi basis data, penerapan logika bisnis cuti/lembur/presensi, serta implementasi kontrol keamanan. Tujuan khususnya:

1. Menghasilkan sistem PILAR HR yang dapat dijalankan pada lingkungan XAMPP lokal dan lingkungan produksi server (CyberPanel) serta diuji fungsionalnya.
2. Merealisasikan kebutuhan fungsional C-2 sesuai prioritas sprint.
3. Menerapkan kontrol keamanan yang dirancang pada C-3 (autentikasi, role-based access, device binding, geofencing, biometrik, attendance consent, audit metadata).
4. Menyediakan dokumentasi pemasangan dan pengoperasian yang dapat diikuti kembali.

### A.3 Ruang Lingkup Implementasi

Implementasi pada Capstone Project 2 mencakup modul-modul berikut:

1. Autentikasi dan manajemen peran (login, logout, role-based redirect).
2. Manajemen data master (karyawan, jabatan, golongan, pangkat, status pegawai, departemen, unit kerja).
3. Presensi (check-in/check-out dengan geofencing, device binding, biometrik, consent).
4. Manajemen cuti (jenis cuti, saldo cuti, pengajuan, approval berlapis).
5. Manajemen lembur (overtime).
6. Jadwal kerja (shift group, work shift, jadwal karyawan, hari libur).
7. Lokasi absensi (titik lokasi + radius untuk geofencing).
8. Portal karyawan berbasis antarmuka mobile-friendly.

### A.4 Kondisi Awal Sebelum Pengembangan

Sebelum implementasi, tim telah menyelesaikan analisis kebutuhan dan perancangan sistem pada Capstone Project 1 (Sprint 1 dan Sprint 2). Source code awal dibuat pada 18 April 2026 (commit `143ef38` "Initial commit: SmartHR Laravel app") dengan struktur Laravel 11 standar. Basis data `laravelkaryawan` dibuat pada MySQL lokal melalui XAMPP.

---

## B. Implementasi Sistem

### B.1 Pembagian Tugas dan Tanggung Jawab yang Dilaksanakan

> **Catatan verifikasi:** Riwayat Git pada repositori ini hanya mencatat satu kontributor aktif, yaitu `muhammadsatrioadi9@gmail.com` (Muhammad Satrioadi) untuk seluruh 23 commit sejak 18 April 2026 hingga 19 Juli 2026. Pembagian tugas anggota lain belum dapat diverifikasi dari riwayat commit dan perlu dilengkapi dengan bukti catatan kegiatan, dokumen, atau bukti lain.

**Tabel 4.1. Ringkasan kontribusi anggota**

| Anggota | Tanggung jawab yang dilaksanakan | Modul/fitur | Kode bukti |
|---|---|---|---|
| Muhammad Satrioadi / 2311501028 | Implementasi seluruh backend Laravel, kontroler, model, migrasi, logika bisnis cuti/lembur/presensi, kontrol keamanan (geofencing, device binding, biometrik), integrasi Leaflet & SweetAlert2, serta dokumentasi teknis | Autentikasi, Master Data, Presensi, Cuti, Lembur, Jadwal Kerja, Lokasi Absensi, Portal Karyawan | Commit `143ef38`–`0823b98`, `docs/*.md` |
| Annisa Ashadia N.S. / 2311501050 | [?] Belum terverifikasi dari riwayat commit | [?] | [?] |
| Rizki Fachriadi I. / 2311501054 | [?] Belum terverifikasi dari riwayat commit | [?] | [?] |
| Nathasya Destia F.S. / 2311501058 | [?] Belum terverifikasi dari riwayat commit | [?] | [?] |
| Nessa Aulia R. / 2311501024 | [?] Belum terverifikasi dari riwayat commit | [?] | [?] |

> **Tindakan yang diperlukan:** Isi kolom kontribusi anggota 2–5 dengan bukti yang dapat diverifikasi (catatan kegiatan, tangkapan layar pengerjaan, dokumen desain, hasil testing, atau commit di branch lain). Kode EV merujuk pada Lampiran G.

### B.2 Teknologi dan Alat Bantu yang Digunakan

**Tabel 4.2. Teknologi, alasan pemilihan, dan keterbatasan**

| Teknologi/alat bantu | Fungsi | Alasan dipilih | Keterbatasan/risiko | Alternatif |
|---|---|---|---|---|
| Laravel 11.10.0 / PHP 8.2.12 | Framework backend & REST API | Menyediakan autentikasi, validasi, Eloquent ORM, routing, dan CSRF protection bawaan sehingga mempercepat pengembangan; sesuai kompetensi tim dan kebutuhan sistem HRM berbasis web | Membutuhkan runtime PHP 8.2+ dan pengelolaan dependency via Composer; performa perlu diperhatikan pada beban tinggi | CodeIgniter, Native PHP — tidak dipilih karena fitur bawaan keamanan dan ORM lebih terbatas |
| MySQL (MariaDB via XAMPP) | Penyimpanan data sistem (users, karyawans, cutis, absensis, dll.) | Gratis, banyak digunakan, dukungan penuh di XAMPP, dan sesuai kebutuhan relasi data HRM | Konfigurasi dan pemeliharaan server DB perlu dilakukan; untuk skala besar diperlukan optimasi indeks | PostgreSQL — tidak dipilih karena kebutuhan proyek tercakup MySQL dan lingkungan XAMPP lebih sederhana |
| HTML, CSS, JavaScript, Bootstrap 5 | Antarmuka pengguna (Blade template) | Ringan, tidak memerlukan build system, mudah dipelihara, dan sesuai kebutuhan UI sederhana | Interaksi dinamis kompleks terbatas; perlu perhatian pada konsistensi UI | Vue.js/React — tidak dipilih untuk menghindari kompleksitas build |
| Blade Template Engine | Rendering view Laravel | Terintegrasi langsung dengan Laravel, mendukung komponen dan layout | — | Twig |
| Leaflet.js | Pemetaan lokasi untuk geofencing presensi | Open-source, ringan, tanpa API key, cocok untuk penentuan titik lokasi kantor | Membutuhkan koneksi internet untuk tile peta; akurasi bergantung GPS perangkat | Google Maps API — tidak dipilih karena memerlukan API key berbayar |
| SweetAlert2 | Notifikasi dan konfirmasi aksi (hapus, simpan) | Memberikan UX notifikasi yang lebih baik dibanding alert bawaan browser | Tambahan dependency JavaScript | Native `alert()` |
| Git / GitHub | Pengendalian versi dan kolaborasi | Melacak riwayat perubahan kode dan kontribusi anggota | Membutuhkan disiplin commit dan pengelolaan branch | GitLab |
| XAMPP (Apache + MySQL) | Server pengembangan lokal | Menyediakan Apache, MySQL, PHP dalam satu paket; sesuai spesifikasi teknis C-2 | Hanya untuk pengembangan lokal; bukan untuk produksi | Laragon, Docker — tidak dipilih karena XAMPP lebih sederhana |
| Composer | Pengelola dependency PHP | Standar untuk proyek Laravel | Membutuhkan koneksi internet saat instalasi | — |
| Bcrypt/Argon2 (Laravel Hash) | Hashing password | Bawaan Laravel, aman, sesuai standar keamanan | — | MD5/SHA — tidak dipilih karena tidak aman |

### B.3 Implementasi Berdasarkan Rancangan

Implementasi dilakukan secara bertahap sesuai rencana sprint pada C-3. Berikut uraian implementasi tiap modul.

#### B.3.1 Autentikasi dan Manajemen Peran

Autentikasi diimplementasikan menggunakan `LoginController` dengan mekanisme `Auth::attempt()`. Password diverifikasi menggunakan `Hash::check()` bawaan Laravel (Bcrypt). Setelah login berhasil, sistem mengarahkan pengguna berdasarkan peran melalui metode `redirectAfterLogin()`: pengguna dengan role `karyawan` atau `manajer` diarahkan ke portal karyawan (`portal.home`), sedangkan role admin diarahkan ke dashboard utama (`index`).

```php
// app/Http/Controllers/LoginController.php
protected function redirectAfterLogin($user)
{
    if (in_array($user->role ?? 'karyawan', ['karyawan', 'manajer'], true)) {
        return redirect()->route('portal.home');
    }
    return redirect()->route('index');
}
```

Peran yang dikenali sistem meliputi `admin`, `admin_hr`, `manajer`/`atasan`, dan `karyawan`. Semua route selain login dilindungi middleware `auth`.

> [Gambar 4.1. Implementasi halaman login dan role-based redirect]

#### B.3.2 Manajemen Data Master

Modul master data mencakup karyawan, jabatan, golongan, pangkat, status pegawai, departemen, unit kerja, hari libur, shift group, work shift, jenis cuti, dan lokasi absensi. Setiap modul diimplementasikan dengan pola CRUD (Create-Read-Update-Delete) melalui Controller yang sesuai, dengan validasi input pada setiap metode `store` dan `update`.

Contoh: `KaryawanController` menangani data master pegawai dengan dua kelompok data: data umum dan data kepegawaian (relasi ke jabatan, golongan, pangkat, status, departemen, unit kerja). Relasi antar tabel didefinisikan pada model Eloquent.

> [Gambar 4.2. Implementasi formulir master data karyawan]

#### B.3.3 Presensi (Absensi) dengan Keamanan Berlapis

Presensi adalah modul paling kompleks dan menjadi fokus keamanan. Implementasinya pada `PresensiController` dengan alur:

1. **Device Binding** — setiap akun hanya dapat digunakan pada satu perangkat melalui `DeviceBindingService`. Fingerprint perangkat dibandingkan dengan `user_devices`; jika berbeda, akses ditolak.
2. **Attendance Consent** — karyawan harus menyetujui perjanjian absensi sebelum dapat presensi (diverifikasi oleh `hasRequiredConsents()`).
3. **Geofencing** — lokasi GPS karyawan divalidasi terhadap radius lokasi kantor menggunakan `GeolocationService::distanceMeters()` dengan rumus Haversine. Jika di luar radius dan bukan mode dinas luar, presensi ditolak.
4. **Biometrik** — parameter `biometric_verified` dikirim dari frontend (WebAuthn) sebagai verifikasi tambahan.
5. **Audit Metadata** — setiap presensi menyimpan latitude, longitude, accuracy, jarak, device fingerprint, biometric status, user agent, dan timestamp.

```php
// app/Support/GeolocationService.php — rumus Haversine
public static function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
{
    $earthRadius = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat / 2) ** 2
        + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($earthRadius * $c, 2);
}
```

> [Gambar 4.3. Implementasi halaman presensi dengan peta Leaflet]

#### B.3.4 Manajemen Cuti

`CutiController` menangani pengajuan cuti dengan perhitungan hari kerja yang mengecualikan akhir pekan dan hari libur nasional (menggunakan tabel `holidays`). `LeaveBalanceService` mengelola saldo cuti per jenis cuti per tahun, memastikan saldo tersedia sebelum pengajuan disetujui. Alur approval berlapis: status `menunggu_atasan` → `menunggu_hr` → `disetujui`/`ditolak`, dengan rute `cuti.approveSupervisor`, `cuti.approveHr`, dan `cuti.reject`.

#### B.3.5 Lembur (Overtime)

`OvertimeController` menangani pengajuan lembur dengan perhitungan jumlah jam otomatis dan approval berlapis (atasan → HR).

#### B.3.6 Jadwal Kerja dan Hari Libur

`EmployeeScheduleController` menyediakan grid jadwal shift per bulan dengan dukungan assignment massal (`massAssign`). `HolidayController` mendukung sinkronisasi hari libur nasional. `ShiftGroupController` dan `WorkShiftController` mengelola definisi shift kerja.

#### B.3.7 Lokasi Absensi

`AttendanceLocationController` mengelola titik lokasi kantor beserta radius (dalam meter) yang digunakan untuk validasi geofencing. Lokasi dapat di-assign ke karyawan tertentu atau berlaku umum.

#### B.3.8 Portal Karyawan

Antarmuka portal (`resources/views/portal/`) dirancang mobile-friendly untuk karyawan dan manajer, mencakup halaman home, presensi, riwayat absensi, cuti (pengajuan & approval), profil, consent, dan absensi bawahan (untuk manajer).

---

**Tabel 4.3. Matriks kesesuaian rancangan dan implementasi**

| Kode DES | Kode FR/NFR | Hasil implementasi | PJ | Kode bukti | Status |
|---|---|---|---|---|---|
| DES-AUTH-01 | FR-LOGIN | Login dengan email/password dan role-based redirect diimplementasikan pada `LoginController` | Satrioadi | `LoginController.php`, commit `143ef38` | Sesuai |
| DES-MASTER-01 | FR-MASTER | CRUD master data (karyawan, jabatan, golongan, pangkat, status, departemen, unit kerja) diimplementasikan | Satrioadi | `KaryawanController.php`, `JabatanController.php`, dll. | Sesuai |
| DES-ATT-01 | FR-ATTEND | Presensi check-in/out dengan geofencing (Haversine), device binding, biometrik, consent, dan audit metadata | Satrioadi | `PresensiController.php`, `GeolocationService.php`, commit `eea95e0`, `731c57b` | Sesuai |
| DES-LEAVE-01 | FR-LEAVE | Pengajuan cuti dengan perhitungan hari kerja (kecuali akhir pekan & libur), saldo cuti, approval atasan→HR | Satrioadi | `CutiController.php`, `LeaveBalanceService.php`, commit `4313de4` | Sesuai |
| DES-OT-01 | FR-OT | Pengajuan lembur dengan approval berlapis | Satrioadi | `OvertimeController.php`, commit `a85da56` | Sesuai |
| DES-PERF-01 | FR-PERF | Evaluasi kinerja **belum diimplementasikan** | — | — | Belum |
| DES-DOC-01 | FR-DOC | Penyimpanan dokumen karyawan **belum diimplementasikan** | — | — | Belum |
| DES-SEC-01 | NFR-SEC | Password hashing Bcrypt/Argon2, CSRF, validasi input, role-based access | Satrioadi | `User.php` (`casts: hashed`), middleware `auth` | Sesuai |
| DES-SEC-02 | NFR-SEC | Device binding (1 akun 1 perangkat) | Satrioadi | `DeviceBindingService.php`, `UserDevice.php` | Sesuai |
| DES-SEC-03 | NFR-SEC | Geofencing presensi dengan verifikasi backend | Satrioadi | `GeolocationService.php`, `PresensiController.php` | Sesuai |
| DES-SEC-04 | NFR-SEC | Verifikasi biometrik WebAuthn (frontend) | Satrioadi | `resources/views/portal/presensi.blade.php` | Sebagian (fallback) |
| DES-SEC-05 | NFR-SEC | Attendance consent sebelum presensi | Satrioadi | `AttendanceConsent.php`, `PortalController::consent` | Sesuai |

> Kode FR/NFR/DES di atas disesuaikan karena dokumen C-2 pada `rapih lanjut.md` tidak menggunakan penomoran FR/NFR standar. **Silakan sesuaikan kode dengan penomoran resmi yang digunakan kelompok.**

### B.4 Ringkasan Keputusan dan Perubahan Rancangan

**Tabel 4.4. Ringkasan perubahan implementasi**

| Kode CHG | Perubahan | Alasan | Dampak | PJ |
|---|---|---|---|---|
| CHG-01 | Modul evaluasi kinerja dan penyimpanan dokumen tidak diimplementasikan | Fokus pengembangan pada modul inti HR (master data, presensi, cuti, lembur) yang lebih prioritas | FR-PERF dan FR-DOC berstatus "Belum"; perlu dikomunikasikan ke pembimbing | — [?] |
| CHG-02 | Perubahan label "Admin" menjadi "Manajer" pada tampilan approval cuti | Menyesuaikan istilah peran di perusahaan | Perubahan UI tanpa dampak fungsional | Satrioadi (commit `9457117`) |

> **Tindakan yang diperlukan:** Pastikan setiap CHG memiliki bukti persetujuan dari pembimbing/mitra. Jika belum ada, cantumkan statusnya sebagai "Menunggu persetujuan".

### B.5 Integrasi Komponen

Integrasi antarkomponen dilakukan melalui struktur MVC Laravel dan relasi Eloquent. Berikut alur integrasi utama:

1. **Antarmuka → Controller → Basis Data**: Setiap aksi pengguna (misal submit presensi) mengirim request HTTP ke route yang dipetakan ke method Controller. Controller memvalidasi input, memanggil Service untuk logika bisnis, dan berinteraksi dengan Model (Eloquent) untuk membaca/menulis data ke MySQL.

2. **Integrasi Presensi dengan Modul Pendukung**: `PresensiController` mengintegrasikan `DeviceBindingService` (device binding), `GeolocationService` (geofencing), `AttendanceConsent` (consent), `EmployeeSchedule` (jadwal shift), dan `AttendanceLocation` (titik lokasi). Kegagalan salah satu pemeriksaan menyebabkan presensi ditolak dengan pesan yang sesuai.

3. **Integrasi Cuti dengan Saldo & Hari Libur**: `CutiController` menggunakan `LeaveBalanceService` untuk memastikan saldo cuti tersedia, dan tabel `holidays` untuk mengecualikan hari libur dari perhitungan hak cuti.

4. **Integrasi Portal dengan Role**: `PortalController` memfilter data berdasarkan peran (manajer melihat absensi bawahan; karyawan hanya data pribadi).

> Bukti keberhasilan integrasi akan dirujuk ke kode IT (Integration Testing) pada C-5.

### B.6 Implementasi Kontrol Keamanan

Kontrol keamanan yang diimplementasikan merujuk pada rancangan keamanan C-3:

| Kontrol | Implementasi | Rujukan NFR/DES | Bukti |
|---|---|---|---|
| Autentikasi | `Auth::attempt()` dengan session | DES-SEC-01 | `LoginController.php` |
| Password hashing | Bcrypt (Laravel Hash, `password` cast `hashed`) | DES-SEC-01 | `app/Models/User.php` |
| Role-based access | Middleware `auth` + filter role di Controller; `redirectAfterLogin` | DES-SEC-01 | `routes/web.php`, `LoginController.php` |
| CSRF Protection | Bawaan Laravel pada semua form POST/PUT/DELETE | DES-SEC-01 | Blade `@csrf` |
| Input validation | `$request->validate()` pada setiap Controller | DES-SEC-01 | Semua Controller |
| Device Binding | `DeviceBindingService` + tabel `user_devices`; 1 akun 1 perangkat | DES-SEC-02 | `DeviceBindingService.php` |
| Geofencing | `GeolocationService` (Haversine) + verifikasi di backend | DES-SEC-03 | `GeolocationService.php`, `PresensiController.php` |
| Biometrik | WebAuthn API di frontend (`presensi.blade.php`) | DES-SEC-04 | `resources/views/portal/presensi.blade.php` |
| Attendance Consent | `AttendanceConsent` model + pemeriksaan sebelum presensi | DES-SEC-05 | `PresensiController::hasRequiredConsents()` |
| Audit metadata | Kolom latitude, longitude, accuracy, jarak, fingerprint, biometric status, user agent, IP pada tabel absensi | DES-SEC-06 | migrasi `absensis`, `PresensiController::checkin` |
| Perlindungan kredensial | Kredensial DB disimpan di `.env` (tidak di-commit karena ada di `.gitignore`) | NFR-SEC | `.gitignore`, `.env` |

> **Catatan:** Kredensial basis data pada `.env` saat ini menggunakan `DB_USERNAME=root` tanpa password, sesuai konfigurasi XAMPP default. Untuk lingkungan produksi, kredensial harus diperkuat dan tidak menggunakan akun root tanpa password.

---

## C. Ketuntasan Implementasi

**Tabel 4.5. Status ketuntasan implementasi**

| Fitur/komponen | Kode FR/NFR | Status | PJ | Kode bukti |
|---|---|---|---|---|
| Autentikasi & role-based redirect | FR-LOGIN | Selesai | Satrioadi | `LoginController.php` |
| Manajemen data master (karyawan, jabatan, golongan, pangkat, status, departemen, unit kerja) | FR-MASTER | Selesai | Satrioadi | `*Controller.php` master |
| Presensi (geofencing, device binding, biometrik, consent, audit) | FR-ATTEND | Selesai | Satrioadi | `PresensiController.php`, `GeolocationService.php` |
| Manajemen cuti (jenis, saldo, pengajuan, approval) | FR-LEAVE | Selesai | Satrioadi | `CutiController.php`, `LeaveBalanceService.php` |
| Manajemen lembur (pengajuan + approval) | FR-OT | Selesai | Satrioadi | `OvertimeController.php` |
| Jadwal kerja (shift group, work shift, jadwal karyawan, hari libur) | FR-SCHEDULE | Selesai | Satrioadi | `EmployeeScheduleController.php`, dll. |
| Lokasi absensi (geofencing) | FR-ATTLOC | Selesai | Satrioadi | `AttendanceLocationController.php` |
| Portal karyawan (mobile-friendly) | FR-PORTAL | Selesai | Satrioadi | `resources/views/portal/` |
| Dashboard HR | FR-DASHBOARD | Sebagian | Satrioadi | `Dashboard.php`, `dashboard.js` |
| Evaluasi kinerja karyawan | FR-PERF | Belum | — | — |
| Penyimpanan dokumen karyawan | FR-DOC | Belum | — | — |
| Laporan/Reporting HR | FR-REPORT | Sebagian | Satrioadi | Absensi/cuti list pada dashboard |

> **Keterangan:** "Sebagian" pada dashboard berarti kartu ringkasan tersedia, namun grafik dan filter lanjutan perlu dilengkapi.

---

## D. Dokumentasi Pemasangan dan Pengoperasian Sistem

### D.1 Kebutuhan Perangkat Keras dan Perangkat Lunak

**Spesifikasi Aktual Server Produksi:**
- CPU: Common KVM processor (pc-i440fx-11.0 CPU @ 2.0GHz)
- RAM: 6 GB (5.8Gi total)
- Penyimpanan: 40 GB SSD (13GB terpakai)

**Perangkat Lunak Aktual:**
- Sistem Operasi: Ubuntu 24.04.4 LTS (Noble Numbat)
- Kernel: Linux 6.8.0-147-generic x86_64
- Server Web: OpenLiteSpeed HTTP Server (Active)
- Database: MariaDB 10.11.16 (Active)
- Runtime: PHP 8.3.6 (cli)
- Framework: Laravel 11.10.0
- Pengelola Dependensi: Composer 2.8.12
- Version Control: Git 2.43.0

### D.2 Lingkungan Tujuan Pemasangan

Sistem diimplementasikan pada dua lingkungan utama:

1. **Lingkungan Pengembangan (Local):**
   - Menggunakan XAMPP (Apache + MySQL) pada Windows.
   - URL: `http://localhost/Aplikasi HR Karyawan/public` atau `http://127.0.0.1:8000`.

2. **Lingkungan Produksi (Server):**
    - Sistem Operasi: Ubuntu 24.04.4 LTS.
    - Menggunakan CyberPanel dengan web server OpenLiteSpeed.
    - Domain: `hr.yhotech.id` (dengan SSL/HTTPS).
    - Lokasi File: `/home/hr.yhotech.id/public_html` (terintegrasi dengan repositori Git).
    - Database: MariaDB 10.11.16 pada server produksi.
    - Deployment: Automasi melalui Git Pull dari repositori GitHub.

### D.3 Langkah Pemasangan

**Tabel 4.6. Dokumentasi pemasangan dan pengoperasian sistem**

| Langkah/tindakan | Cara/alat | Lingkungan tujuan | Konfigurasi teknis utama | Konfigurasi keamanan | Kode bukti |
|---|---|---|---|---|---|
| 1. Menyiapkan server | XAMPP / CyberPanel | Lokal & Server | PHP 8.2; MySQL 8.0; LiteSpeed/Apache | Firewall, SSL (HTTPS) | [Gambar 4.4] |
| 2. Deployment kode | Git Pull / Clone | Server | Repo: `muhammadsatrioadi/SmartHR.git` | SSH/HTTPS Auth | [Gambar 4.5] |
| 3. Instal dependensi | `composer install` | Lokal & Server | `--no-dev --optimize-autoloader` | — | [Gambar 4.6] |
| 4. Konfigurasi env | `.env` | Lokal & Server | `APP_KEY`, `DB_PASSWORD`, `APP_ENV=production` | Kredensial rahasia | `.env` |
| 5. Migrasi Database | `php artisan migrate` | Lokal & Server | `--force` pada produksi | — | [Gambar 4.8] |
| 6. Optimasi Cache | `php artisan optimize` | Server | Clear & Cache Route/Config/View | — | — |
| 7. Pengaturan Izin | `chown` & `chmod` | Server | Owner: `hr.yhotech.id`; Folder: `storage`, `cache` | Izin 775/755 | — |
| 8. Uji Akses | Browser | Server | Akses `https://hr.yhotech.id` | Login Role-based | [Gambar 4.12] |

### D.4 Perintah Instalasi dan Konfigurasi Server (Produksi)

```bash
# 1. Masuk ke direktori home website di CyberPanel
cd /home/hr.yhotech.id/public_html

# 2. Ambil pembaruan kode terbaru dari GitHub
git pull origin main

# 3. Instal dependensi produksi
composer install --no-dev --optimize-autoloader

# 4. Jalankan migrasi database
php artisan migrate --force

# 5. Bersihkan dan perbarui cache sistem
php artisan optimize:clear

# 6. Atur kepemilikan dan izin folder agar dapat diakses web server
chown -R hr.yhotech.id:hr.yhotech.id /home/hr.yhotech.id/public_html
chmod -R 775 storage bootstrap/cache
```

### D.5 Menjalankan, Menghentikan, dan Memulai Ulang Sistem

- **Menjalankan:** `php artisan serve` (menggunakan server bawaan Laravel) atau start Apache & MySQL dari XAMPP Control Panel lalu akses `http://localhost/Aplikasi%20HR%20Karyawan/public`.
- **Menghentikan:** Tekan `Ctrl+C` pada terminal `php artisan serve`, atau Stop Apache/MySQL di XAMPP.
- **Memulai ulang:** Jalankan ulang perintah pada langkah menjalankan.

### D.6 Pengamanan yang Diterapkan

1. **Kredensial basis data** disimpan di `.env` yang tidak di-commit (terdaftar di `.gitignore`).
2. **Password pengguna** di-hash dengan Bcrypt melalui cast `hashed` pada model `User`.
3. **CSRF protection** aktif pada semua form.
4. **Validasi input** pada setiap Controller untuk mencegah injeksi data.
5. **Role-based access** melalui middleware `auth` dan filter role di Controller.
6. **Device binding** membatasi satu akun untuk satu perangkat.
7. **Geofencing backend** mencegah presensi dari lokasi yang tidak diizinkan.
8. **HTTPS/SSL** diaktifkan pada domain `hr.yhotech.id` untuk enkripsi data.
9. **File Permissions** diatur secara ketat pada server (owner `hr.yhotech.id`).
10. **LiteSpeed Cache** dikonfigurasi untuk performa dan keamanan.

### D.7 Pemeriksaan Keberhasilan Pemasangan

1. Halaman login muncul saat mengakses URL aplikasi.
2. Login dengan akun admin berhasil dan diarahkan ke dashboard.
3. Login dengan akun karyawan/manajer berhasil dan diarahkan ke portal.
4. Data master dapat ditambahkan/diedit/dihapus.
5. Presensi dapat dilakukan dari dalam radius lokasi kantor dan ditolak dari luar.
6. Pengajuan cuti melalui alur approval atasan→HR berjalan.

**Hasil Verifikasi Infrastruktur Server:**
Berdasarkan pengecekan langsung pada VM, berikut adalah status kesiapan infrastruktur:
- **OS & Kernel:** Ubuntu 24.04.4 LTS, Linux 6.8.0-147-generic (Verified).
- **Resources:** RAM 5.8Gi (Available 5.1Gi), Disk 40G (Free 28G).
- **Web Server:** OpenLiteSpeed active (running) via `lshttpd.service`.
- **Database:** MariaDB 10.11.16 active (running) via `mariadb.service`.
- **PHP:** PHP 8.3.6 NTS dengan Zend OPcache aktif.
- **Laravel:** Laravel Framework 11.10.0 (Verified).
- **Ports:** Port 80, 443, 3306, 8090 (CyberPanel) dalam status LISTEN.

> [Gambar 4.12. Bukti sistem berjalan setelah pemasangan]

---

## E. Persetujuan C-4

**Tabel 4.7. Lembar persetujuan tahap C-4**

| No. | Nama anggota kelompok | NIM | Menyetujui |
|---|---|---|---|
| 1 | Muhammad Satrioadi | 2311501028 | [Paraf/Tanda Tangan] |
| 2 | Annisa Ashadia Nurhaliza Savana | 2311501050 | [Paraf/Tanda Tangan] |
| 3 | Rizki Fachriadi Iskandar | 2311501054 | [Paraf/Tanda Tangan] |
| 4 | Nathasya Destia Fany Sitorus | 2311501058 | [Paraf/Tanda Tangan] |
| 5 | Nessa Aulia Rahma | 2311501024 | [Paraf/Tanda Tangan] |

Yogyakarta, [tanggal bulan tahun]

Menyetujui,

Dosen Pembimbing

**Dr. Arizona Firdonsyah, S.Kom., M.Kom.**

NIP. 8005011810481

---

## Matriks Keterlacakan Implementasi

**Tabel 4.8. Matriks keterlacakan implementasi**

| Kebutuhan (C-2) | Rancangan (C-3) | Modul/komponen diimplementasikan (C-4) | Bukti implementasi | Status |
|---|---|---|---|---|
| Login & role-based routing | DES-AUTH-01 | `LoginController`, middleware `auth`, `User` model | `app/Http/Controllers/LoginController.php` | Selesai |
| Manajemen data master karyawan | DES-MASTER-01 | `KaryawanController`, `JabatanController`, `GolonganController`, `PangkatController`, `EmployeeStatusController`, `DepartmentController`, `WorkUnitController` | `app/Http/Controllers/*.php` (master) | Selesai |
| Presensi dengan geofencing, device binding, biometrik, consent | DES-ATT-01, DES-SEC-02–05 | `PresensiController`, `GeolocationService`, `DeviceBindingService`, `AttendanceConsent`, `AttendanceLocation` | `app/Http/Controllers/PresensiController.php`, `app/Support/GeolocationService.php`, `app/Services/DeviceBindingService.php` | Selesai |
| Pengajuan & approval cuti | DES-LEAVE-01 | `CutiController`, `LeaveBalanceService`, `LeaveType`, `EmployeeLeaveBalance` | `app/Http/Controllers/CutiController.php`, `app/Services/LeaveBalanceService.php` | Selesai |
| Pengajuan & approval lembur | DES-OT-01 | `OvertimeController`, `Overtime` model | `app/Http/Controllers/OvertimeController.php` | Selesai |
| Jadwal kerja & hari libur | DES-SCHEDULE-01 | `EmployeeScheduleController`, `ShiftGroupController`, `WorkShiftController`, `HolidayController` | `app/Http/Controllers/*Schedule*.php`, `*Shift*.php`, `HolidayController.php` | Selesai |
| Keamanan: password hashing, CSRF, validasi, RBAC | DES-SEC-01 | Laravel Auth, `User::casts`, middleware | `app/Models/User.php`, `routes/web.php` | Selesai |
| Evaluasi kinerja | DES-PERF-01 | — | — | Belum |
| Penyimpanan dokumen karyawan | DES-DOC-01 | — | — | Belum |

---

## Daftar Gambar, Tabel, dan Bukti yang Perlu Disertakan

### Daftar Gambar

| No. | Gambar | Status |
|---|---|---|
| 4.1 | Halaman login dan role-based redirect | Tersedia (`docs/DA_dari sistem/01_login.png`, `02_logout.png`) |
| 4.2 | Formulir master data karyawan | Tersedia (`docs/DA_dari sistem/03_kelola_master_pegawai.png`) |
| 4.3 | Halaman presensi dengan peta Leaflet | [?] Periksa `docs/DA_dari sistem/26_presensi_karyawan.png` |
| 4.4 | XAMPP Control Panel aktif | Belum ada — ambil screenshot |
| 4.5 | Struktur direktori proyek | Belum ada — ambil screenshot atau gunakan `tree` |
| 4.6 | Output `composer install` | Belum ada — ambil screenshot terminal |
| 4.7 | Basis data `laravelkaryawan` di phpMyAdmin | Belum ada — ambil screenshot |
| 4.8 | Output `php artisan migrate` | Belum ada — ambil screenshot terminal |
| 4.9 | Output `php artisan db:seed` | Belum ada — ambil screenshot terminal |
| 4.10 | Halaman login terbuka setelah pemasangan | Tersedia (`docs/DA_dari sistem/01_login.png`) |
| 4.11 | Dashboard setelah login admin | Tersedia (`docs/DA_dari sistem/03_kelola_master_pegawai.png` dan lainnya) |
| 4.12 | Bukti sistem berjalan setelah pemasangan | Tersedia dari folder `docs/DA_dari sistem/` |
| — | Diagram arsitektur sistem (dari C-3) | Tersedia (`docs/DIAGRAM_ARSITEKTUR_PILAR_HR.png`) |
| — | ERD (dari C-3) | Tersedia (`docs/ERD.md`, `docs/ERD` jika ada gambar) |

### Daftar Tabel

| No. | Tabel |
|---|---|
| 4.1 | Ringkasan kontribusi anggota |
| 4.2 | Teknologi, alasan pemilihan, dan keterbatasan |
| 4.3 | Matriks kesesuaian rancangan dan implementasi |
| 4.4 | Ringkasan perubahan implementasi |
| 4.5 | Status ketuntasan implementasi |
| 4.6 | Dokumentasi pemasangan dan pengoperasian sistem |
| 4.7 | Lembar persetujuan tahap C-4 |
| 4.8 | Matriks keterlacakan implementasi |

### Bukti Teknis (Potongan Kode & Artefak)

| Bukti | Lokasi |
|---|---|
| Implementasi login & role redirect | `app/Http/Controllers/LoginController.php` |
| Geofencing (rumus Haversine) | `app/Support/GeolocationService.php` |
| Device binding | `app/Services/DeviceBindingService.php` |
| Presensi (check-in/out) | `app/Http/Controllers/PresensiController.php` |
| Cuti & saldo | `app/Http/Controllers/CutiController.php`, `app/Services/LeaveBalanceService.php` |
| Struktur routes | `routes/web.php` |
| Daftar migrasi | `database/migrations/` |
| Riwayat commit | `git log` (23 commit, 18 Apr–19 Jul 2026) |
| Konfigurasi `.env` | `.env` |

---

## Daftar Pemeriksaan Akhir (Checklist Kriteria Penilaian C-4)

Berdasarkan Tabel 4 pada Panduan Capstone Project PSTI UNISA 2026:

| No. | Kriteria penilaian | Status | Catatan |
|---|---|---|---|
| 1 | Tanda tangan dari Pembimbing | Belum | Perlu tanda tangan Dr. Arizona Firdonsyah |
| 2 | Kerangka Dokumen telah disusun dengan benar | ✅ | Struktur A–E sesuai panduan |
| 3 | Implementasi dilakukan sesuai Dokumen C-3 dan terdokumentasi | Sebagian | Sebagian besar sesuai; ada perubahan (CHG-01–04) |
| 4 | Kontribusi setiap anggota dapat ditelusuri | Sebagian | Hanya Satrioadi yang terverifikasi dari Git; anggota lain perlu bukti |
| 5 | Langkah pemasangan dan pengoperasian sistem, alat bantu, lingkungan tujuan, konfigurasi teknis, dan pengamanan | ✅ | Pemasangan lokal (XAMPP) dan produksi (CyberPanel/LiteSpeed) terdokumentasi |
| 6 | Alasan pemilihan teknologi, keterbatasan, risiko, konsekuensi, dan alternatif | ✅ | Tabel 4.2 |
| 7 | Ketuntasan implementasi dapat ditunjukkan | ✅ | Tabel 4.5 |
| 8 | Ketuntasan bagian tanggung jawab mahasiswa didemonstrasikan dan didukung bukti | Sebagian | Perlu identifikasi bagian tanggung jawab masing-masing anggota |

---

## Catatan Bagian yang Belum Dapat Diselesaikan (Keterbatasan Informasi)

1. **Kontribusi anggota 2–5 belum dapat diverifikasi** dari riwayat Git. Perlu bukti catatan kegiatan, dokumen desain, tangkapan layar pengerjaan, atau commit di branch/repositori lain.

2. **Kode FR/NFR/DES resmi** — dokumen C-2 pada `rapih lanjut.md` tidak menggunakan penomoran FR/NFR standar. Kode pada draf ini dibuat untuk konsistensi; **silakan ganti dengan kode resmi kelompok**.

3. **Lingkungan produksi/deployment** telah terverifikasi menggunakan CyberPanel (OpenLiteSpeed) pada domain `hr.yhotech.id`.

4. **Persetujuan perubahan (CHG-01 hingga CHG-02)** belum ada bukti tertulis dari pembimbing/mitra. Perlu dilampirkan pada Lampiran B.

5. **Bagian tanggung jawab Anda secara spesifik** belum dapat diidentifikasi. Mohon sebutkan modul mana yang menjadi tanggung jawab Anda agar dapat ditonjolkan pada Tabel 4.1 dan 4.5.

6. **Bukti screenshot pemasangan** (Gambar 4.4–4.9) belum tersedia dan perlu diambil.

7. **Modul evaluasi kinerja dan penyimpanan dokumen** belum diimplementasikan. Perlu penjelasan mengapa ditiadakan dan apakah telah disetujui.
