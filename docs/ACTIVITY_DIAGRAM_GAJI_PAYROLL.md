
# Diagram Activity — Perhitungan Gaji dan Payroll Tax PILAR HR

Dokumen ini berisi diagram activity (DA) dan sequence dalam format PlantUML, dengan interaksi antara Admin HR, Sistem, dan Database.

---

## 1. Diagram Activity Perhitungan Gaji (DA Gaji)

```plantuml
@startuml
skinparam swimlane {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam activity {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam note {
    BackgroundColor #FFFFFF
    BorderColor #000000
}

title
PERHITUNGAN GAJI ADMIN HR
end title

|Admin HR|
start
:Pilih Periode Gaji (Bulan &amp; Tahun);
|Sistem|
:Ambil Data Karyawan Aktif dari Database;
|Database|
:Query Tabel Karyawan;
|Sistem|
repeat
    :Resolusi Komponen Gaji;
    |Database|
    :Cek Riwayat Gaji Terakhir;
    |Sistem|
    if (Ada Riwayat Gaji Terakhir?) then (Ya)
        |Database|
        :Ambil Komponen dari Riwayat Gaji;
    else (Tidak)
        |Database|
        :Ambil Komponen dari Jabatan;
    endif
    |Sistem|
    :Hitung Lembur Bulan Ini;
    |Database|
    :Filter Overtime Status=Disetujui;
    :Filter Overtime Sesuai Bulan;
    :Sum Nominal Lembur;
    |Sistem|
    :Hitung Total Tunjangan;
    :Hitung Potongan Internal;
    :Hitung Gaji Bruto;
repeat while (Masih Ada Karyawan Lain?) is (Ya)
-> Tidak;
|Admin HR|
:Tampilkan Daftar Gaji;
stop

@enduml
```

---

## 2. Diagram Activity Perhitungan Payroll Tax (DA Payroll Tax)

```plantuml
@startuml
skinparam swimlane {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam activity {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam note {
    BackgroundColor #FFFFFF
    BorderColor #000000
}

title
PERHITUNGAN PAYROLL TAX ADMIN HR
end title

|Admin HR|
start
:Daftar Gaji Sudah Dihitung;
|Sistem|
repeat
    :Ambil Gaji Bruto Bulanan;
    |Database|
    :Ambil Data Gaji Bulanan;
    |Sistem|
    :Hitung BPJS Kesehatan;
    note right
    Dasar: Min(Gaji Bruto, Rp12.000.000)
    Iuran: Dasar × 1%
    end note
    :Hitung BPJS JHT &amp; JP;
    note right
    Dasar: Min(Gaji Bruto, Rp10.547.400)
    JHT: Dasar × 2%
    JP: Dasar × 1%
    end note
    :Hitung Penghasilan Neto Setelah Iuran;
    :Hitung PPh21;
    |Database|
    :Ambil Konfigurasi Payroll (Mode PPh21);
    |Sistem|
    if (Mode PPh21?) then (None)
        :PPh21 = Rp0;
    else (Flat Percent)
        :PPh21 = Penghasilan Neto × (Flat% / 100);
    endif
    :Hitung Total Potongan Wajib;
    :Hitung Take Home Pay (THP);
    note right
    THP = Gaji Bruto - Potongan Internal - Total Potongan Wajib
    end note
repeat while (Masih Ada Karyawan Lain?) is (Ya)
-> Tidak;
|Admin HR|
:Tampilkan Take Home Pay (THP) Perkiraan;
stop

@enduml
```

---

## 3. Diagram Activity Lengkap (Gaji + Payroll Tax)

```plantuml
@startuml
skinparam swimlane {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam activity {
    BackgroundColor #FFFFFF
    BorderColor #000000
}
skinparam note {
    BackgroundColor #FFFFFF
    BorderColor #000000
}

title
PERHITUNGAN GAJI DAN PAYROLL TAX LENGKAP
end title

|Admin HR|
start
:Pilih Periode Gaji;
|Sistem|
:Ambil Data Karyawan Aktif;
|Database|
:Query Tabel Karyawan;
|Sistem|
repeat
    :Resolusi Komponen Gaji;
    |Database|
    :Cek Riwayat Gaji Terakhir;
    |Sistem|
    if (Ada Riwayat Gaji Terakhir?) then (Ya)
        |Database|
        :Ambil Komponen dari Riwayat Gaji;
    else (Tidak)
        |Database|
        :Ambil Komponen dari Jabatan;
    endif
    |Sistem|
    :Hitung Lembur Bulan Ini;
    |Database|
    :Filter Overtime Status=Disetujui;
    :Filter Overtime Sesuai Bulan;
    :Sum Nominal Lembur;
    |Sistem|
    :Hitung Total Tunjangan;
    :Hitung Potongan Internal;
    :Hitung Gaji Bruto;
    :Hitung BPJS Kesehatan;
    note right
    Dasar: Min(Gaji Bruto, Rp12.000.000)
    Iuran: Dasar × 1%
    end note
    :Hitung BPJS JHT &amp; JP;
    note right
    Dasar: Min(Gaji Bruto, Rp10.547.400)
    JHT: Dasar × 2%
    JP: Dasar × 1%
    end note
    :Hitung Penghasilan Neto Setelah Iuran;
    :Hitung PPh21;
    |Database|
    :Ambil Konfigurasi Payroll;
    |Sistem|
    if (Mode PPh21?) then (None)
        :PPh21 = Rp0;
    else (Flat Percent)
        :PPh21 = Penghasilan Neto × (Flat% / 100);
    endif
    :Hitung Total Potongan Wajib;
    :Hitung Take Home Pay (THP);
repeat while (Masih Ada Karyawan Lain?) is (Ya)
-> Tidak;
|Admin HR|
:Lihat Daftar Gaji dengan Perhitungan Lengkap;
stop

@enduml
```

---

## 4. Diagram Sequence Perhitungan Gaji

```plantuml
@startuml
skinparam sequenceMessageAlign center
skinparam backgroundColor #FFFFFF

actor "Admin HR" as Admin
participant Browser
participant "GajiController" as Gaji
participant "PayrollCalculator" as Payroll
database Database

Admin -> Browser: Buka halaman /gajis
Browser -> Gaji: index()

activate Gaji
Gaji -> Database: Load semua Karyawan Aktif
activate Database
Database --> Gaji: Daftar Karyawan Aktif
deactivate Database

Gaji -> Database: Load semua Gaji + relasi\n(karyawan.jabatan, salaryHistories, overtimes)
activate Database
Database --> Gaji: Daftar Gaji beserta relasi
deactivate Database

loop Per setiap Karyawan / Record Gaji
    Gaji -> Database: Ambil Komponen Gaji Aktif\n(gaji_pokok, tunjangan)
    activate Database
    Database --> Gaji: Komponen Gaji Aktif
    deactivate Database

    Gaji -> Payroll: resolveSalaryComponents(karyawan)
    activate Payroll
    Payroll --> Gaji: Komponen Gaji (gaji_pokok, tunjangan_tetap, tunjangan_lain)
    deactivate Payroll

    Gaji -> Database: Hitung Lembur Bulan Ini\n(status=disetujui, sesuai bulan)
    activate Database
    Database --> Gaji: Total Nominal Lembur
    deactivate Database

    Gaji -> Gaji: Hitung Total Tunjangan\n(tunjangan_tetap + tunjangan_lain + lembur)
    Gaji -> Gaji: Hitung Potongan Internal\n(total_kontak × jabatan.potongan)
    Gaji -> Gaji: Hitung Gaji Bruto
end

deactivate Gaji

Gaji -> Browser: Render halaman gaji.index\n dengan daftar gaji lengkap
Browser -> Admin: Tampilkan daftar gaji
@enduml
```

---

## 5. Diagram Sequence Perhitungan Payroll Tax

```plantuml
@startuml
skinparam sequenceMessageAlign center
skinparam backgroundColor #FFFFFF

actor "Admin HR" as Admin
participant Browser
participant "GajiController" as Gaji
participant "StatutoryPayrollEstimator" as Stat
database Database

Admin -> Browser: Buka halaman /gajis (setelah perhitungan gaji)
Browser -> Gaji: index()

activate Gaji

loop Per setiap Karyawan / Record Gaji
    Gaji -> Stat: estimateMonthly(gaji_pokok, tunjangan_tetap,\n tunjangan_lain, lembur, potongan_internal)
    activate Stat

    Stat -> Stat: Hitung Gaji Bruto
    Stat -> Stat: Hitung BPJS Kesehatan\n(1% dari min(bruto, Rp 12.000.000))
    Stat -> Stat: Hitung BPJS JHT\n(2% dari min(bruto, Rp 10.547.400))
    Stat -> Stat: Hitung BPJS JP\n(1% dari min(bruto, Rp 10.547.400))
    Stat -> Stat: Hitung Penghasilan Neto\nSetelah Iuran
    Stat -> Stat: Hitung PPh21\n(jika mode flat_percent)
    Stat -> Stat: Hitung Total Potongan Wajib
    Stat -> Stat: Hitung Take Home Pay (THP)

    Stat --> Gaji: Hasil Estimasi (bruto, bpjs_kesehatan,\n bpjs_jht, bpjs_jp, pph21, thp)
    deactivate Stat
end

deactivate Gaji

Gaji -> Browser: Render halaman gaji.index\n dengan estimasi payroll tax
Browser -> Admin: Tampilkan daftar gaji beserta\n estimasi payroll tax dan THP
@enduml
```

---

## Keterangan:

1. **Perhitungan Gaji**:
   - Komponen gaji diambil dari riwayat gaji terakhir karyawan (tabel `employee_salary_histories`), jika tidak ada maka dari jabatan (tabel `jabatans`)
   - Lembur yang dihitung hanya yang berstatus "disetujui" dan sesuai bulan periode (tabel `overtimes`)
   - Potongan internal = total kontak × potongan jabatan (dari tabel `karyawans` dan `jabatans`)

2. **Perhitungan Payroll Tax**:
   - BPJS Kesehatan: dasar = min(gaji bruto, Rp12.000.000), iuran = 1%
   - BPJS JHT: dasar = min(gaji bruto, Rp10.547.400), iuran = 2%
   - BPJS JP: dasar = min(gaji bruto, Rp10.547.400), iuran = 1%
   - PPh21: bisa "none" (tidak dihitung) atau "flat percent" (persentase tetap), konfigurasi dari file `config/payroll.php`)
   - THP = gaji bruto - potongan internal - total potongan wajib

3. **Cara Melihat Diagram**:
   - **Activity &amp; Sequence Diagram (PlantUML)**: Salin kode PlantUML ke [plantuml.com/plantuml](https://www.plantuml.com/plantuml/)
   - Atau gunakan ekstensi PlantUML di VS Code
   - Atau gunakan plantuml.jar yang sudah ada di direktori `docs/`

4. **Referensi Kode**:
   - [GajiController.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Http/Controllers/GajiController.php)
   - [PayrollCalculator.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Support/PayrollCalculator.php)
   - [StatutoryPayrollEstimator.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/app/Support/StatutoryPayrollEstimator.php)
   - [payroll.php](file:///c:/xampp/htdocs/Aplikasi%20HR%20Karyawan/config/payroll.php)

