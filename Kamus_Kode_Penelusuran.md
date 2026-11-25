# KAMUS KODE PENELUSURAN — PILAR HR

> Dokumen acuan kode untuk laporan C-1 s.d. C-5.
> Disusun mengikuti **Tabel P.1** pada *Panduan Pelaksanaan Capstone Project* (`panduan_extracted.txt`, baris 909–920).
> Tujuan: satu kode menunjuk satu butir yang tetap, agar tidak perlu menyalin uraian berulang dan agar setiap kebutuhan dapat dilacak dari masalah → rancangan → implementasi → hasil uji.

---

## 1. Arti Kode (sumber: Tabel P.1)

| Kode | Arti dan penggunaan |
|---|---|
| **PR-xx** | *Problem* — masalah pengguna |
| **NEED-xx** | *Need* — kebutuhan pengguna (turunan dari PR) |
| **PC-xx** | *Product Characteristic* — karakteristik/kapabilitas produk |
| **FR-xx** | *Functional Requirement* — kebutuhan fungsional (fitur sistem) |
| **NFR-xx** | *Non-Functional Requirement* — kebutuhan nonfungsional (kualitas, keamanan, kinerja) |
| **DES-[jenis]-xx** | *Design* — artefak rancangan |
| **ACT-xx** | *Activity* — aktivitas implementasi |
| **FT-xx** | Uji fungsional (*functional test*) |
| **IT-xx** | Uji integrasi (*integration test*) |
| **NFT-xx** | Uji nonfungsional (*non-functional test*) |
| **ST-xx** | Uji keamanan (*security test*) |
| **CHG-xx** | *Change* — perubahan keputusan/rancangan |
| **BUG-xx** | Temuan kegagalan pengujian |
| **EV-xx** | *Evidence* — bukti (wawancara, rancangan, commit kode, hasil uji, validasi) |

**Aturan nomor:** angka pada kode hanya **penanda pembeda**, bukan urutan prioritas. Kode yang sama merujuk butir yang sama; satu kebutuhan boleh dikaitkan ke beberapa rancangan, bukti, dan kasus uji (panduan baris 925–927).

---

## 2. Jenis Kode DES (rancangan)

Sumber: panduan baris 930–942.

| Kode | Jenis rancangan |
|---|---|
| `DES-ARCH` | Arsitektur |
| `DES-UC` | Use Case Diagram |
| `DES-CL` | Class Diagram |
| `DES-SEQ` | Sequence Diagram |
| `DES-AD` | Activity Diagram |
| `DES-STATE` | State Diagram |
| `DES-DB` | Rancangan basis data / ERD |
| `DES-UI` | Rancangan UI/UX |
| `DES-FLOW` | Flowchart |
| `DES-DFD` | Data Flow Diagram |

Nomor terakhir = urutan artefak dalam jenis yang sama (contoh: `DES-UI-01`, `DES-UI-02`, `DES-SEQ-01`).

---

## 3. Letak Kode di Panduan

| Kode | Letak (bagian laporan) | Rujukan berkas panduan |
|---|---|---|
| PR, NEED | **C-1 B.4** | baris 1086 (Tabel 1.2a); ringkasan bukti Tabel 1.2 baris 1047 |
| PC | **C-1 B.5** | baris 1131 (Tabel 1.3) |
| FR | **C-2 B** | baris 1291 (Tabel 2.1) |
| NFR | **C-2 C** | baris 1332 (Tabel 2.2) |
| FR/NFR ↔ DES & uji | **C-2 E** | baris 1430 (Tabel 2.4 Matriks Keterlacakan) |
| FT, IT, NFT, ST (rencana) | **C-2 F** | baris 1477 (Tabel 2.5 Rencana Pengujian) |
| DES (semua jenis) | **C-3 B–E** | baris 1682–1690 |
| DES (keterlacakan) | **C-3 F** | baris 1709 (Tabel 3.1) |
| ACT | **C-3 G** | baris 1735 (Tabel 3.2) |
| NFR + ST (keamanan) | **C-3 H** | baris 1778 (Tabel 3.3) |
| EV (kontribusi) | **C-4 A** | baris 1891 (Tabel 4.1) |
| DES/FR + EV (implementasi) | **C-4 B.3** | baris 2002 (Tabel 4.3) |
| CHG | **C-4 B.4** | baris 2055 (Tabel 4.4); Lampiran B baris 2737 |
| FT, IT + EV (hasil) | **C-5 B** | baris 2271 (Tabel 5.1) |
| NFT (hasil) | **C-5 C** | baris 2409 (Tabel 5.2) |
| ST (hasil) | **C-5 D** | baris 2477 (Tabel 5.3) |
| BUG | **C-5 E** | baris 2542 (Tabel 5.4) |
| PR/NEED/FR/BUG (evaluasi) | **C-5 H** | baris 2630 (Tabel 5.6) |
| EV (rincian bukti) | **Lampiran G** | baris 920; kasus uji Lampiran C baris 2769 |

---

## 4. Letak Kode di Laporan PILAR HR (`Laporan_Capstone_PILAR_HR_C1-C5.md`)

| Kode | Letak |
|---|---|
| PR, NEED | C-1 **B.4** — Tabel hubungan masalah & kebutuhan (baris 146–149) |
| PC | C-1 **B.5** — karakteristik produk (baris 157–162) |
| FR | C-2 **B** — Tabel 2.1 (baris 310–321) |
| NFR | C-2 **C** — Tabel 2.2 (baris 329–335) |
| FR/NFR ↔ DES & uji | C-2 **E** — Matriks Keterlacakan (baris 363–373) |
| FT/IT/NFT/ST (rencana) | C-2 **F** — Rencana Pengujian (baris 381–398) |
| DES | C-3 **B–E** — daftar artefak (baris 520–531) |
| DES (keterlacakan) | C-3 **F** — Tabel 3.2 (baris 557–564) |
| ACT | C-3 **G** — Tabel 3.3 (baris 572–579) |
| NFR + ST (keamanan) | C-3 **H** — Tabel 3.4 (baris 589–593) |
| EV (kontribusi) | C-4 **A** — Tabel 4.1 (baris 666) |
| DES/FR + EV (implementasi) | C-4 **B.3** — Tabel 4.3 (baris 720–728) |
| CHG | C-4 **B.4** — Tabel 4.4 (baris 736–737) |
| EV (indeks bukti) | **Lampiran G** (baris 1089–1093) |

---

## 5. Daftar Kode Aktual Proyek PILAR HR

### 5.1 Masalah dan Kebutuhan Pengguna (C-1 B.4)

| Masalah (PR) | Rumusan | Kebutuhan (NEED) | Bukti |
|---|---|---|---|
| **PR-01** | Data pegawai tersebar di banyak berkas → duplikasi & tidak konsisten | **NEED-01** butuh satu basis data pegawai terpusat | EV-01 |
| **PR-02** | Kehadiran dicatat manual; rekap lambat & rawan keliru | **NEED-02** butuh presensi digital akurat, aman, terekap otomatis | EV-02 |
| **PR-03** | Cuti/lembur via pesan singkat tanpa rekam jejak | **NEED-03** butuh pengajuan & persetujuan tercatat dan berjenjang | EV-03 |
| **PR-04** | Laporan kepegawaian disusun manual & tidak konsisten | **NEED-04** butuh dashboard & laporan akurat dan mudah diakses | EV-04 |

### 5.2 Karakteristik Produk (C-1 B.5)

| Kode | Karakteristik | Sumber |
|---|---|---|
| **PC-01** | Master data pegawai terpusat | NEED-01 / EV-01 |
| **PC-02** | Presensi digital dengan pengamanan berlapis | NEED-02 / EV-02 |
| **PC-03** | Pengajuan & persetujuan cuti berjenjang | NEED-03 / EV-03 |
| **PC-04** | Pengajuan lembur | NEED-03 / EV-03 |
| **PC-05** | Pengelolaan jadwal kerja dan hari libur | NEED-03 / EV-02 |
| **PC-06** | Dashboard dan pelaporan HR | NEED-04 / EV-04 |

### 5.3 Kebutuhan Fungsional (C-2 Tabel 2.1)

| Kode | Fungsi | Sumber | Prioritas |
|---|---|---|---|
| **FR-01** | Login/logout sesuai peran; pengarahan halaman per peran | NEED-01 / EV-01 | Wajib |
| **FR-02** | Admin HR mengelola master data pegawai (CRUD) | NEED-01 / EV-01 | Wajib |
| **FR-03** | Admin HR mengelola data master pendukung (jabatan, golongan, dll.) | NEED-01 / EV-01 | Wajib |
| **FR-04** | Pegawai melakukan presensi masuk/pulang dengan verifikasi lokasi & perangkat | NEED-02 / EV-02 | Wajib |
| **FR-05** | Pengajuan & persetujuan cuti berjenjang; status terlihat | NEED-03 / EV-03 | Wajib |
| **FR-06** | Pengajuan & persetujuan lembur | NEED-03 / EV-03 | Wajib |
| **FR-07** | Admin HR mengelola jadwal shift, hari libur, jenis & saldo cuti | NEED-03 / EV-02 | Wajib |
| **FR-08** | Admin HR mengelola titik lokasi absensi & penugasannya | NEED-02 / EV-02 | Penting |
| **FR-09** | Dashboard & rekap kehadiran/cuti per periode | NEED-04 / EV-04 | Penting |
| **FR-10** | Pegawai melihat riwayat & mengelola profil | NEED-01 / EV-01 | Penting |
| **FR-11** | Persetujuan dokumen absensi (consent) sebagai prasyarat presensi | NEED-02 / EV-02 | Wajib |
| **FR-12** | Penyimpanan dokumen pendukung (mis. lampiran cuti) | NEED-03 / EV-03 | Tambahan |

### 5.4 Kebutuhan Nonfungsional (C-2 Tabel 2.2)

| Kode | Kategori | Target / kondisi uji |
|---|---|---|
| **NFR-01** | Kinerja | Respons rata-rata ≤ 3 detik |
| **NFR-02** | Keamanan | RBAC — akses tanpa hak ditolak (HTTP 403) |
| **NFR-03** | Keamanan | Password ter-*hash*; validasi input di sisi server |
| **NFR-04** | Keandalan | Fungsi utama pulih setelah restart layanan |
| **NFR-05** | Kemudahan penggunaan | ≥ 80% tugas utama selesai tanpa bantuan |
| **NFR-06** | Ketersediaan | Data pulih dari cadangan |
| **NFR-07** | Kompatibilitas | Responsif pada browser desktop & mobile modern |

### 5.5 Artefak Rancangan (C-3)

| Kode | Artefak |
|---|---|
| **DES-ARCH-01** | Arsitektur Sistem |
| **DES-DB-01** | Entity Relationship Diagram (ERD) |
| **DES-UC-01 / -02 / -03** | Use Case Diagram Admin HR / Manager / Pegawai |
| **DES-CL-01** | Class Diagram |
| **DES-SEQ-01 / -02** | Sequence Diagram Login & Role Redirect / Presensi |
| **DES-AD-01 / -02 / -03** | Activity Diagram Presensi / Pengajuan Cuti / Pengajuan Lembur |
| **DES-UI-01** | Rancangan Antarmuka Halaman Login |
| **DES-UI-02** | Rancangan Antarmuka Portal Presensi |
| **DES-UI-03** | Rancangan Antarmuka Dashboard Admin HR |
| **DES-UI-04** | Rancangan Antarmuka Pengajuan Cuti |

### 5.6 Aktivitas Implementasi (C-3 G)

| Kode | Aktivitas | Rujukan |
|---|---|---|
| **ACT-01** | Autentikasi & manajemen peran | FR-01 / DES-SEQ-01 |
| **ACT-02** | Master data pegawai & pendukung | FR-02, FR-03 / DES-DB-01 |
| **ACT-03** | Presensi aman | FR-04, FR-11 / DES-AD-01 |
| **ACT-04** | Cuti & approval | FR-05 / DES-AD-02 |
| **ACT-05** | Lembur | FR-06 / DES-AD-03 |
| **ACT-06** | Jadwal kerja & lokasi absensi | FR-07, FR-08 / DES-DB-01 |
| **ACT-07** | Dashboard & pelaporan | FR-09, FR-10 / DES-UI-03 |
| **ACT-08** | Kontrol keamanan & deployment | NFR-02, NFR-03 / DES-ARCH-01 |

### 5.7 Rencana Pengujian (C-2 F)

| Kode | Jenis | Kebutuhan yang diuji |
|---|---|---|
| **FT-01** | Fungsional | FR-01 |
| **FT-02** | Fungsional | FR-02, FR-03 |
| **FT-03** | Fungsional | FR-04 |
| **FT-04** | Fungsional (kondisi gagal) | FR-11 |
| **FT-05** | Fungsional | FR-05 |
| **FT-06** | Fungsional | FR-06 |
| **FT-07** | Fungsional | FR-07, FR-08 |
| **FT-08** | Fungsional | FR-09, FR-10 |
| **IT-01** | Integrasi | FR-01..FR-11 |
| **IT-02** | Integrasi | FR-05 |
| **NFT-01** | Nonfungsional (kinerja) | NFR-01 |
| **NFT-02** | Nonfungsional (keandalan) | NFR-04 |
| **NFT-03** | Nonfungsional (ketersediaan) | NFR-06 |
| **NFT-04** | Nonfungsional (kemudahan) | NFR-05, NFR-07 |
| **ST-01** | Keamanan | NFR-02 |
| **ST-02** | Keamanan | NFR-03 |
| **ST-03** | Keamanan | NFR-02, NFR-03 |
| **ST-04** | Keamanan | NFR-03 |

### 5.8 Perubahan dan Temuan (C-4 / C-5)

| Kode | Isi |
|---|---|
| **CHG-01** | Verifikasi biometrik menjadi lapisan tambahan dengan *fallback* (bukan syarat tunggal) |
| **CHG-02** | Perubahan label peran pada tampilan persetujuan menjadi "Manager" |
| **BUG-01 …** | *(belum ada — diisi setelah pengujian pada C-5)* |

### 5.9 Bukti (EV) — Lampiran G

| Rentang | Jenis bukti | Letak fisik |
|---|---|---|
| **EV-01..EV-05** | Notulen/observasi pengumpulan kebutuhan (Feb–Mar 2026) | Lampiran/notulen |
| **EV-10..EV-17** | Kode/screenshot implementasi modul | Repositori proyek |
| **EV-20..EV-27** | Screenshot/log pemasangan & pengoperasian | Lingkungan produksi |
| **EV-30..EV-47** | Hasil pengujian fungsional/nonfungsional/keamanan | Lampiran C & D |
| **EV-48..EV-51** | Umpan balik/mitra (validasi) | Lampiran E |

> **Catatan:** rentang `EV-06..09`, `EV-18..19`, `EV-28..29` sengaja belum dipakai. Kode EV cukup bila setiap bukti punya satu kode unik; nomor tidak harus berurutan rapat.

---

## 6. Alur Rujukan

```
PR → NEED → FR / NFR → DES-[jenis] → ACT → implementasi C-4 → FT/IT/NFT/ST di C-5
```

Setiap butir menunjuk **EV** sebagai bukti (panduan baris 928–929).

**Contoh utuh (PILAR HR):**
`PR-02 → NEED-02 → FR-04 → DES-AD-01 / DES-UI-02 → ACT-03 → Tabel 4.3 → FT-03, ST-02, ST-03 → EV-12, EV-3x`

**Contoh kerangka panduan:**
`PR-01 → NEED-01 → FR-01 → DES-UC-01 → implementasi C-4 → FT-01`

---

## 7. Aturan Penulisan Caption Diagram

Kode DES **tidak menggantikan** nomor gambar. Kode dicantumkan di dalam caption (panduan baris 953–960).

**Format:** `Gambar {nomor}. DES-{jenis}-{xx} – {Nama artefak}`

**Contoh:**
`Gambar 3.7. DES-UI-02 – Rancangan Antarmuka Portal Presensi`

Unsur: nomor gambar dokumen · kode artefak desain · nama artefak.

---

## 8. Catatan Konsistensi (perlu dilengkapi)

Butir berikut belum selaras dan perlu diperbaiki agar penelusuran tidak terputus:

1. **C-2 Tabel 2.4** masih memakai `DES-UI-05` dan `DES-UI-06` — kode ini belum ada di C-3 (C-3 hanya mendefinisikan `DES-UI-01..04`).
2. **C-2 Tabel 2.4**: `FR-02, FR-03 → DES-UI-01` (padahal `DES-UI-01` = Halaman Login) dan `FR-11 → DES-UI-03` (padahal `DES-UI-03` = Dashboard) — perlu diperbaiki pemetaannya.
3. **C-4 Tabel 4.3**: `DES-DB-01` dipakai dua kali untuk hal berbeda (master data & jadwal/lokasi).
4. **FR-12** belum muncul di C-3 F dan belum punya kode uji (FT/IT).
5. **Nomor tabel C-3**: panduan menetapkan Tabel 3.1 = keterlacakan; pada laporan tergeser karena ada tambahan tabel kamus data.
6. **Hasil uji C-5** masih "Belum dilaksanakan", sehingga rantai ke FT/IT/NFT/ST dan BUG belum terisi.

---

*Versi: 1.0 · Disusun sebagai acuan internal tim Grow in The Dark — Proyek PILAR HR.*
