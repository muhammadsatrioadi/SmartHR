# ERD — Database `hr_hr`

Entity Relationship Diagram berdasarkan dump `hr_hr.sql` (MariaDB, Jun 2026).

Dirender di VS Code, GitHub, atau [mermaid.live](https://mermaid.live).

## Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Autentikasi & Portal](#2-autentikasi--portal)
3. [Master Data HR](#3-master-data-hr)
4. [Karyawan (Entitas Pusat)](#4-karyawan-entitas-pusat)
5. [Presensi & Lokasi](#5-presensi--lokasi)
6. [Cuti & Saldo Cuti](#6-cuti--saldo-cuti)
7. [Jadwal Kerja](#7-jadwal-kerja)
8. [Payroll & Gaji](#8-payroll--gaji)
9. [Lembur & Reimbursement](#9-lembur--reimbursement)
10. [Wilayah Indonesia](#10-wilayah-indonesia)
11. [Tabel Sistem (Laravel)](#11-tabel-sistem-laravel)
12. [Relasi Logis (Tanpa FK)](#12-relasi-logis-tanpa-fk)

---

## Legenda

| Simbol | Arti |
|--------|------|
| `PK` | Primary Key |
| `FK` | Foreign Key (ada di database) |
| `||--o{` | Satu ke banyak (1:N) |
| `||--||` | Satu ke satu (1:1) |
| `}o--o{` | Banyak ke banyak (N:M) via tabel penghubung |

---

## 1. Gambaran Umum

Diagram ringkas hubungan antar modul utama.

```mermaid
erDiagram
    USERS ||--o{ USER_DEVICES : "memiliki"
    USERS ||--o{ ATTENDANCE_CONSENTS : "menyetujui"
    USERS ||--o{ CUTIS : "approve/reject"
    USERS ||--o{ OVERTIMES : "approve/reject"
    USERS ||--o{ REIMBURSEMENTS : "approve/reject"

    KARYAWANS ||--o{ ABSENSIS : "presensi"
    KARYAWANS ||--o{ CUTIS : "ajukan"
    KARYAWANS ||--o{ GAJIS : "gaji"
    KARYAWANS ||--o{ OVERTIMES : "lembur"
    KARYAWANS ||--o{ REIMBURSEMENTS : "klaim"
    KARYAWANS ||--o{ EMPLOYEE_SCHEDULES : "jadwal"
    KARYAWANS ||--o{ EMPLOYEE_LEAVE_BALANCES : "saldo cuti"
    KARYAWANS ||--o{ EMPLOYEE_SALARY_HISTORIES : "riwayat gaji"
    KARYAWANS ||--o{ EMPLOYEE_EMPLOYMENT_HISTORIES : "riwayat kerja"

    JABATANS ||--o{ KARYAWANS : "jabatan"
    DEPARTMENTS ||--o{ WORK_UNITS : "memiliki"
    DEPARTMENTS ||--o{ KARYAWANS : "departemen"
    ATTENDANCE_LOCATIONS ||--o{ KARYAWANS : "titik absen"
    ATTENDANCE_LOCATIONS ||--o{ ABSENSIS : "lokasi check-in"
    LEAVE_TYPES ||--o{ CUTIS : "jenis cuti"
    LEAVE_TYPES ||--o{ EMPLOYEE_LEAVE_BALANCES : "kuota"
    SHIFT_GROUPS ||--o{ WORK_SHIFTS : "grup shift"
    SHIFT_GROUPS ||--o{ KARYAWANS : "grup shift"
    SALARY_STEPS ||--o{ EMPLOYEE_SALARY_HISTORIES : "pangkat gaji"

    USERS {
        bigint id PK
        varchar email UK
        varchar role
    }

    KARYAWANS {
        bigint id PK
        varchar nik UK
        varchar email
        varchar nik_atasan
        bigint jabatan_id FK
    }
```

> **Catatan:** `users` dan `karyawans` dihubungkan aplikasi lewat **email** (bukan FK di database).

---

## 2. Autentikasi & Portal

```mermaid
erDiagram
    USERS ||--o{ USER_DEVICES : "1 perangkat"
    USERS ||--o{ ATTENDANCE_CONSENTS : "consent absensi"
    USERS ||--o| SESSIONS : "session aktif"

    USERS {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        varchar role "admin_hr|atasan|manajer|karyawan"
        varchar imgProfile
        timestamp email_verified_at
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    USER_DEVICES {
        bigint id PK
        bigint user_id FK
        varchar device_fingerprint UK
        varchar device_label
        text user_agent
        varchar platform
        timestamp registered_at
        timestamp last_used_at
    }

    ATTENDANCE_CONSENTS {
        bigint id PK
        bigint user_id FK
        enum jenis "perjanjian_absensi|task_list_flowchart"
        tinyint disetujui
        timestamp disetujui_pada
        varchar ip_address
        text catatan
    }

    SESSIONS {
        varchar id PK
        bigint user_id FK
        varchar ip_address
        text user_agent
        longtext payload
        int last_activity
    }

    PASSWORD_RESET_TOKENS {
        varchar email PK
        varchar token
        timestamp created_at
    }
```

---

## 3. Master Data HR

```mermaid
erDiagram
    DEPARTMENTS ||--o{ WORK_UNITS : "unit kerja"
    SHIFT_GROUPS ||--o{ WORK_SHIFTS : "shift"

    JABATANS {
        bigint id PK
        varchar nama_jabatan
        time jam_mulai_kerja
        time jam_selesai_kerja
        int gaji_pokok
        int tunjangan
        int potongan
    }

    GOLONGANS {
        bigint id PK
        varchar kode UK
        varchar nama
        tinyint is_active
    }

    PANGKATS {
        bigint id PK
        varchar kode UK
        varchar nama
        tinyint is_active
    }

    EMPLOYEE_STATUSES {
        bigint id PK
        varchar kode UK
        varchar nama
        tinyint is_payroll
        tinyint is_active
    }

    DEPARTMENTS {
        bigint id PK
        varchar kode UK
        varchar nama
        tinyint is_active
    }

    WORK_UNITS {
        bigint id PK
        bigint department_id FK
        varchar kode UK
        varchar nama
        tinyint is_active
    }

    SHIFT_GROUPS {
        bigint id PK
        varchar kode UK
        varchar nama
        varchar tipe_absen
        int istirahat_menit
    }

    WORK_SHIFTS {
        bigint id PK
        bigint shift_group_id FK
        varchar kode UK
        varchar nama
        time jam_masuk
        time jam_pulang
        int toleransi_telat_menit
        tinyint is_aktif
    }

    LEAVE_TYPES {
        bigint id PK
        varchar kode UK
        varchar nama
        varchar grup
        decimal kuota_hari
        tinyint pakai_periode
        enum satuan_periode
    }

    SALARY_STEPS {
        bigint id PK
        varchar kode UK
        varchar deskripsi
        decimal gaji_pokok
        decimal tunjangan_tetap
        tinyint hari_kerja_per_minggu
        int masa_kerja_min
        int masa_kerja_maks
    }

    HOLIDAYS {
        bigint id PK
        date tanggal UK
        varchar kode_libur
        varchar keterangan
        tinyint is_tetap
        tinyint is_hari_raya
    }

    ATTENDANCE_LOCATIONS {
        bigint id PK
        varchar nama
        decimal latitude
        decimal longitude
        smallint radius_meter
        tinyint is_aktif
    }
```

---

## 4. Karyawan (Entitas Pusat)

Tabel `karyawans` memiliki 80+ kolom. Diagram ini menampilkan kolom kunci dan relasi FK.

```mermaid
erDiagram
    JABATANS ||--o{ KARYAWANS : "wajib"
    GOLONGANS ||--o{ KARYAWANS : "opsional"
    PANGKATS ||--o{ KARYAWANS : "opsional"
    EMPLOYEE_STATUSES ||--o{ KARYAWANS : "opsional"
    DEPARTMENTS ||--o{ KARYAWANS : "opsional"
    WORK_UNITS ||--o{ KARYAWANS : "opsional"
    SHIFT_GROUPS ||--o{ KARYAWANS : "opsional"
    ATTENDANCE_LOCATIONS ||--o{ KARYAWANS : "titik absen"

    KARYAWANS {
        bigint id PK
        varchar nik UK
        varchar nbm
        varchar name
        varchar nama_lengkap
        varchar email UK
        varchar jenis_kelamin
        varchar telephone
        varchar handphone
        varchar status
        bigint jabatan_id FK "NOT NULL"
        bigint golongan_id FK
        bigint pangkat_id FK
        bigint employee_status_id FK
        bigint department_id FK
        bigint work_unit_id FK
        bigint shift_group_id FK
        bigint attendance_location_id FK
        varchar nik_atasan "atasan via NIK"
        date tanggal_masuk
        int total_kontak
        varchar ktp
        varchar NPWP
        text alamat
        varchar provinsi
        varchar kota
        tinyint aktif_dinas
        timestamp created_at
        timestamp updated_at
    }
```

### Hierarki Atasan (logis, tanpa FK)

```mermaid
erDiagram
    KARYAWANS ||--o{ KARYAWANS : "nik_atasan → nik"

    KARYAWANS {
        varchar nik PK
        varchar nik_atasan FK_logis
        varchar name
    }
```

---

## 5. Presensi & Lokasi

```mermaid
erDiagram
    KARYAWANS ||--o{ ABSENSIS : "catat"
    ATTENDANCE_LOCATIONS ||--o{ ABSENSIS : "lokasi"

    ABSENSIS {
        bigint id PK
        bigint karyawan_id FK
        enum status_absen "hadir|alpha|cuti"
        enum tipe_absen "masuk|pulang"
        date tanggal_absensi
        time time
        decimal latitude
        decimal longitude
        decimal accuracy
        smallint jarak_meter
        bigint attendance_location_id FK
        varchar device_fingerprint
        varchar biometric_credential_id
        tinyint biometric_verified
        tinyint lokasi_dinas
        text catatan
        varchar user_agent
        varchar keterangan
    }

    ATTENDANCE_LOCATIONS {
        bigint id PK
        varchar nama
        decimal latitude
        decimal longitude
        smallint radius_meter
        tinyint is_aktif
    }

    KARYAWANS {
        bigint id PK
        bigint attendance_location_id FK
        varchar nik
        varchar name
    }
```

---

## 6. Cuti & Saldo Cuti

```mermaid
erDiagram
    KARYAWANS ||--o{ CUTIS : "mengajukan"
    LEAVE_TYPES ||--o{ CUTIS : "jenis"
    KARYAWANS ||--o{ EMPLOYEE_LEAVE_BALANCES : "saldo tahunan"
    LEAVE_TYPES ||--o{ EMPLOYEE_LEAVE_BALANCES : "per jenis"
    USERS ||--o{ CUTIS : "approved_by_supervisor_id"
    USERS ||--o{ CUTIS : "approved_by_hr_id"
    USERS ||--o{ CUTIS : "rejected_by_id"

    CUTIS {
        bigint id PK
        bigint karyawan_id FK
        bigint leave_type_id FK
        date tanggal_mulai
        date tanggal_berakhir
        varchar keterangan
        varchar lampiran
        varchar jenis_cuti
        varchar status "menunggu_atasan|disetujui|ditolak"
        int saldo_awal
        int hak_diambil
        int saldo_sisa
        bigint approved_by_supervisor_id FK
        timestamp approved_at_supervisor
        bigint approved_by_hr_id FK
        timestamp approved_at_hr
        bigint rejected_by_id FK
        varchar rejected_reason
    }

    EMPLOYEE_LEAVE_BALANCES {
        bigint id PK
        bigint karyawan_id FK
        bigint leave_type_id FK
        smallint tahun
        decimal kuota
        decimal terpakai
        decimal sisa
    }

    LEAVE_TYPES {
        bigint id PK
        varchar kode
        varchar nama
        decimal kuota_hari
    }

    HOLIDAYS {
        bigint id PK
        date tanggal
        varchar keterangan
    }
```

> `holidays` tidak punya FK ke `cutis`, tetapi dipakai aplikasi untuk menghitung hari kerja saat pengajuan cuti.

---

## 7. Jadwal Kerja

```mermaid
erDiagram
    KARYAWANS ||--o{ EMPLOYEE_SCHEDULES : "jadwal harian"
    SHIFT_GROUPS ||--o{ EMPLOYEE_SCHEDULES : "grup"
    WORK_SHIFTS ||--o{ EMPLOYEE_SCHEDULES : "shift"

    EMPLOYEE_SCHEDULES {
        bigint id PK
        bigint karyawan_id FK
        bigint shift_group_id FK
        bigint work_shift_id FK
        date tanggal
        tinyint is_libur
    }

    KARYAWANS {
        bigint id PK
        bigint shift_group_id FK
        varchar name
    }

    SHIFT_GROUPS {
        bigint id PK
        varchar kode
        varchar nama
    }

    WORK_SHIFTS {
        bigint id PK
        bigint shift_group_id FK
        time jam_masuk
        time jam_pulang
    }
```

---

## 8. Payroll & Gaji

```mermaid
erDiagram
    KARYAWANS ||--o{ GAJIS : "rekap bulanan"
    KARYAWANS ||--o{ EMPLOYEE_SALARY_HISTORIES : "riwayat"
    SALARY_STEPS ||--o{ EMPLOYEE_SALARY_HISTORIES : "step gaji"
    USERS ||--o{ EMPLOYEE_SALARY_HISTORIES : "disetujui_oleh"
    JABATANS ||--o{ KARYAWANS : "komponen default"

    GAJIS {
        bigint id PK
        bigint karyawan_id FK
        decimal total
        decimal potongan
        decimal tunjangan
    }

    EMPLOYEE_SALARY_HISTORIES {
        bigint id PK
        bigint karyawan_id FK
        bigint salary_step_id FK
        decimal gaji_pokok
        decimal tunjangan_tetap
        decimal tunjangan_lain
        tinyint hari_kerja_per_minggu
        date tanggal_berlaku
        varchar alasan
        bigint disetujui_oleh FK
    }

    SALARY_STEPS {
        bigint id PK
        varchar kode
        decimal gaji_pokok
        decimal tunjangan_tetap
    }

    JABATANS {
        bigint id PK
        int gaji_pokok
        int tunjangan
        int potongan
    }

    KARYAWANS {
        bigint id PK
        bigint jabatan_id FK
        int total_kontak
    }
```

---

## 9. Lembur & Reimbursement

```mermaid
erDiagram
    KARYAWANS ||--o{ OVERTIMES : "ajukan lembur"
    KARYAWANS ||--o{ REIMBURSEMENTS : "ajukan klaim"
    USERS ||--o{ OVERTIMES : "approve/reject"
    USERS ||--o{ REIMBURSEMENTS : "approve/reject"

    OVERTIMES {
        bigint id PK
        bigint karyawan_id FK
        date tanggal
        time jam_mulai
        time jam_selesai
        decimal jumlah_jam
        enum jenis_hari "hari_kerja|hari_libur|hari_raya"
        decimal dasar_upah_lembur
        decimal upah_per_jam
        decimal nominal_lembur
        varchar keterangan_pekerjaan
        varchar status "menunggu_approval|disetujui|ditolak"
        varchar pilihan_pembayaran
        varchar bukti_screenshot
        bigint approved_by_supervisor_id FK
        timestamp approved_at_supervisor
        bigint approved_by_hr_id FK
        bigint rejected_by_id FK
        varchar rejected_reason
    }

    REIMBURSEMENTS {
        bigint id PK
        bigint karyawan_id FK
        date tanggal
        varchar tipe "nota|dll"
        decimal nominal
        text keterangan
        varchar lampiran
        enum status "pending|disetujui_atasan|disetujui|ditolak"
        bigint approved_by_supervisor_id FK
        timestamp approved_at_supervisor
        bigint approved_by_hr_id FK
        bigint rejected_by_id FK
        varchar rejected_reason
    }
```

---

## 10. Wilayah Indonesia

Data referensi alamat (tidak terhubung FK ke `karyawans`; karyawan menyimpan nama wilayah sebagai teks).

```mermaid
erDiagram
    PROVINCES ||--o{ REGENCIES : "kab/kota"
    REGENCIES ||--o{ DISTRICTS : "kecamatan"
    DISTRICTS ||--o{ VILLAGES : "kelurahan"

    PROVINCES {
        char id PK "2 digit"
        varchar name
    }

    REGENCIES {
        char id PK "4 digit"
        char province_id FK
        varchar name
    }

    DISTRICTS {
        char id PK "7 digit"
        char regency_id FK
        varchar name
    }

    VILLAGES {
        char id PK "10 digit"
        char district_id FK
        varchar name
    }
```

---

## 11. Tabel Sistem (Laravel)

Tabel infrastruktur framework — tidak terkait logika bisnis HR.

| Tabel | Fungsi |
|-------|--------|
| `migrations` | Riwayat migrasi database |
| `cache` / `cache_locks` | Cache aplikasi |
| `jobs` / `job_batches` / `failed_jobs` | Antrian background job |
| `sessions` | Session login web |
| `password_reset_tokens` | Reset password |

---

## 12. Relasi Logis (Tanpa FK)

Relasi ini dipakai aplikasi tetapi **tidak didefinisikan sebagai foreign key** di database.

| Dari | Ke | Cara Hubung | Kegunaan |
|------|----|-------------|----------|
| `users.email` | `karyawans.email` | Match string | Login portal ↔ data pegawai |
| `karyawans.nik_atasan` | `karyawans.nik` | Self-reference | Hierarki atasan–bawahan |
| `karyawans.provinsi/kota/...` | `provinces/regencies/...` | Nama teks (bukan ID) | Alamat pegawai |
| `holidays.tanggal` | perhitungan cuti | Query tanggal | Exclude hari libur |

```mermaid
erDiagram
    USERS }o--o| KARYAWANS : "email = email (logis)"
    KARYAWANS ||--o{ KARYAWANS : "nik_atasan = nik (logis)"

    USERS {
        varchar email
        varchar role
    }

    KARYAWANS {
        varchar email
        varchar nik
        varchar nik_atasan
    }
```

---

## Ringkasan Tabel (36 tabel)

| Grup | Tabel |
|------|-------|
| **Auth** | `users`, `user_devices`, `attendance_consents`, `sessions`, `password_reset_tokens` |
| **Master HR** | `jabatans`, `golongans`, `pangkats`, `employee_statuses`, `departments`, `work_units`, `shift_groups`, `work_shifts`, `leave_types`, `salary_steps`, `holidays`, `attendance_locations` |
| **Pegawai** | `karyawans`, `employee_employment_histories` |
| **Operasional** | `absensis`, `cutis`, `employee_leave_balances`, `employee_schedules`, `overtimes`, `reimbursements`, `gajis`, `employee_salary_histories` |
| **Wilayah** | `provinces`, `regencies`, `districts`, `villages` |
| **Sistem** | `migrations`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` |

---

## Daftar Foreign Key (Database)

| Tabel Anak | Kolom FK | Tabel Induk |
|------------|----------|-------------|
| `absensis` | `karyawan_id` | `karyawans` |
| `absensis` | `attendance_location_id` | `attendance_locations` |
| `attendance_consents` | `user_id` | `users` |
| `cutis` | `karyawan_id` | `karyawans` |
| `cutis` | `leave_type_id` | `leave_types` |
| `cutis` | `approved_by_supervisor_id` | `users` |
| `cutis` | `approved_by_hr_id` | `users` |
| `cutis` | `rejected_by_id` | `users` |
| `districts` | `regency_id` | `regencies` |
| `employee_employment_histories` | `karyawan_id` | `karyawans` |
| `employee_leave_balances` | `karyawan_id` | `karyawans` |
| `employee_leave_balances` | `leave_type_id` | `leave_types` |
| `employee_salary_histories` | `karyawan_id` | `karyawans` |
| `employee_salary_histories` | `salary_step_id` | `salary_steps` |
| `employee_salary_histories` | `disetujui_oleh` | `users` |
| `employee_schedules` | `karyawan_id` | `karyawans` |
| `employee_schedules` | `shift_group_id` | `shift_groups` |
| `employee_schedules` | `work_shift_id` | `work_shifts` |
| `gajis` | `karyawan_id` | `karyawans` |
| `karyawans` | `jabatan_id` | `jabatans` |
| `karyawans` | `golongan_id` | `golongans` |
| `karyawans` | `pangkat_id` | `pangkats` |
| `karyawans` | `employee_status_id` | `employee_statuses` |
| `karyawans` | `department_id` | `departments` |
| `karyawans` | `work_unit_id` | `work_units` |
| `karyawans` | `shift_group_id` | `shift_groups` |
| `karyawans` | `attendance_location_id` | `attendance_locations` |
| `overtimes` | `karyawan_id` | `karyawans` |
| `overtimes` | `approved_by_supervisor_id` | `users` |
| `overtimes` | `approved_by_hr_id` | `users` |
| `overtimes` | `rejected_by_id` | `users` |
| `regencies` | `province_id` | `provinces` |
| `reimbursements` | `karyawan_id` | `karyawans` |
| `reimbursements` | `approved_by_supervisor_id` | `users` |
| `reimbursements` | `approved_by_hr_id` | `users` |
| `reimbursements` | `rejected_by_id` | `users` |
| `user_devices` | `user_id` | `users` |
| `villages` | `district_id` | `districts` |
| `work_shifts` | `shift_group_id` | `shift_groups` |
| `work_units` | `department_id` | `departments` |

---

*Sumber: `hr_hr.sql` — phpMyAdmin dump, 22 Jun 2026*
