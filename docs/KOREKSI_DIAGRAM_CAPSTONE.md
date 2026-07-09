# Koreksi dan Konsistensi Diagram Sistem HR Karyawan

**Dokumen acuan perbaikan diagram capstone**  
**Sumber perbandingan:** implementasi aplikasi HR Karyawan yang sudah berjalan  
**Versi:** 1.0 — Juli 2026

---

## Daftar Isi

1. [Tujuan Dokumen](#1-tujuan-dokumen)
2. [Pengguna Sistem (Aktor)](#2-pengguna-sistem-aktor)
3. [Daftar Istilah Baku](#3-daftar-istilah-baku)
4. [Struktur Menu Aplikasi](#4-struktur-menu-aplikasi)
5. [Koreksi Use Case Diagram](#5-koreksi-use-case-diagram)
6. [Koreksi Activity Diagram](#6-koreksi-activity-diagram)
7. [Koreksi Sequence Diagram](#7-koreksi-sequence-diagram)
8. [Koreksi ERD](#8-koreksi-erd)
9. [Cek Konsistensi Antar Diagram](#9-cek-konsistensi-antar-diagram)
10. [Alur Bisnis yang Benar](#10-alur-bisnis-yang-benar)
11. [Checklist Perbaikan](#11-checklist-perbaikan)
12. [Catatan untuk Penulisan Skripsi](#12-catatan-untuk-penulisan-skripsi)

---

## 1. Tujuan Dokumen

Dokumen ini dibuat untuk membantu perbaikan diagram pada file **CAPSTONE 1.drawio**. Isi dokumen membandingkan diagram yang sudah dibuat dengan **fitur yang benar-benar ada di aplikasi**, menggunakan bahasa yang mudah dipahami.

Dokumen ini dapat dikonversi ke PDF dan digunakan sebagai:

- acuan revisi diagram draw.io;
- lampiran penjelasan di bab analisis atau perancangan sistem;
- daftar periksa sebelum sidang atau bimbingan.

---

## 2. Pengguna Sistem (Aktor)

### 2.1 Perbandingan Aktor

Pada diagram capstone, digunakan tiga aktor: **Admin**, **Manager**, dan **Karyawan**.

Pada aplikasi sebenarnya terdapat **empat peran pengguna**:

| Peran di Aplikasi | Setara dengan Diagram | Keterangan |
|---|---|---|
| Admin HR | Admin | Mengelola seluruh data master, pengaturan, laporan, dan monitoring |
| Atasan | Belum digambar terpisah | Memiliki fungsi serupa Manager: menyetujui pengajuan bawahan |
| Manajer | Manager | Menggunakan portal, memantau tim, dan menyetujui pengajuan |
| Karyawan | Karyawan | Menggunakan portal untuk absensi, cuti, klaim, dan melihat gaji |

### 2.2 Rekomendasi Perbaikan Aktor

Gunakan salah satu pendekatan berikut secara konsisten di semua diagram:

- **Opsi A (disarankan):** tiga aktor dengan label **Admin HR**, **Manager/Atasan**, dan **Karyawan**.
- **Opsi B:** empat aktor terpisah: **Admin HR**, **Atasan**, **Manajer**, dan **Karyawan**.

### 2.3 Perilaku Setelah Login

| Peran | Halaman Pertama Setelah Login |
|---|---|
| Karyawan | Portal Karyawan (tampilan mobile) |
| Manajer | Portal Karyawan (tampilan mobile) |
| Atasan | Dashboard Admin |
| Admin HR | Dashboard Admin |

---

## 3. Daftar Istilah Baku

Agar semua diagram konsisten, gunakan istilah berikut di seluruh use case, activity, sequence, dan ERD.

| Istilah Lama di Diagram | Istilah Baku yang Disarankan |
|---|---|
| ADMIN | Admin HR |
| MANAGER | Manager/Atasan |
| check list | Setujui Task List |
| check in saja | Presensi Masuk dan Pulang |
| hari raya dan libur | Hari Libur Nasional |
| Order Lembur | Lembur Pegawai (atau tetap "Order Lembur" di semua diagram) |
| Setup Master (untuk semua menu) | Setup Master **dan** Setup Jadwal & Cuti (dipisah) |
| Kelola Form (generik) | Sebut nama modulnya, misalnya "Kelola Jabatan" |
| Cuti per Pegawai (di modul saldo) | Saldo Cuti Pegawai |

---

## 4. Struktur Menu Aplikasi

Berikut struktur menu yang sebenarnya ada di aplikasi. Gunakan sebagai acuan saat menggambar ulang diagram.

### 4.1 Menu Admin HR

```
Dashboard
Master Pegawai
Cuti
Setup Master
  ├── Jabatan
  ├── Golongan
  ├── Pangkat
  ├── Status Pegawai RS
  ├── Departemen
  └── Unit Kerja
Setup Jadwal & Cuti
  ├── Hari Libur Nasional
  ├── Group Shift
  ├── Shift Kerja
  ├── Jenis Cuti
  └── Saldo Cuti Pegawai
Jadwal Shift Pegawai
Absensi
  ├── Absensi
  ├── Daftar Hadir
  └── Daftar Alpha
Gaji
  ├── Gaji Bulanan
  ├── Skala Gaji Berkala
  └── Riwayat Gaji Pegawai
Portal Karyawan
Klaim & Lembur
  ├── Reimbursement Nota
  └── Lembur Pegawai
Titik Lokasi Absensi
Absensi Admin
```

### 4.2 Menu Portal (Karyawan, Manajer, Atasan)

```
Beranda Portal
Presensi
Riwayat Absensi
Cuti
  ├── Ajukan Cuti
  ├── Saldo Cuti
  └── Riwayat Cuti
Persetujuan (khusus Manager/Atasan)
  ├── Persetujuan Cuti
  └── Persetujuan Lembur
Absensi Tim (khusus Manager/Atasan/Admin HR)
Klaim & Lembur
  ├── Ajukan Klaim Nota
  ├── Ajukan Lembur
  └── Riwayat Pengajuan
Gaji / Slip Gaji
Profil
  ├── Lihat Profil
  ├── Ubah Password
  └── Persetujuan Dokumen (Consent)
```

---

## 5. Koreksi Use Case Diagram

### 5.1 Fitur yang Belum Ada di Diagram (Perlu Ditambahkan)

| No | Use Case | Aktor | Penjelasan |
|---|---|---|---|
| 1 | Setujui Dokumen Absensi | Karyawan, Manager/Atasan | Wajib dilakukan sebelum bisa absen |
| 2 | Reset Perangkat Karyawan | Admin HR | Menghapus ikatan HP lama saat karyawan ganti perangkat |
| 3 | Sinkronisasi Hari Libur | Admin HR | Mengambil data libur nasional secara otomatis |
| 4 | Sinkronisasi Saldo Cuti | Admin HR | Membuat saldo cuti semua pegawai untuk tahun tertentu |
| 5 | Tetapkan Titik Lokasi ke Pegawai | Admin HR | Menugaskan lokasi absen ke pegawai tertentu |

### 5.2 Fitur yang Digambar Berlebihan (Perlu Dikurangi)

| Modul | Yang Digambar | Yang Sebenarnya Ada |
|---|---|---|
| Reimbursement Nota (Admin) | Tambah, ubah, hapus, lihat | Hanya **lihat daftar** dan **setujui/tolak** |
| Gaji Bulanan | Tambah, ubah, hapus, lihat | Hanya **lihat rekap gaji** |
| Riwayat Gaji Pegawai | Tambah, ubah, hapus, lihat | Hanya **tambah** dan **lihat** |
| Jadwal Shift Pegawai | Hapus per baris | Hanya **isi/ubah jadwal**, tanpa hapus terpisah |

### 5.3 Koreksi Modul Presensi

**Yang benar di aplikasi:**

1. Pengguna membuka halaman presensi.
2. Pengguna **wajib** sudah menyetujui dokumen terlebih dahulu.
3. Sistem memverifikasi **lokasi GPS** (kecuali mode dinas luar).
4. Sistem memverifikasi **perangkat HP** (satu akun terikat satu perangkat).
5. Pengguna dapat menggunakan **verifikasi biometrik** jika perangkat mendukung.
6. Pengguna dapat mengaktifkan **mode dinas luar** jika tidak berada di kantor.
7. Pengguna melakukan **absen masuk** dan **absen pulang**.

**Relasi use case yang disarankan:**

```
Presensi Masuk dan Pulang  <<include>>  Setujui Dokumen Absensi
Presensi Masuk dan Pulang  <<extend>>   Verifikasi Lokasi GPS
Presensi Masuk dan Pulang  <<extend>>   Verifikasi Perangkat
Presensi Masuk dan Pulang  <<extend>>   Mode Dinas Luar
Presensi Masuk dan Pulang  <<extend>>   Verifikasi Biometrik
```

### 5.4 Koreksi Modul Cuti

| Aspek | Di Diagram | Di Aplikasi |
|---|---|---|
| Pengajuan cuti utama | Bisa dari admin | Utamanya dari **Portal Karyawan** |
| Penyetuju | Admin HR ikut menyetujui | **Manager/Atasan** yang menyetujui |
| Unggah surat | Tampak wajib | **Opsional** (boleh kosong) |
| Validasi saldo | Tidak digambarkan | Sistem **cek saldo** sebelum pengajuan disimpan |

### 5.5 Koreksi Modul Master Pegawai

Form data pegawai memiliki **lima tab**, bukan tiga:

1. Data Umum
2. Kepegawaian
3. Payroll
4. Riwayat Kepegawaian
5. Riwayat Keluar

Saat menambah pegawai baru, sistem juga **membuat akun login otomatis** menggunakan email pegawai. Password awal menggunakan NIK pegawai.

### 5.6 Penjelasan Relasi Include dan Extend — Modul Reimbursement

**Untuk Admin HR (panel admin):**

| Use Case | Relasi | Keterangan |
|---|---|---|
| Lihat Daftar Reimbursement | Use case utama | Admin membuka halaman daftar klaim |
| Setujui Reimbursement | <<extend>> | Dilakukan saat admin menyetujui salah satu pengajuan |
| Tolak Reimbursement | <<extend>> | Dilakukan saat admin menolak dengan alasan |

Admin **tidak memiliki** fitur menambah, mengubah, atau menghapus klaim nota.

**Untuk Karyawan (portal):**

| Use Case | Relasi | Keterangan |
|---|---|---|
| Ajukan Klaim Nota | Use case utama | Karyawan mengisi form klaim |
| Lihat Riwayat Klaim | <<include>> | Riwayat tampil di halaman yang sama |

### 5.7 Matriks Hak Akses per Aktor

| Fitur | Karyawan | Manajer | Atasan | Admin HR |
|---|---|---|---|---|
| Login | ✅ | ✅ | ✅ | ✅ |
| Presensi via portal | ✅ | ✅ | ✅ | ✅ |
| Setujui dokumen absensi | ✅ | ✅ | ✅ | ✅ |
| Ajukan cuti | ✅ | ✅ | ✅ | ❌ |
| Setujui cuti bawahan | ❌ | ✅ | ✅ | ⚠️ Hanya monitoring |
| Ajukan klaim nota | ✅ | ✅ | ✅ | ❌ |
| Setujui klaim nota | ❌ | ❌ | ✅ | ⚠️ Hanya monitoring |
| Ajukan lembur | ✅ | ✅ | ✅ | ❌ |
| Setujui lembur | ❌ | ✅ | ✅ | ❌ |
| Lihat absensi tim | ❌ | ✅ | ✅ | ✅ |
| Lihat gaji sendiri | ✅ | ✅ | ✅ | ❌ |
| Kelola master data | ❌ | ❌ | ❌ | ✅ |
| Kelola data pegawai | ❌ | ❌ | ❌ | ✅ |
| Reset perangkat pegawai | ❌ | ❌ | ❌ | ✅ |
| Kelola titik lokasi absensi | ❌ | ❌ | ❌ | ✅ |
| Lihat rekap gaji semua pegawai | ❌ | ❌ | ❌ | ✅ |

Keterangan: ✅ = bisa, ❌ = tidak bisa, ⚠️ = bisa melihat tetapi bukan peran utama.

---

## 6. Koreksi Activity Diagram

### 6.1 Diagram yang Perlu Diperbaiki Isinya

#### A. Activity Diagram Pengajuan Cuti

**Alur yang benar:**

```
[Mulai]
  ↓
Karyawan login ke sistem
  ↓
Masuk ke Portal → menu Cuti
  ↓
Sistem menampilkan saldo cuti dan form pengajuan
  ↓
Karyawan memilih jenis cuti
  ↓
Karyawan mengatur tanggal mulai dan selesai
  ↓
Karyawan mengisi keterangan/alasan
  ↓
Karyawan dapat mengunggah surat (opsional)
  ↓
Karyawan klik Kirim Pengajuan
  ↓
Sistem menghitung jumlah hari kerja (tidak termasuk weekend & libur)
  ↓
Apakah saldo cuti mencukupi?
  ├── TIDAK → Tampilkan pesan error → [Selesai]
  └── YA → Simpan pengajuan (status: menunggu persetujuan)
            ↓
       Manager/Atasan membuka menu Persetujuan
            ↓
       Apakah disetujui?
         ├── YA → Status menjadi disetujui → Tampilkan ke karyawan
         └── TIDAK → Isi alasan penolakan → Status ditolak → Tampilkan ke karyawan
  ↓
[Selesai]
```

**Perbaikan dari diagram lama:**

- Hapus langkah "Admin HR menyetujui" sebagai penyetuju utama.
- Tambahkan validasi saldo cuti.
- Tandai unggah surat sebagai opsional.

#### B. Activity Diagram Master Pegawai

**Alur yang benar:**

```
[Mulai]
  ↓
Admin HR login → Dashboard
  ↓
Klik menu Master Pegawai
  ↓
Sistem menampilkan daftar pegawai
  ↓
Admin memilih aksi:
  ├── Tambah Pegawai Baru
  │     ↓
  │   Tampilkan form dengan 5 tab:
  │   (Data Umum, Kepegawaian, Payroll, Riwayat Kepegawaian, Riwayat Keluar)
  │     ↓
  │   Admin mengisi data
  │     ↓
  │   Klik Simpan
  │     ↓
  │   Sistem validasi data
  │     ├── TIDAK valid → Tampilkan pesan error
  │     └── Valid → Simpan data pegawai
  │                   → Buat akun login otomatis (email + password awal NIK)
  │                   → Tampilkan notifikasi berhasil
  │
  ├── Edit Pegawai → Tampilkan form → Ubah data → Simpan
  │
  └── Hapus Pegawai → Konfirmasi → Hapus dari sistem
  ↓
[Selesai]
```

#### C. Activity Diagram Presensi

```
[Mulai]
  ↓
Karyawan login → Portal → menu Presensi
  ↓
Apakah sudah setujui dokumen absensi?
  ├── BELUM → Arahkan ke halaman persetujuan dokumen → [Kembali]
  └── SUDAH → Lanjut
  ↓
Sistem memverifikasi perangkat HP
  ↓
Sistem mengambil lokasi GPS
  ↓
Apakah mode dinas luar aktif?
  ├── YA → Lewati pengecekan jarak lokasi
  └── TIDAK → Cek apakah dalam radius titik absen
              ├── TIDAK → Tampilkan pesan lokasi tidak valid
              └── YA → Lanjut
  ↓
Karyawan melakukan absen masuk ATAU absen pulang
  ↓
Sistem menyimpan data absensi
  ↓
Tampilkan konfirmasi berhasil
  ↓
[Selesai]
```

### 6.2 Diagram yang Salah Salin (Harus Digambar Ulang)

| Halaman Diagram | Masalah | Perbaikan |
|---|---|---|
| Activity Status Pegawai RS (sekitar hal. 114–121) | Judul "Status Pegawai RS" tetapi isinya alur **Departemen** | Gambar ulang khusus untuk Status Pegawai RS |
| Activity Group Shift (sekitar hal. 158–163) | **Duplikat persis** dari halaman sebelumnya | Hapus salah satu |

### 6.3 Diagram yang Belum Ada (Perlu Ditambahkan)

| Modul | Keterangan |
|---|---|
| Pangkat | Sudah ada use case, tetapi belum ada activity diagram |
| Shift Kerja | Sudah ada use case, tetapi belum ada activity diagram |
| Persetujuan Dokumen (Consent) | Belum ada di use case maupun activity |
| Reset Perangkat Karyawan | Belum ada di use case maupun activity |

### 6.4 Koreksi Nama Menu di Activity Diagram

| Di Diagram Lama | Seharusnya |
|---|---|
| "Setup Master" untuk semua submenu | Pisahkan: **Setup Master** dan **Setup Jadwal & Cuti** |
| "hari raya dan libur" | **Hari Libur Nasional** |
| "klik menu lembur" (karyawan) | **Portal → Klaim & Lembur → Ajukan Lembur** |

---

## 7. Koreksi Sequence Diagram

### 7.1 Sequence Diagram Tambah Karyawan Baru

Diagram sequence yang sudah ada **hampir benar**. Berikut versi lengkap yang disarankan:

**Peserta (swimlane):**

- Admin HR
- Halaman Form Karyawan
- Sistem
- Data Pegawai
- Akun Login
- Basis Data

**Urutan interaksi:**

| Langkah | Dari | Ke | Pesan / Aksi |
|---|---|---|---|
| 1 | Admin HR | Halaman Form | Buka form tambah karyawan |
| 2 | Admin HR | Halaman Form | Isi data di semua tab yang diperlukan |
| 3 | Admin HR | Sistem | Klik Simpan |
| 4 | Sistem | Sistem | Validasi kelengkapan dan kebenaran data |
| 5a | Sistem | Admin HR | Jika tidak valid: tampilkan pesan error |
| 5b | Sistem | Data Pegawai | Jika valid: simpan data pegawai |
| 6 | Sistem | Akun Login | Buat atau perbarui akun login (email pegawai, password awal = NIK) |
| 7 | Sistem | Basis Data | Simpan riwayat kepegawaian (jika tab diisi) |
| 8 | Sistem | Admin HR | Tampilkan notifikasi berhasil |
| 9 | Admin HR | Halaman Form | Melihat pegawai baru di daftar |

---

## 8. Koreksi ERD

### 8.1 Entitas yang Sudah Benar

Entitas berikut sudah sesuai dan dapat dipertahankan:

- Karyawan
- Jabatan
- Golongan
- Pangkat
- Status Pegawai RS
- Departemen
- Unit Kerja
- Absensi
- Cuti
- Jenis Cuti
- Gaji
- Lembur
- Reimbursement
- Riwayat Gaji
- Skala Gaji Berkala
- Akun Login (Users)
- Data Wilayah (Provinsi, Kabupaten, Kecamatan, Kelurahan)

### 8.2 Entitas yang Perlu Ditambahkan

| Entitas | Hubungan | Keterangan |
|---|---|---|
| Hari Libur Nasional | Digunakan saat hitung cuti | Tidak terhubung langsung ke tabel cuti, tetapi dipakai perhitungan |
| Group Shift | Satu group punya banyak shift kerja | |
| Shift Kerja | Milik satu group shift | |
| Jadwal Shift Pegawai | Menghubungkan pegawai, group shift, dan shift per tanggal | |
| Saldo Cuti Pegawai | Per pegawai, per jenis cuti, per tahun | |
| Riwayat Kepegawaian | Milik satu pegawai | Data riwayat jabatan/kepegawaian |
| Persetujuan Dokumen | Milik satu akun login | Mencatat persetujuan surat perjanjian dan task list |
| Perangkat Pengguna | Milik satu akun login | Mencatat HP yang terdaftar untuk absensi |
| Titik Lokasi Absensi | Ditugaskan ke pegawai | Koordinat GPS dan radius absen |

### 8.3 Relasi yang Perlu Dikoreksi

| Di Diagram ERD | Seharusnya |
|---|---|
| Akun Login langsung terhubung ke Karyawan dengan garis FK | Terhubung **melalui email** (relasi logis, bukan kunci asing langsung) |
| Unit Kerja sejajar dengan Departemen | Unit Kerja **bagian dari** Departemen |
| Atasan dan bawahan | Dihubungkan lewat **NIK atasan** pada data karyawan (bukan tabel terpisah) |
| Alamat karyawan ke tabel wilayah | Karyawan menyimpan **nama wilayah sebagai teks**, bukan ID wilayah |

### 8.4 Ringkasan Relasi Utama

```
Departemen ──memiliki──> Unit Kerja
Group Shift ──memiliki──> Shift Kerja

Jabatan ──dimiliki──> Karyawan
Golongan ──dimiliki──> Karyawan
Pangkat ──dimiliki──> Karyawan
Status Pegawai RS ──dimiliki──> Karyawan
Departemen ──dimiliki──> Karyawan
Unit Kerja ──dimiliki──> Karyawan
Titik Lokasi Absensi ──ditugaskan ke──> Karyawan
Group Shift ──ditugaskan ke──> Karyawan

Karyawan ──melakukan──> Absensi
Karyawan ──mengajukan──> Cuti
Karyawan ──mengajukan──> Lembur
Karyawan ──mengajukan──> Reimbursement
Karyawan ──menerima──> Gaji
Karyawan ──memiliki──> Saldo Cuti
Karyawan ──memiliki──> Jadwal Shift
Karyawan ──memiliki──> Riwayat Kepegawaian
Karyawan ──memiliki──> Riwayat Gaji

Jenis Cuti ──menentukan──> Cuti
Jenis Cuti ──menentukan──> Saldo Cuti
Skala Gaji Berkala ──menentukan──> Riwayat Gaji

Akun Login ──menyetujui──> Cuti / Lembur / Reimbursement
Akun Login ──memiliki──> Perangkat Pengguna
Akun Login ──memiliki──> Persetujuan Dokumen
```

---

## 9. Cek Konsistensi Antar Diagram

### 9.1 Yang Sudah Konsisten ✅

| Aspek | Keterangan |
|---|---|
| Modul Setup Master | Jabatan, Golongan, Pangkat, Status RS, Departemen, Unit Kerja ada di use case dan activity |
| Pola CRUD master data | Tambah, lihat, ubah, hapus, dan pencarian cocok untuk modul master |
| Fitur portal karyawan | Absensi, cuti, gaji, profil sudah tercakup di use case |
| Sequence tambah karyawan | Alur besar sudah benar |
| Entitas utama ERD | Karyawan, absensi, cuti, gaji, lembur sudah ada |

### 9.2 Ketidakkonsistenan yang Harus Diperbaiki ❌

#### A. Nama tidak sama antar diagram

| Lokasi | Masalah |
|---|---|
| Activity Setup | Semua masuk "Setup Master", padahal ada dua menu terpisah |
| Activity hari libur | Pakai "hari raya dan libur", di menu asli "Hari Libur Nasional" |
| Use case lembur vs menu | "Order Lembur" vs "Lembur Pegawai" — pilih satu |

#### B. Use case vs activity — alur cuti bertentangan

| | Use Case | Activity |
|---|---|---|
| Penyetuju | Admin punya "Approve Cuti" | Manager setujui, Admin HR ikut |
| Yang benar | Admin monitoring, **Manager/Atasan menyetujui** | Satu penyetuju: Manager/Atasan |

#### C. Use case vs activity — lembur tidak sama

| | Use Case | Activity |
|---|---|---|
| Cara ajukan lembur | Portal pengajuan lembur | "Klik menu lembur" |
| Yang benar | **Portal → Klaim & Lembur → Ajukan Lembur** | Sama |

#### D. Use case ada, activity tidak ada

| Modul | Status |
|---|---|
| Pangkat | Use case ✅ — Activity ❌ |
| Shift Kerja | Use case ✅ — Activity ❌ |
| Group Shift | Activity duplikat ⚠️ |
| Consent / Persetujuan Dokumen | Keduanya belum ada ❌ |

#### E. Pola relasi include/extend tidak seragam

Masalah: semua modul memakai pola CRUD (tambah/ubah/hapus), padahal beberapa modul hanya bisa lihat dan setujui.

**Pola yang disarankan (konsisten):**

| Jenis Modul | Pola Use Case |
|---|---|
| Master data (jabatan, golongan, dll.) | Lihat Daftar → Tambah / Ubah / Hapus (extend) |
| Modul persetujuan (klaim, lembur) | Lihat Daftar → Setujui / Tolak (extend) |
| Modul baca saja (gaji bulanan) | Hanya "Lihat Rekap Gaji" |
| Portal presensi | Absen include Persetujuan Dokumen |

#### F. Use case vs ERD — entitas kurang lengkap

Entitas yang muncul di use case/activity tetapi belum ada di ERD:

- Hari Libur Nasional
- Group Shift dan Shift Kerja
- Saldo Cuti Pegawai
- Riwayat Kepegawaian
- Persetujuan Dokumen
- Perangkat Pengguna

---

## 10. Alur Bisnis yang Benar

### 10.1 Alur Presensi

1. Pengguna login.
2. Buka Portal → Presensi.
3. Jika belum setujui dokumen, diarahkan ke halaman persetujuan.
4. Setelah dokumen disetujui, sistem verifikasi perangkat.
5. Sistem mengambil lokasi GPS.
6. Jika tidak mode dinas luar, cek jarak ke titik absen.
7. Pengguna absen masuk atau pulang.
8. Data absensi tersimpan.

### 10.2 Alur Pengajuan Cuti

1. Karyawan login ke portal.
2. Buka menu Cuti → Ajukan Cuti.
3. Sistem menampilkan saldo cuti tersedia.
4. Karyawan pilih jenis cuti, tanggal, dan alasan.
5. Karyawan dapat melampirkan surat (opsional).
6. Sistem hitung hari kerja dan cek saldo.
7. Jika saldo cukup, pengajuan tersimpan dengan status menunggu persetujuan.
8. Manager/Atasan membuka menu Persetujuan.
9. Manager/Atasan menyetujui atau menolak (dengan alasan jika ditolak).
10. Karyawan melihat status terbaru di riwayat cuti.

### 10.3 Alur Pengajuan Lembur

1. Karyawan login ke portal.
2. Buka Klaim & Lembur → Ajukan Lembur.
3. Isi tanggal, jam mulai, jam selesai, jenis hari, keterangan.
4. Pilih opsi pembayaran (bulan ini atau bulan depan).
5. Dapat melampirkan bukti screenshot (opsional).
6. Centang pernyataan persetujuan.
7. Sistem hitung durasi dan nominal lembur otomatis.
8. Pengajuan tersimpan, menunggu persetujuan Manager/Atasan.

### 10.4 Alur Pengajuan Klaim Nota (Reimbursement)

1. Karyawan login ke portal.
2. Buka Klaim & Lembur → Ajukan Klaim Nota.
3. Isi tanggal, nominal, keterangan.
4. Dapat melampirkan foto nota (opsional).
5. Pengajuan tersimpan, menunggu persetujuan Atasan.

### 10.5 Alur Tambah Pegawai Baru (Admin HR)

1. Admin HR login ke dashboard.
2. Buka Master Pegawai → Tambah Pegawai.
3. Isi form di kelima tab sesuai kebutuhan.
4. Klik Simpan.
5. Sistem validasi data.
6. Data pegawai tersimpan.
7. Akun login otomatis dibuat (email pegawai, password awal = NIK).
8. Riwayat kepegawaian tersimpan jika tab diisi.
9. Pegawai baru muncul di daftar.

---

## 11. Checklist Perbaikan

### Prioritas Tinggi

- [ ] Tambahkan use case **Setujui Dokumen Absensi** dan hubungkan ke presensi
- [ ] Perbaiki activity cuti: penyetuju = **Manager/Atasan**, bukan Admin HR
- [ ] Hapus fitur tambah/ubah/hapus yang tidak ada di: Gaji Bulanan, Reimbursement Admin, Riwayat Gaji
- [ ] Perbaiki activity diagram **salah salin** (Status Pegawai RS isinya Departemen)
- [ ] Hapus **duplikat** activity Group Shift
- [ ] Samakan **nama menu** di semua diagram (lihat Daftar Istilah Baku)

### Prioritas Menengah

- [ ] Tambahkan activity diagram **Pangkat** dan **Shift Kerja**
- [ ] Lengkapi tab Master Pegawai menjadi **5 tab**
- [ ] Tambahkan use case **Reset Perangkat** dan **Sinkronisasi**
- [ ] Lengkapi ERD dengan entitas yang hilang
- [ ] Tambahkan peran **Atasan** atau label **Manager/Atasan**

### Prioritas Rendah (Penyempurnaan Skripsi)

- [ ] Buat glosarium istilah di awal bab perancangan
- [ ] Seragamkan pola relasi include/extend di semua modul
- [ ] Rapikan diagram Karyawan dan Manager yang hampir sama

---

## 12. Catatan untuk Penulisan Skripsi

### 12.1 Kalimat Pembuka yang Dapat Dipakai

> Use case diagram pada penelitian ini menggambarkan interaksi antara Admin HR, Manager/Atasan, dan Karyawan dengan Sistem HR Karyawan. Admin HR bertanggung jawab pada pengelolaan data master, data pegawai, jadwal kerja, lokasi absensi, laporan absensi, dan rekap gaji. Manager/Atasan menggunakan portal untuk memantau absensi bawahan serta memberikan persetujuan atau penolakan terhadap pengajuan cuti, lembur, dan klaim nota. Karyawan menggunakan portal untuk melakukan presensi, mengajukan cuti, klaim, lembur, serta melihat informasi gaji dan profil.

### 12.2 Hal yang Perlu Dijelaskan di Narasi (Bukan Hanya di Gambar)

1. Sebelum absen, karyawan wajib menyetujui dokumen perjanjian absensi dan task list.
2. Satu akun login hanya dapat terikat pada satu perangkat HP untuk keperluan absensi.
3. Pengajuan cuti divalidasi terhadap saldo cuti yang tersedia.
4. Penyetujuan pengajuan (cuti, lembur, klaim) dilakukan oleh Manager/Atasan, bukan Admin HR.
5. Akun login pegawai dibuat otomatis saat Admin HR menambah data pegawai baru.

### 12.3 Tips Konversi ke PDF

Dokumen ini dapat dikonversi ke PDF menggunakan:

- **VS Code** dengan ekstensi "Markdown PDF"
- **Pandoc** di terminal
- Situs **dillinger.io** atau **markdowntopdf.com**
- Buka di browser lalu cetak sebagai PDF (Ctrl+P)

Untuk hasil terbaik, gunakan ukuran kertas A4, margin 2 cm, dan font yang mudah dibaca (misalnya Times New Roman atau Calibri 11pt).

---

*Dokumen ini disusun berdasarkan analisis implementasi aplikasi HR Karyawan dan perbandingan dengan diagram capstone draw.io.*
