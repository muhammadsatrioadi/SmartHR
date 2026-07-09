# Diagram Sequence — Aplikasi HR Karyawan

Dokumen ini berisi diagram sequence per modul/alur bisnis. Dapat dirender di GitHub, GitLab, VS Code (Mermaid), atau [mermaid.live](https://mermaid.live).

## Daftar Isi

1. [Login & Redirect](#1-login--redirect)
2. [Persetujuan Absensi (Consent)](#2-persetujuan-absensi-consent)
3. [Presensi Portal (Check-in/out)](#3-presensi-portal-check-inout)
4. [Sinkronisasi Presensi Offline](#4-sinkronisasi-presensi-offline)
5. [Pengajuan Cuti (Portal)](#5-pengajuan-cuti-portal)
6. [Persetujuan Cuti](#6-persetujuan-cuti)
7. [Lembur (Overtime)](#7-lembur-overtime)
8. [Reimbursement](#8-reimbursement)
9. [Payroll / Gaji (Admin)](#9-payroll--gaji-admin)
10. [Lihat Gaji (Portal Karyawan)](#10-lihat-gaji-portal-karyawan)
11. [Absensi Manual (Admin)](#11-absensi-manual-admin)
12. [Tambah Karyawan & Akun User](#12-tambah-karyawan--akun-user)
13. [Reset Perangkat Karyawan](#13-reset-perangkat-karyawan)
14. [Setup Titik Lokasi Absensi](#14-setup-titik-lokasi-absensi)

---

## Ringkasan Aktor

| Aktor | Peran |
|-------|-------|
| **Karyawan** | Pegawai biasa (`role=karyawan`) |
| **Atasan** | Supervisor (`role=atasan` / `manajer`) |
| **Admin HR** | Pengelola HR (`role=admin_hr`) |
| **Browser** | GPS, WebAuthn, localStorage |
| **Portal.js** | Script frontend portal |
| **Controller** | Laravel Controller |
| **Service** | Business logic (DeviceBinding, LeaveBalance, dll.) |
| **Database** | MySQL via Eloquent |

---

## 1. Login & Redirect

```mermaid
sequenceDiagram
    autonumber
    actor User as Pengguna
    participant Browser
    participant Login as LoginController
    participant Auth as Laravel Auth
    participant DB as Database

    User->>Browser: Buka halaman login (GET /)
    Browser->>Login: login()
    Login->>Auth: Cek session aktif?
    alt Sudah login
        Login-->>Browser: Redirect sesuai role
    else Belum login
        Login-->>Browser: Tampilkan form login
    end

    User->>Browser: Submit email & password
    Browser->>Login: actionlogin() (POST /)
    Login->>Auth: Auth::attempt()
    Auth->>DB: Validasi users

    alt Login gagal
        Auth-->>Login: false
        Login-->>Browser: Flash error → redirect /
    else Login berhasil
        Auth-->>Login: true
        Login->>Login: redirectAfterLogin()
        alt role = karyawan / manajer
            Login-->>Browser: Redirect → /portal
        else role = admin_hr / atasan
            Login-->>Browser: Redirect → /dashboard
        end
    end

    Note over User,DB: Logout: GET actionlogout → Auth::logout() → /
```

---

## 2. Persetujuan Absensi (Consent)

> Prasyarat sebelum karyawan bisa check-in presensi.

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser
    participant Portal as PortalController
    participant DB as Database

    Karyawan->>Browser: Buka /portal/consent
    Browser->>Portal: consent()
    Portal->>DB: Load AttendanceConsent (per user)
    Portal-->>Browser: Tampilkan dokumen persetujuan

    loop Setiap jenis dokumen
        Karyawan->>Browser: Klik setuju
        Browser->>Portal: storeConsent() (POST /portal/consent)
        Portal->>DB: updateOrCreate AttendanceConsent<br/>(disetujui=true, IP, timestamp)
        Portal-->>Browser: Sukses
    end

    Note over Karyawan,DB: Kedua jenis wajib ada:<br/>perjanjian_absensi & task_list_flowchart
```

---

## 3. Presensi Portal (Check-in/out)

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser
    participant JS as portal.js
    participant Presensi as PresensiController
    participant Device as DeviceBindingService
    participant Geo as GeolocationService
    participant DB as Database

    rect rgb(240, 248, 255)
        Note over Karyawan,DB: FASE 1 — Muat Halaman
        Karyawan->>Browser: Buka /portal/presensi
        Browser->>Presensi: index()
        Presensi->>DB: Karyawan, jadwal hari ini,<br/>absensi masuk/pulang, lokasi, consent
        Presensi-->>Browser: Render halaman + ATTENDANCE_LOCATIONS
        Browser->>JS: initClock(), initGpsWatch(), registerDevice()
    end

    rect rgb(255, 250, 240)
        Note over Karyawan,DB: FASE 2 — Registrasi Perangkat
        JS->>JS: getDeviceFingerprint() → localStorage
        JS->>Presensi: POST /portal/device/register
        Presensi->>Device: registerOrValidate()
        Device->>DB: Cek UserDevice
        alt Perangkat baru / sama
            Device-->>Presensi: OK
        else Perangkat berbeda
            Device-->>Presensi: 403 (satu email = satu perangkat)
        end
    end

    rect rgb(240, 255, 240)
        Note over Karyawan,DB: FASE 3 — Check-in / Check-out
        Karyawan->>Browser: Klik tombol Masuk / Pulang
        Browser->>JS: submitCheckin(tipe_absen)
        JS->>JS: registerDevice()
        JS->>Browser: getCurrentPosition() (GPS)
        JS->>JS: verifyBiometric() (WebAuthn)
        JS->>Presensi: POST /portal/presensi/checkin

        Presensi->>Presensi: hasRequiredConsents()
        alt Consent belum lengkap
            Presensi-->>Browser: 422 → redirect consent
        end

        Presensi->>Device: Validasi device binding
        Presensi->>Geo: Cek jarak ke titik lokasi
        alt Di luar radius & bukan lokasi dinas
            Presensi-->>Browser: 422 error geo-fence
        end

        Presensi->>DB: Cek duplikat masuk/pulang hari ini
        alt Sudah ada record sama
            Presensi-->>Browser: 422 error duplikat
        else Valid
            Presensi->>DB: Absensi::create (hadir, masuk/pulang)
            Presensi-->>Browser: JSON sukses → reload
        end
    end
```

---

## 4. Sinkronisasi Presensi Offline

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant JS as portal.js
    participant Storage as localStorage
    participant Presensi as PresensiController
    participant DB as Database

    Note over Karyawan,DB: Saat offline saat check-in

    Karyawan->>JS: submitCheckin()
    JS->>JS: Deteksi tidak ada koneksi
    JS->>Storage: Simpan ke hr_offline_attendance_queue
    JS-->>Karyawan: Notifikasi tersimpan offline

    Note over Karyawan,DB: Saat koneksi kembali (DOMContentLoaded / online)

    JS->>Storage: Baca antrian offline
    loop Setiap item antrian
        JS->>Presensi: POST /portal/presensi/checkin
        alt Sukses
            Presensi->>DB: Absensi::create
            JS->>Storage: Hapus item dari antrian
        else Gagal
            JS->>Storage: Biarkan di antrian
        end
    end
```

---

## 5. Pengajuan Cuti (Portal)

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser
    participant Portal as PortalController
    participant Leave as LeaveBalanceService
    participant DB as Database
    participant Storage as File Storage

    Karyawan->>Browser: Buka /portal/cuti/ajukan
    Browser->>Portal: ajukanCuti()
    Portal->>Leave: ensureBalancesForYear()
    Portal->>DB: Load LeaveType + EmployeeLeaveBalance
    Portal-->>Browser: Form pengajuan cuti

    Karyawan->>Browser: Isi tanggal, jenis cuti, lampiran
    Browser->>Portal: simpanCuti() (POST)
    Portal->>Portal: Validasi tanggal & file
    Portal->>Storage: Simpan lampiran (jika ada)
    Portal->>DB: Hitung hari kerja (exclude weekend + Holiday)

    Portal->>Leave: Cek sisa saldo cuti
    alt Saldo tidak cukup
        Portal-->>Browser: Error validasi
    else Saldo cukup
        Portal->>DB: Cuti::create<br/>status=menunggu_atasan
        Portal-->>Browser: Redirect /portal/cuti (sukses)
    end
```

---

## 6. Persetujuan Cuti

```mermaid
sequenceDiagram
    autonumber
    actor Atasan
    participant Browser
    participant Portal as PortalController
    participant Cuti as CutiController
    participant Leave as LeaveBalanceService
    participant DB as Database

    Atasan->>Browser: Buka /portal/cuti/approvals
    Browser->>Portal: approvals()
    Portal->>DB: Cuti menunggu_atasan<br/>filter nik_atasan = atasan.nik
    Portal-->>Browser: Daftar pengajuan bawahan

    alt Setujui
        Atasan->>Browser: Klik Setujui
        Browser->>Cuti: POST /cuti/{id}/approve-supervisor
        Cuti->>DB: Cek status = menunggu_atasan
        Cuti->>DB: Update status=disetujui,<br/>approved_by_supervisor_id, timestamp
        Cuti->>Leave: recalculateUsed()
        Leave->>DB: Update EmployeeLeaveBalance.terpakai
        Cuti-->>Browser: Redirect + sukses
    else Tolak
        Atasan->>Browser: Klik Tolak + alasan
        Browser->>Cuti: POST /cuti/{id}/reject
        Cuti->>DB: Update status=ditolak,<br/>rejected_reason, rejected_by_id
        Cuti-->>Browser: Redirect + sukses
    end

    Note over Atasan,DB: approveHr() dinonaktifkan —<br/>tidak ada langkah persetujuan HR
```

---

## 7. Lembur (Overtime)

### 7a. Pengajuan Lembur (Portal)

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser
    participant Reimb as ReimbursementController
    participant Payroll as PayrollCalculator
    participant DB as Database

    Karyawan->>Browser: Buka /portal/reimburse/overtime
    Browser-->>Karyawan: Form lembur

    Karyawan->>Browser: Submit jam, jenis hari, bulan bayar
    Browser->>Reimb: portalOvertimeStore() (POST)
    Reimb->>DB: Load EmployeeSalaryHistory + Jabatan
    Reimb->>Reimb: Hitung jumlah_jam
    Reimb->>Reimb: Validasi kuota harian & mingguan
    Reimb->>Payroll: calculateOvertimePay()
    Payroll-->>Reimb: nominal_lembur
    Reimb->>DB: Overtime::create<br/>status=menunggu_approval
    Reimb-->>Browser: Redirect sukses
```

### 7b. Persetujuan Lembur

```mermaid
sequenceDiagram
    autonumber
    actor Atasan
    participant Browser
    participant OT as OvertimeController
    participant DB as Database

    Atasan->>Browser: Lihat daftar lembur (admin/portal)
    Browser->>OT: GET /overtimes
    OT->>DB: Load Overtime menunggu_approval
    OT-->>Browser: Tampilkan daftar

    alt Setujui
        Atasan->>Browser: Klik Setujui
        Browser->>OT: POST /overtimes/{id}/approve-supervisor
        OT->>OT: Re-validasi kuota mingguan
        OT->>DB: status=disetujui
        OT-->>Browser: Sukses
    else Tolak
        Atasan->>Browser: Klik Tolak + alasan
        Browser->>OT: POST /overtimes/{id}/reject
        OT->>DB: status=ditolak
        OT-->>Browser: Sukses
    end

    Note over Atasan,DB: Lembur disetujui masuk perhitungan<br/>gaji bulan berjalan (GajiController)
```

---

## 8. Reimbursement

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    actor Atasan
    participant Browser
    participant Reimb as ReimbursementController
    participant DB as Database
    participant Storage as File Storage

    rect rgb(240, 248, 255)
        Note over Karyawan,Storage: Pengajuan
        Karyawan->>Browser: Buka /portal/reimburse/create
        Karyawan->>Browser: Isi nominal + upload bukti
        Browser->>Reimb: portalStore() (POST)
        Reimb->>Storage: Simpan gambar bukti
        Reimb->>DB: Reimbursement::create status=pending
        Reimb-->>Browser: Redirect sukses
    end

    rect rgb(255, 250, 240)
        Note over Atasan,Storage: Persetujuan
        Atasan->>Browser: Buka /reimbursements (admin)
        Browser->>Reimb: adminIndex()
        Reimb->>DB: Load Reimbursement pending
        Reimb-->>Browser: Daftar klaim

        alt Setujui
            Atasan->>Browser: Klik Setujui
            Browser->>Reimb: POST /reimbursements/{id}/approve-supervisor
            Reimb->>DB: status=disetujui
        else Tolak
            Atasan->>Browser: Klik Tolak + alasan
            Browser->>Reimb: POST /reimbursements/{id}/reject
            Reimb->>DB: status=ditolak
        end
    end
```

---

## 9. Payroll / Gaji (Admin)

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin HR
    participant Browser
    participant Gaji as GajiController
    participant Payroll as PayrollCalculator
    participant Stat as StatutoryPayrollEstimator
    participant DB as Database

    Admin->>Browser: Buka /gajis
    Browser->>Gaji: index()
    Gaji->>DB: Load semua Gaji + karyawan.jabatan,<br/>salaryHistories, overtimes

    loop Per karyawan / record gaji
        Gaji->>DB: Ambil komponen gaji aktif<br/>(gaji_pokok, tunjangan)
        Gaji->>Payroll: Hitung lembur bulan ini (disetujui)
        Gaji->>Gaji: Hitung potongan<br/>(total_kontak × jabatan.potongan)
        Gaji->>Stat: Estimasi BPJS, PPh21, THP
        Stat-->>Gaji: Komponen statutory
    end

    Gaji-->>Browser: Render gaji.index (tampilan saja)
    Note over Admin,DB: Tidak ada generate slip gaji —<br/>perhitungan on-the-fly untuk display
```

---

## 10. Lihat Gaji (Portal Karyawan)

```mermaid
sequenceDiagram
    autonumber
    actor Karyawan
    participant Browser
    participant Portal as PortalController
    participant DB as Database

    Karyawan->>Browser: Buka /portal/gaji
    Browser->>Portal: gaji()
    Portal->>DB: Gaji WHERE karyawan_id = user sendiri
    Portal-->>Browser: Tampilkan riwayat gaji (read-only)
```

---

## 11. Absensi Manual (Admin)

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin / Atasan
    participant Browser
    participant Absensi as AbsensiController
    participant DB as Database

    Admin->>Browser: Buka /absensi
    Browser->>Absensi: index()
    Absensi->>DB: Karyawan tanpa absensi hari ini
    Absensi-->>Browser: Daftar pegawai

    Admin->>Browser: Pilih karyawan + status (hadir/alpha)
    Browser->>Absensi: store() (POST /absensi/create-proses)
    Absensi->>DB: Absensi::create<br/>timestamp Jakarta
    Absensi-->>Browser: Redirect sukses

    Note over Admin,DB: Lihat laporan:<br/>GET /absensi/absensiHadir<br/>GET /absensi/absensiAlpha
```

---

## 12. Tambah Karyawan & Akun User

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin HR
    participant Browser
    participant Karyawan as KaryawanController
    participant DB as Database

    Admin->>Browser: Buka /karyawans/create
    Admin->>Browser: Isi data lengkap pegawai
    Browser->>Karyawan: store() (POST /karyawans/create-proses)
    Karyawan->>Karyawan: Validasi data
    Karyawan->>DB: Karyawan::create
    Karyawan->>DB: Simpan EmployeeEmploymentHistory

    alt Email diisi
        Karyawan->>DB: User::updateOrCreate<br/>role=karyawan (default)<br/>password = hash(NIK) atau default
    end

    Karyawan-->>Browser: Redirect daftar karyawan
```

---

## 13. Reset Perangkat Karyawan

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin HR
    participant Browser
    participant Karyawan as KaryawanController
    participant DB as Database

    Admin->>Browser: Klik Reset Device pada karyawan
    Browser->>Karyawan: resetDevice() (POST /karyawans/{id}/reset-device)
    Karyawan->>DB: Cari User by email karyawan
    Karyawan->>DB: Hapus UserDevice
    Karyawan-->>Browser: Sukses — karyawan bisa daftar perangkat baru
```

---

## 14. Setup Titik Lokasi Absensi

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin HR
    participant Browser
    participant Loc as AttendanceLocationController
    participant DB as Database

    rect rgb(240, 248, 255)
        Note over Admin,DB: CRUD Titik Lokasi
        Admin->>Browser: Kelola /attendance-locations
        Browser->>Loc: create/store/edit/update
        Loc->>DB: AttendanceLocation<br/>(nama, lat, lng, radius_meter, is_aktif)
    end

    rect rgb(240, 255, 240)
        Note over Admin,DB: Assign ke Karyawan
        Admin->>Browser: Pilih karyawan + titik lokasi
        Browser->>Loc: assign() (POST)
        Loc->>DB: Update karyawan.attendance_location_id
    end

    Note over Admin,DB: Digunakan oleh PresensiController@checkin<br/>untuk validasi geo-fence server-side
```

---

## Peta Modul & Alur

```mermaid
flowchart TB
    subgraph Portal["Portal Karyawan"]
        P1[Consent]
        P2[Presensi GPS]
        P3[Cuti]
        P4[Gaji]
        P5[Lembur]
        P6[Reimburse]
        P7[Profil]
    end

    subgraph Admin["Panel Admin HR"]
        A1[Dashboard]
        A2[Master Pegawai]
        A3[Master Data]
        A4[Absensi Manual]
        A5[Payroll]
        A6[Titik Lokasi]
        A7[Jadwal Shift]
    end

    subgraph Approval["Persetujuan Atasan"]
        AP1[Cuti]
        AP2[Lembur]
        AP3[Reimburse]
    end

    Login((Login)) --> Portal
    Login --> Admin

    P1 --> P2
    P3 --> AP1
    P5 --> AP2
    P6 --> AP3

    A2 --> Portal
    A6 --> P2
    A7 --> P2
    AP2 --> A5
```

---

*Terakhir diperbarui berdasarkan struktur kode di `routes/web.php` dan controller terkait.*
