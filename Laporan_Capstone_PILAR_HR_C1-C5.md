# IMPLEMENTASI SISTEM HUMAN RESOURCE MANAGEMENT (PILAR HR) BERBASIS WEB DENGAN METODE AGILE

## LAPORAN CAPSTONE PROJECT
**Dokumen Terpadu C-1 sampai dengan C-5**

| | |
|---|---|
| **Nama Tim** | Grow in The Dark |
| **Anggota** | Muhammad Satrioadi / 2311501028<br>Annisa Ashadia Nurhaliza Savana / 2311501050<br>Rizki Fachriadi Iskandar / 2311501054<br>Nathasya Destia Fany Sitorus / 2311501058<br>Nessa Aulia Rahma / 2311501024 |
| **Mitra** | PT Pilar Inovasi Informatika |
| **Dosen Pembimbing** | Dr. Arizona Firdonsyah, S.Kom., M.Kom. |
| **Tanggal Pengajuan** | 06 Maret 2026 |
| **Versi Dokumen** | v1.0 / 26 Oktober 2026 |

**PROGRAM STUDI TEKNOLOGI INFORMASI**
**FAKULTAS SAINS DAN TEKNOLOGI**
**UNIVERSITAS 'AISYIYAH YOGYAKARTA**
**TAHUN AKADEMIK 2025/2026**

> **Catatan penyusun (hapus sebelum pengumpulan):** Seluruh diagram pada dokumen ini **sengaja dikosongkan** karena masih dalam proses revisi. Setiap tempat diagram ditandai dengan blok bertanda *“Diagram dikosongkan — sedang direvisi”*. Modul **Penggajian (Payroll)** dan **Reimbursement** tidak termasuk dalam ruang lingkup dokumen ini.

---

## PETUNJUK PENGGUNAAN DAN KODE PENELUSURAN

Dokumen ini menggunakan satu sistem kode agar kebutuhan, rancangan, implementasi, dan hasil pengujian dapat ditelusuri satu sama lain.

| Kode | Arti dan penggunaan |
|---|---|
| PR-01 / NEED-01 | Masalah / kebutuhan pengguna; ditetapkan pada C-1 B.4 |
| PC-01 | Karakteristik produk pada C-1 B.5 |
| FR-01 / NFR-01 | Kebutuhan fungsional / nonfungsional pada C-2 |
| DES-[jenis]-xx / ACT-01 | Rancangan / aktivitas implementasi pada C-3 |
| FT-01 / IT-01 | Uji fungsional / integrasi; rencana di C-2, hasil di C-5 |
| NFT-01 / ST-01 | Uji nonfungsional / keamanan |
| CHG-01 / BUG-01 | Perubahan keputusan / temuan pengujian |
| EV-01 | Semua bukti: wawancara, rancangan, catatan perubahan kode, hasil uji, dan validasi |

**Kode jenis rancangan yang digunakan:** DES-ARCH (arsitektur), DES-UC (use case), DES-CL (class), DES-SEQ (sequence), DES-AD (activity), DES-DB (basis data/ERD), DES-UI (antarmuka), DES-FLOW (flowchart).

Contoh alur rujukan: `PR-01 → NEED-01 → FR-01 → DES-UC-01 → implementasi pada C-4 → FT-01`, masing-masing merujuk pada EV (bukti) terkait.

**Format dokumen:** kertas A4; margin 4 cm (atas & kiri), 3 cm (kanan & bawah); huruf Times New Roman (judul 14 pt, isi 12 pt, tabel 9–10 pt); spasi 1,5; nomor halaman kanan bawah; sitasi dan daftar referensi gaya **APA edisi ke-7**.

---

# ABSTRAK

PILAR HR merupakan sistem *Human Resource Management* (HRM) berbasis web yang dikembangkan untuk mitra **PT Pilar Inovasi Informatika**. Pengelolaan data pegawai pada mitra masih dilakukan secara manual dan terpisah-pisah, sehingga menimbulkan duplikasi data, keterlambatan penyampaian informasi, kesulitan pemantauan kehadiran, serta pelaporan kepegawaian yang lambat. Proyek ini merancang dan mengimplementasikan sistem terpusat yang mencakup autentikasi berbasis peran, master data pegawai, presensi digital dengan pengamanan berlapis (geofencing, *device binding*, biometrik, dan *attendance consent*), pengajuan serta persetujuan cuti, pengajuan lembur, pengelolaan jadwal kerja dan hari libur, serta dashboard dan pelaporan. Sistem dibangun menggunakan framework **Laravel 11**, basis data **MySQL/MariaDB**, dan antarmuka web responsif berbasis Bootstrap, dengan metode pengembangan **Agile Scrum** dalam beberapa sprint. Pengujian dilakukan secara fungsional, nonfungsional, integrasi, dan keamanan. Hasil pengujian dan evaluasi ketercapaian kebutuhan disajikan pada bagian C-5.

**Kata kunci:** sistem HRM, PILAR HR, Agile Scrum, Laravel, presensi digital, geofencing

---

# DAFTAR ISI

> *(Daftar isi otomatis dibuat di Microsoft Word melalui menu *References → Table of Contents* setelah templat disalin ke berkas Word.)*

# DAFTAR GAMBAR

> *(Daftar gambar otomatis di Word. Semua gambar pada versi ini dikosongkan sementara.)*

# DAFTAR TABEL

> *(Daftar tabel otomatis di Word.)*

# DAFTAR PERSAMAAN

> *(Diisi bila laporan memuat persamaan, misalnya rumus Haversine pada modul geofencing.)*

---
---

# C-1: PROPOSAL PROYEK

## A. Identitas Proyek

**Tabel 1.1. Identitas proyek**

| Elemen | Isian |
|---|---|
| Judul Proyek | Implementasi Sistem Human Resource Management (PILAR HR) Berbasis Web dengan Metode Agile |
| Nama Tim | Grow in The Dark |
| Anggota | 1. Muhammad Satrioadi / 2311501028<br>2. Annisa Ashadia Nurhaliza Savana / 2311501050<br>3. Rizki Fachriadi Iskandar / 2311501054<br>4. Nathasya Destia Fany Sitorus / 2311501058<br>5. Nessa Aulia Rahma / 2311501024 |
| Dosen Pembimbing | Dr. Arizona Firdonsyah, S.Kom., M.Kom. |
| Mitra / pihak utama | PT Pilar Inovasi Informatika |
| Tanggal Pengajuan | 06 Maret 2026 |
| Versi / tanggal dokumen | v1.0 / 26 Oktober 2026 |

## B. Latar Belakang dan Identifikasi Masalah

### B.1 Konteks Mitra dan Pemangku Kepentingan

Mitra proyek adalah **PT Pilar Inovasi Informatika**, sebuah organisasi yang mengelola sumber daya manusia dalam jumlah cukup banyak dan masih menjalankan sebagian besar proses administrasi kepegawaian secara manual, yaitu menggunakan berkas *spreadsheet* dan arsip dokumen fisik yang tersebar di beberapa bagian. Proses bisnis yang berjalan meliputi pencatatan data pegawai, pencatatan kehadiran, pengajuan dan persetujuan cuti, pengajuan lembur, pengelolaan jadwal kerja (shift), serta penyusunan laporan kepegawaian.

Pengguna utama sistem ini meliputi:

1. **Admin HR** — pengelola penuh data kepegawaian dan data master pendukung.
2. **Manager / Atasan** — membutuhkan akses terhadap data tim yang dipimpinnya, khususnya persetujuan cuti/lembur dan pemantauan kehadiran bawahan.
3. **Pegawai** — membutuhkan layanan mandiri (presensi, pengajuan cuti, pengajuan lembur, melihat riwayat kehadiran, dan pengelolaan profil).

Pemangku kepentingan lain yang terlibat adalah pihak manajemen mitra sebagai pemilik keputusan dan dosen pembimbing sebagai pihak yang memantau ketercapaian kompetensi.

> **Gambar 1.1. Konteks mitra dan pemangku kepentingan**
>
> *[Diagram dikosongkan — sedang direvisi.]*

### B.2 Bukti Kebutuhan dan Kondisi Saat Ini

Bukti kebutuhan diperoleh dari wawancara dengan pengelola kepegawaian mitra, observasi alur kerja administrasi, serta telaah dokumen dan berkas kerja yang digunakan saat ini. Ringkasan temuan disajikan pada tabel berikut.

**Tabel 1.2. Ringkasan bukti kebutuhan**

| Kode bukti | Sumber / tanggal | Temuan utama | Pihak terkait |
|---|---|---|---|
| EV-01 | Wawancara pengelola kepegawaian, Februari 2026 | Data pegawai dicatat pada berkas terpisah (buku induk, spreadsheet per bagian) sehingga sering terjadi duplikasi dan perbedaan data antar bagian | Admin HR |
| EV-02 | Observasi proses absensi, Februari 2026 | Kehadiran dicatat manual; rekap kehadiran disusun ulang setiap periode sehingga memakan waktu dan rawan keliru | Admin HR, Pegawai |
| EV-03 | Wawancara Manager/Atasan, Februari 2026 | Persetujuan cuti dan lembur dilakukan lewat pesan singkat tanpa rekam jejak, sehingga sulit ditelusuri dan dipantau statusnya | Manager, Pegawai |
| EV-04 | Telaah dokumen laporan, Maret 2026 | Laporan kepegawaian periodik disusun manual dari beberapa sumber, sehingga lambat dan tidak konsisten | Manajemen Mitra |
| EV-05 | Diskusi bersama mitra, Maret 2026 | Mitra membutuhkan sistem terpusat berbasis web agar mudah diakses dari berbagai perangkat tanpa instalasi aplikasi | Mitra |

> **Gambar 1.2. Dokumentasi kegiatan pengumpulan kebutuhan (wawancara/observasi)**
>
> *[Diagram/screenshot dikosongkan — sedang direvisi.]*

### B.3 Gejala, Akar Masalah, Dampak, dan Urgensi

Perkembangan teknologi informasi telah mendorong organisasi melakukan transformasi digital pada berbagai proses bisnis, termasuk pengelolaan sumber daya manusia (*Human Resource Management*) (Wibowo & Putri, 2023; Hartono & Susanti, 2024). Pengelolaan data pegawai yang masih dilakukan secara manual atau menggunakan aplikasi terpisah sering menimbulkan permasalahan seperti duplikasi data, keterlambatan penyampaian informasi, dan kesulitan pemantauan kehadiran (Wijaya & Santoso, 2023; Setiawan & Pratiwi, 2024).

**Gejala yang diamati.** Data pegawai sering tidak konsisten antar bagian; rekap kehadiran baru tersedia setelah beberapa hari; status pengajuan cuti/lembur tidak diketahui pegawai secara langsung; dan laporan kepegawaian memerlukan waktu lama untuk disusun.

**Akar masalah.** Tidak adanya satu basis data terpusat dan proses administrasi kepegawaian yang masih manual/terpisah-pisah. Setiap bagian menyimpan data secara mandiri sehingga tidak ada sumber kebenaran tunggal (*single source of truth*).

**Dampak.** (1) *Keandalan sistem*: keputusan berbasis data kepegawaian berisiko keliru karena data tidak konsisten. (2) *Keberlanjutan pengembangan*: proses manual sulit dikembangkan dan diaudit karena tidak memiliki rekam jejak digital.

**Urgensi.** Peningkatan jumlah pegawai membuat proses manual semakin tidak efisien. Diperlukan sistem terpusat yang menyatukan data pegawai, presensi, cuti, lembur, jadwal kerja, serta pelaporan.

### B.4 Rumusan Masalah

Rumusan masalah dinyatakan dari sudut pandang pengguna, lalu diterjemahkan menjadi kebutuhan pengguna (NEED) yang ditelusuri ke bukti.

**Tabel 1.2a. Hubungan masalah dan kebutuhan pengguna**

| Kode masalah | Rumusan masalah | Kode NEED | Kebutuhan pengguna | Kode bukti |
|---|---|---|---|---|
| PR-01 | Data pegawai tersebar di banyak berkas sehingga terjadi duplikasi dan data tidak konsisten | NEED-01 | Admin HR membutuhkan satu basis data pegawai terpusat yang dapat ditambah, diubah, dihapus, dan ditelusuri | EV-01 |
| PR-02 | Kehadiran dicatat manual dan rekap lambat serta rawan keliru | NEED-02 | Admin HR dan Pegawai membutuhkan presensi digital yang akurat, aman, dan otomatis terekapitulasi | EV-02 |
| PR-03 | Pengajuan cuti dan lembur dilakukan lewat pesan singkat tanpa rekam jejak | NEED-03 | Pegawai dan Manager membutuhkan proses pengajuan dan persetujuan cuti/lembur yang tercatat dan berjenjang | EV-03 |
| PR-04 | Laporan kepegawaian disusun manual dan tidak konsisten | NEED-04 | Manajemen membutuhkan dashboard dan laporan kepegawaian yang akurat serta mudah diakses | EV-04 |

### B.5 Karakteristik Produk yang Diinginkan dan Skenario Pemakaian Pemangku Kepentingan

**Tabel 1.3. Karakteristik produk dan skenario pemakaian pemangku kepentingan**

| Kode | Karakteristik produk | Kode kebutuhan / bukti | Skenario penggunaan | Indikator awal |
|---|---|---|---|---|
| PC-01 | Master data pegawai terpusat | NEED-01 / EV-01 | Admin HR menambah/mengubah data pegawai → sistem menyimpan → data tampil konsisten di seluruh modul | Data pegawai tercatat satu kali dan dapat ditelusuri |
| PC-02 | Presensi digital dengan pengamanan berlapis | NEED-02 / EV-02 | Pegawai melakukan presensi dari portal → sistem memverifikasi lokasi & perangkat → data presensi tersimpan dengan metadata | Presensi terekam otomatis lengkap dengan bukti lokasi/perangkat |
| PC-03 | Pengajuan dan persetujuan cuti berjenjang | NEED-03 / EV-03 | Pegawai mengajukan cuti → Manager menyetujui → HR memfinalkan → status terlihat pegawai | Status pengajuan dapat diketahui kapan saja oleh pihak terkait |
| PC-04 | Pengajuan lembur | NEED-03 / EV-03 | Pegawai mengajukan lembur → Manager menyetujui → HR memfinalkan | Pengajuan lembur terekam dan berstatus jelas |
| PC-05 | Pengelolaan jadwal kerja dan hari libur | NEED-03 / EV-02 | Admin HR menetapkan shift dan jadwal → sistem menampilkan jadwal per pegawai | Jadwal kerja dapat ditetapkan dan dilihat secara terpusat |
| PC-06 | Dashboard dan pelaporan HR | NEED-04 / EV-04 | Admin HR membuka dashboard → sistem menampilkan ringkasan kehadiran/cuti → laporan dapat diunduh | Rekap periodik diperoleh tanpa penyusunan manual |

## C. Tujuan Proyek

Berdasarkan permasalahan pada latar belakang dan kebutuhan mitra terhadap sistem kepegawaian yang terintegrasi, disusun tujuan proyek sebagai acuan analisis, perancangan, hingga pengembangan sistem PILAR HR dengan metode Agile Scrum. Adapun tujuan proyek ini adalah:

1. Membangun sistem Human Resource Management berbasis web yang terintegrasi.
2. Mempermudah pengelolaan data pegawai dalam satu sistem terpusat.
3. Memfasilitasi proses presensi dan pengajuan cuti secara digital.
4. Menyediakan pengelolaan jadwal kerja, hari libur, dan data master pendukung secara terpusat.
5. Menyediakan laporan sumber daya manusia yang akurat dan mudah diakses.
6. Meningkatkan efisiensi proses administrasi sumber daya manusia.
7. Menerapkan metode Agile Scrum dalam implementasi sistem agar mampu beradaptasi terhadap perubahan kebutuhan pengguna.

## D. Ruang Lingkup dan Batasan Proyek

### D.1 Ruang Lingkup Proyek

Ruang lingkup disusun berdasarkan kebutuhan mitra PT Pilar Inovasi Informatika. Implementasi penuh dilakukan pada Capstone Project 2. Adapun ruang lingkup sistem meliputi:

1. Proses Human Resource Management pada organisasi mitra.
2. Autentikasi pengguna dan pengelolaan peran (*role management*) dengan *role-based access control*.
3. Master data pegawai (data umum, data kepegawaian, dan riwayat kepegawaian).
4. Data master pendukung: jabatan, golongan, pangkat, status pegawai, departemen, dan unit kerja.
5. Presensi pegawai melalui portal, termasuk verifikasi lokasi dan perangkat.
6. Pengajuan dan persetujuan cuti beserta pengelolaan jenis cuti dan saldo cuti.
7. Pengajuan dan persetujuan lembur.
8. Pengelolaan jadwal shift, hari libur, dan lokasi absensi.
9. Dashboard untuk memantau kehadiran, cuti, dan data kepegawaian.
10. Penyimpanan dokumen pendukung, misalnya lampiran pengajuan cuti dan dokumen persetujuan absensi.

### D.2 Batasan Proyek

Batasan proyek ditetapkan agar pengerjaan tetap fokus:

1. Sistem dibangun berbasis web dan diakses melalui browser.
2. Capstone 1 mencakup analisis kebutuhan dan perancangan; coding penuh dikerjakan pada Capstone 2.
3. **Sistem tidak mencakup modul Penggajian (Payroll) dan modul Reimbursement.**
4. Sistem tidak mencakup modul rekrutmen, asesmen psikologi, atau pelatihan.
5. Sistem tidak mencakup integrasi dengan aplikasi pihak ketiga di luar ruang lingkup mitra (misalnya mesin *fingerprint* eksternal atau ERP luar).
6. Pengujian dilakukan pada lingkungan *development/staging*; penerapan produksi mengikuti kebijakan mitra.

**Asumsi/keterbatasan yang diketahui:** perangkat mitra memiliki spesifikasi terbatas sehingga antarmuka dirancang ringan; ketersediaan koneksi internet menjadi prasyarat penggunaan sistem.

## E. Alternatif Solusi dan Alasan Pemilihan

**Tabel 1.4. Perbandingan alternatif solusi**

| Alternatif | Kelebihan | Keterbatasan/risiko | Kesesuaian terhadap masalah |
|---|---|---|---|
| Web responsif (Laravel) | Diakses dari berbagai perangkat tanpa instalasi; fitur autentikasi, keamanan, ORM, dan struktur MVC sudah tersedia | Membutuhkan koneksi internet dan server; butuh spesifikasi server memadai | Tinggi |
| Aplikasi Desktop | Berjalan tanpa koneksi internet; pemakaian lokal cepat | Harus dipasang di tiap perangkat; sulit diakses dari banyak lokasi; pemeliharaan lebih rumit | Rendah |
| Aplikasi Android native | Memanfaatkan kamera/notifikasi perangkat secara langsung | Perlu instalasi; tidak dapat dipakai pada komputer petugas; waktu pengembangan lebih lama | Sedang |
| Framework native PHP | Ringan dan strukturnya sederhana | Banyak fungsi keamanan dan ORM dibuat manual sehingga pengembangan lebih lama | Sedang |

**Solusi yang dipilih.** Proyek memilih **aplikasi web berbasis framework Laravel**. Laravel dipilih karena menyediakan autentikasi, keamanan (CSRF, *hashing*, validasi), Eloquent ORM, dan struktur MVC yang mempercepat pengembangan sekaligus mempermudah pemeliharaan. Platform web dipilih agar Admin HR, Manager, dan Pegawai dapat mengakses sistem melalui browser tanpa instalasi. Konsekuensi yang diterima adalah kebutuhan server dan koneksi jaringan.

> **Complex-1 (kebutuhan/batasan saling bertentangan):** mitra menginginkan pengamanan presensi yang ketat (lokasi + perangkat + biometrik), namun perangkat pegawai dan spesifikasi komputer mitra terbatas. Tim memilih menerapkan pengamanan berlapis **di sisi server** dengan *fallback* pada sisi klien (biometrik opsional bila WebAuthn tidak didukung), sehingga keamanan tetap terjaga tanpa membebani perangkat. Konsekuensinya, sebagian verifikasi bergantung pada dukungan perangkat pengguna.

## F. Pertimbangan Profesional, Etika, dan Dampak

**Tabel 1.5. Pertimbangan profesional, etika, dan dampak**

| Aspek | Risiko/dampak | Pihak terdampak | Mitigasi/rencana tindakan |
|---|---|---|---|
| Privasi/Data | Data lokasi (GPS), data perangkat, dan data pribadi pegawai berpotensi disalahgunakan | Pegawai | Data lokasi hanya dipakai untuk validasi presensi; akses data dibatasi berdasarkan peran; metadata presensi disimpan untuk keperluan audit |
| Keamanan | Kebocoran kredensial atau akses tanpa hak ke fungsi admin | Pegawai, Mitra | Password di-*hash* (Bcrypt/Argon2), RBAC, CSRF, validasi input sisi server, dan *device binding* |
| Etika Profesional | Hasil uji berisiko dilaporkan tidak lengkap bila hanya hasil berhasil ditampilkan | Mitra, Dosen | Seluruh hasil (termasuk gagal/terkendala) dicatat apa adanya pada C-5 |
| Lisensi/HKI | Penggunaan pustaka/ikon tanpa memperhatikan lisensi | Pemilik karya, Tim | Sumber dan lisensi setiap pustaka dicatat pada Lampiran F |
| Aksesibilitas | Antarmuka sulit digunakan pada perangkat mitra berspesifikasi rendah | Pegawai, Admin HR | Antarmuka ringan, responsif, dan tanpa *build system* berat |

## G. Metode Pengembangan

Metode yang digunakan adalah **Agile Scrum**. Metode ini dipilih karena kebutuhan sistem dapat berubah selama pengerjaan, sehingga diperlukan pengembangan iteratif melalui beberapa sprint yang dievaluasi di setiap akhir sprint. Tahapan yang diterapkan meliputi **Product Backlog → Sprint Planning → Daily Scrum → Sprint Review → Sprint Retrospective**, dan berulang pada sprint berikutnya bila masih ada item backlog.

Pada Capstone 1, ruang lingkup terbatas pada analisis kebutuhan dan perancangan, sehingga pekerjaan berhenti pada **Sprint 1** (identifikasi *stakeholder*, analisis proses bisnis, Product Backlog, kebutuhan fungsional/nonfungsional, use case) dan **Sprint 2** (arsitektur, ERD, antarmuka, activity/sequence diagram, keamanan, rencana implementasi). Coding modul, integrasi, dan pengujian penuh dilanjutkan pada Capstone 2 melalui sprint berikutnya.

> **Gambar 1.3. Metode Pengembangan Agile Scrum**
>
> *[Diagram dikosongkan — sedang direvisi.]*

**Tabel 1.6. Ringkasan tahapan sprint**

| Sprint | Tahap | Fokus |
|---|---|---|
| Sprint 1 | Capstone 1 | Analisis kebutuhan, Product Backlog, use case, arsitektur awal |
| Sprint 2 | Capstone 1 | ERD, UI, activity/sequence diagram, keamanan, rencana implementasi |
| Sprint 3 | Capstone 2 | Implementasi autentikasi & manajemen pengguna, portal awal |
| Sprint 4 | Capstone 2 | Implementasi master data |
| Sprint 5 | Capstone 2 | Implementasi presensi, cuti, lembur, approval |
| Sprint 6 | Capstone 2 | Dashboard, pelaporan, integrasi |
| Sprint 7 | Capstone 2 | Pengujian fungsional, nonfungsional, keamanan, dan perbaikan |

## H. Jadwal Pelaksanaan

> **Gambar 1.4. Bagan Gantt pelaksanaan proyek**
>
> *[Diagram dikosongkan — sedang direvisi.]*

## I. Persetujuan C-1

**Tabel 1.7. Lembar persetujuan tahap C-1**

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
---

# C-2: SPESIFIKASI PRODUK

## A. Deskripsi Sistem

PILAR HR adalah sistem *Human Resource Management* berbasis web yang membantu pengelolaan data dan aktivitas kepegawaian secara digital. Sistem menyediakan fitur pengelolaan data pegawai dan data master pendukung, presensi, pengajuan/ persetujuan cuti, pengajuan/persetujuan lembur, pengelolaan jadwal kerja dan hari libur, serta dashboard dan pelaporan.

**Aktor/pengguna utama:**

1. **Admin HR** — akses penuh ke seluruh fitur (master data, presensi, cuti, lembur, jadwal, dashboard).
2. **Manager / Atasan** — akses ke data tim (persetujuan cuti/lembur, pemantauan kehadiran bawahan).
3. **Pegawai** — akses layanan mandiri (presensi, pengajuan cuti/lembur, riwayat, profil).

**Fungsi inti:** manajemen pengguna & peran; master data pegawai; presensi aman; cuti; lembur; jadwal kerja; dashboard & pelaporan.

**Batas sistem:** sistem tidak mencakup modul Penggajian (Payroll) dan Reimbursement, rekrutmen, pelatihan, maupun integrasi perangkat keras eksternal. Semua aktor mengakses sistem melalui browser dengan koneksi HTTPS.

> **Gambar 2.1. Halaman Login** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 2.2. Halaman Dashboard PILAR HR** — *[dikosongkan — sedang direvisi]*

## B. Kebutuhan Fungsional dan Prioritas

**Tabel 2.1. Kebutuhan fungsional**

| Kode FR | Kebutuhan/fungsi | Sumber kebutuhan | Prioritas | Kriteria penerimaan |
|---|---|---|---|---|
| FR-01 | Pengguna dapat login dan logout sesuai peran; sistem mengarahkan pengguna ke halaman sesuai perannya | NEED-01 / EV-01 | Wajib | Login berhasil mengarahkan Admin ke dashboard dan Pegawai/Manager ke portal |
| FR-02 | Admin HR dapat mengelola master data pegawai (tambah, ubah, hapus, lihat detail) | NEED-01 / EV-01 | Wajib | Data pegawai tersimpan dan tampil konsisten di seluruh modul |
| FR-03 | Admin HR dapat mengelola data master pendukung (jabatan, golongan, pangkat, status pegawai, departemen, unit kerja) | NEED-01 / EV-01 | Wajib | Data master dapat ditambah/diubah/dihapus dan terhubung ke data pegawai |
| FR-04 | Pegawai dapat melakukan presensi masuk dan pulang melalui portal dengan verifikasi lokasi dan perangkat | NEED-02 / EV-02 | Wajib | Presensi tersimpan lengkap dengan metadata (waktu, lokasi, perangkat, status biometrik) |
| FR-05 | Pegawai dapat mengajukan cuti; Manager dan HR dapat menyetujui/menolak berjenjang; status terlihat pegawai | NEED-03 / EV-03 | Wajib | Pengajuan tersimpan, status berubah sesuai alur persetujuan, saldo cuti berkurang saat disetujui |
| FR-06 | Pegawai dapat mengajukan lembur; Manager dan HR dapat menyetujui/menolak | NEED-03 / EV-03 | Wajib | Pengajuan lembur tersimpan dan berstatus jelas |
| FR-07 | Admin HR dapat mengelola jadwal shift, hari libur, jenis cuti, dan saldo cuti | NEED-03 / EV-02 | Wajib | Jadwal, hari libur, dan saldo cuti dapat ditetapkan serta dilihat per pegawai |
| FR-08 | Admin HR dapat mengelola titik lokasi absensi dan penugasannya | NEED-02 / EV-02 | Penting | Lokasi dengan radius tersimpan dan dipakai pada validasi presensi |
| FR-09 | Admin HR dapat melihat dashboard dan mengunduh/melihat rekap kehadiran serta cuti per periode | NEED-04 / EV-04 | Penting | Ringkasan menampilkan data sesuai periode yang dipilih |
| FR-10 | Pegawai dapat melihat riwayat kehadiran/pengajuan dan mengelola profil | NEED-01 / EV-01 | Penting | Riwayat tampil sesuai data pengguna dan profil dapat diperbarui |
| FR-11 | Pegawai harus menyetujui dokumen persetujuan absensi sebelum dapat melakukan presensi | NEED-02 / EV-02 | Wajib | Presensi ditolak bila persetujuan belum diberikan |
| FR-12 | Sistem dapat mengelola penyimpanan dokumen pendukung (misalnya lampiran cuti) | NEED-03 / EV-03 | Tambahan | Dokumen terunggah dan terhubung ke pengajuan terkait |

## C. Kebutuhan Nonfungsional dan Prioritas

**Tabel 2.2. Kebutuhan nonfungsional**

| Kode NFR | Kategori | Persyaratan | Target / kondisi uji | Prioritas | Sumber/bukti |
|---|---|---|---|---|---|
| NFR-01 | Kinerja | Halaman daftar/dashboard menampilkan data dengan waktu respons yang dapat diterima | Rata-rata ≤ 3 detik pada lingkungan uji dengan data uji perwakilan | Penting | EV-04 |
| NFR-02 | Keamanan | Fungsi administrasi hanya dapat digunakan pengguna berhak (RBAC) | Akses pengguna tanpa hak ke fungsi admin ditolak (HTTP 403) | Wajib | EV-01 |
| NFR-03 | Keamanan | Password disimpan dalam bentuk *hash*; data masukan diperiksa di sisi server | Password tidak pernah tersimpan *plain text*; permintaan tidak valid ditolak | Wajib | EV-01 |
| NFR-04 | Keandalan | Sistem dapat digunakan kembali setelah layanan aplikasi dimulai ulang | Fungsi utama dapat digunakan setelah restart terkontrol | Penting | EV-04 |
| NFR-05 | Kemudahan penggunaan | Tugas utama dapat diselesaikan pengguna tanpa bantuan | Minimal 80% tugas utama selesai tanpa bantuan | Penting | EV-03 |
| NFR-06 | Ketersediaan | Data dapat dipulihkan dari cadangan | Data uji pulih dari cadangan dan dapat dibaca aplikasi | Penting | EV-04 |
| NFR-07 | Kompatibilitas | Sistem dapat diakses melalui browser desktop dan perangkat bergerak | Antarmuka responsif pada browser modern (desktop & mobile) | Wajib | EV-05 |

## D. Spesifikasi Teknis

**Tabel 2.3. Spesifikasi teknis**

| Komponen | Teknologi/spesifikasi | Fungsi | Alasan/kendala awal |
|---|---|---|---|
| Sisi server / Backend | Laravel 11 / PHP 8.2–8.3 | Menjalankan logika aplikasi dan routing | Menyediakan autentikasi, validasi, ORM, dan CSRF bawaan; sesuai kompetensi tim |
| Basis data | MySQL/MariaDB | Menyimpan data pengguna, pegawai, presensi, cuti, jadwal | Dukungan penuh di lingkungan pengembangan dan produksi mitra |
| Antarmuka | HTML, CSS, JavaScript, Bootstrap 5, Blade | Menampilkan antarmuka web responsif | Ringan, tanpa *build system*, mudah dipelihara |
| Pemetaan | Leaflet.js | Menampilkan peta untuk penentuan lokasi absensi | *Open-source*, ringan, tanpa API key |
| Notifikasi UI | SweetAlert2 | Notifikasi/konfirmasi aksi | Meningkatkan pengalaman pengguna |
| Server pengembangan | XAMPP (Apache + MySQL) | Lingkungan pengembangan lokal | Paket terpadu, sesuai spesifikasi teknis awal |
| Server produksi | CyberPanel + OpenLiteSpeed (Ubuntu 24.04) | Lingkungan produksi | Sesuai kebijakan mitra; performa dan keamanan |
| Kendali versi | Git / GitHub | Pelacakan perubahan kode dan kolaborasi | Riwayat kontribusi dapat ditelusuri |
| Pengelola dependensi | Composer | Mengelola *library* PHP | Standar proyek Laravel |

### D.1 Kelayakan Beban Kerja dan Biaya

Pengembangan dilakukan oleh lima anggota tim selama dua semester (Capstone 1 dan 2). Infrastruktur uji memanfaatkan perangkat lokal (XAMPP) dan server milik mitra sehingga tidak memerlukan pengadaan perangkat baru. Biaya tambahan diperkirakan hanya untuk domain dan sertifikat SSL bila diperlukan. Aktivitas utama dibagi menjadi backend, frontend, basis data, pengujian, dan *deployment* sesuai Tabel 3.2.

## E. Matriks Keterlacakan Kebutuhan

**Tabel 2.4. Matriks keterlacakan kebutuhan**

| Kode kebutuhan/bukti | Kode FR/NFR | Fitur/komponen | Kode/lokasi rancangan | Kode uji |
|---|---|---|---|---|
| NEED-01 / EV-01 | FR-01 | Autentikasi & peran | DES-UC-01 / DES-SEQ-01 | FT-01, ST-01 |
| NEED-01 / EV-01 | FR-02, FR-03 | Master data | DES-DB-01 / DES-UI-01 | FT-02 |
| NEED-02 / EV-02 | FR-04 | Presensi aman | DES-AD-01 / DES-UI-02 | FT-03, ST-02, ST-03 |
| NEED-02 / EV-02 | FR-11 | Persetujuan absensi | DES-AD-01 / DES-UI-03 | FT-04 |
| NEED-03 / EV-03 | FR-05 | Cuti & approval | DES-AD-02 / DES-UI-04 | FT-05, IT-02 |
| NEED-03 / EV-03 | FR-06, FR-12 | Lembur & lampiran | DES-AD-03 | FT-06 |
| NEED-03 / EV-02 | FR-07, FR-08 | Jadwal kerja & lokasi | DES-DB-01 / DES-UI-05 | FT-07 |
| NEED-04 / EV-04 | FR-09, FR-10 | Dashboard & pelaporan | DES-UI-06 | FT-08, NFT-01 |
| EV-01 | NFR-02, NFR-03 | Kontrol akses & keamanan | DES-ARCH-01 | ST-01, ST-04 |
| EV-04 | NFR-04, NFR-06 | Keandalan & pemulihan | DES-ARCH-01 | NFT-02, NFT-03 |
| EV-03, EV-05 | NFR-05, NFR-07 | Kemudahan & kompatibilitas | DES-UI-01..06 | NFT-04 |

## F. Rencana Pengujian

**Tabel 2.5. Rencana pengujian**

| Kode/jenis uji | Kode FR/NFR | Metode | Kriteria lulus | Alasan metode | Jadwal/PJ |
|---|---|---|---|---|---|
| FT-01 / Fungsional | FR-01 | *Black-box* | Login sesuai peran mengarahkan ke halaman yang tepat | Memeriksa fungsi dari sisi pengguna | Sprint 7 / Tim |
| FT-02 / Fungsional | FR-02, FR-03 | *Black-box* | Data master tersimpan, terubah, terhapus, dan tampil | Memeriksa CRUD dari sisi pengguna | Sprint 7 / Tim |
| FT-03 / Fungsional | FR-04 | *Black-box* | Presensi valid tersimpan lengkap dengan metadata | Memeriksa fungsi presensi | Sprint 7 / Tim |
| FT-04 / Fungsional | FR-11 | *Black-box* (kondisi gagal) | Presensi ditolak bila persetujuan belum diberikan | Memeriksa penanganan prasyarat | Sprint 7 / Tim |
| FT-05 / Fungsional | FR-05 | *Black-box* | Alur cuti berjalan dan saldo berkurang saat disetujui | Memeriksa alur persetujuan | Sprint 7 / Tim |
| FT-06 / Fungsional | FR-06 | *Black-box* | Pengajuan lembur tersimpan dan berstatus jelas | Memeriksa alur persetujuan | Sprint 7 / Tim |
| FT-07 / Fungsional | FR-07, FR-08 | *Black-box* | Jadwal/lokasi tersimpan dan dipakai validasi | Memeriksa fungsi master pendukung | Sprint 7 / Tim |
| FT-08 / Fungsional | FR-09, FR-10 | *Black-box* | Dashboard/rekap tampil sesuai periode | Memeriksa fungsi pelaporan | Sprint 7 / Tim |
| IT-01 / Integrasi | FR-01..FR-11 | Uji integrasi | Data dari antarmuka diterima controller dan tersimpan ke basis data | Memeriksa hubungan antarkomponen | Sprint 7 / Tim |
| IT-02 / Integrasi | FR-05 | Uji integrasi | Cuti, saldo, dan hari libur terintegrasi benar | Memeriksa ketergantungan modul | Sprint 7 / Tim |
| NFT-01 / Kinerja | NFR-01 | *Load test* | Rata-rata waktu respons ≤ 3 detik | Memverifikasi target kinerja | Sprint 7 / Tim |
| NFT-02 / Keandalan | NFR-04 | *Recovery test* | Sistem dapat dipakai kembali setelah restart | Memverifikasi pemulihan layanan | Sprint 7 / Tim |
| NFT-03 / Ketersediaan | NFR-06 | *Backup-restore test* | Data pulih dan dapat dibaca aplikasi | Memverifikasi ketersediaan data | Sprint 7 / Tim |
| NFT-04 / Kemudahan | NFR-05, NFR-07 | *Task-based usability test* | Minimal 80% tugas selesai tanpa bantuan | Mengukur kemudahan dari sisi pengguna | Sprint 7 / Tim |
| ST-01 / Keamanan | NFR-02 | *Access-control test* | Pengguna tanpa hak ditolak (HTTP 403) | Memverifikasi RBAC | Sprint 7 / Tim |
| ST-02 / Keamanan | NFR-03 | Uji validasi sisi server | Permintaan tidak valid ditolak dan tidak tersimpan | Memverifikasi integritas input | Sprint 7 / Tim |
| ST-03 / Keamanan | NFR-02, NFR-03 | Uji *geofencing* & *device binding* | Presensi dari luar radius/perangkat salah ditolak | Memverifikasi kontrol presensi | Sprint 7 / Tim |
| ST-04 / Keamanan | NFR-03 | Uji *hashing* & CSRF | Password ter-*hash*; permintaan tanpa token CSRF ditolak | Memverifikasi perlindungan dasar | Sprint 7 / Tim |

## G. Verifikasi Kualitas Spesifikasi

**Tabel 2.6. Verifikasi kualitas spesifikasi**

| Kode FR/NFR | Dapat ditelusuri | Tidak ambigu | Terukur/dapat diperiksa | Catatan perbaikan |
|---|---|---|---|---|
| FR-01 | Ya – NEED-01/EV-01 | Ya | Ya | - |
| FR-04 | Ya – NEED-02/EV-02 | Ya | Ya | Metadata presensi perlu dirinci pada C-3 |
| FR-05 | Ya – NEED-03/EV-03 | Ya | Ya | Aturan saldo cuti & hari libur dijelaskan di C-3 |
| FR-09 | Ya – NEED-04/EV-04 | Ya | Ya | Periode rekap perlu didefinisikan sebagai kondisi uji |
| NFR-01 | Ya – EV-04 | Ya | Ya | Lingkungan uji (jumlah data & pengguna) dicantumkan pada kondisi uji |
| NFR-05 | Ya – EV-03 | Ya | Ya | Definisi "tugas utama" dirinci pada rencana usability |

## H. Persetujuan C-2

**Tabel 2.7. Lembar persetujuan tahap C-2**

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
---

# C-3: PERANCANGAN

## A. Identitas Proyek

Rancangan ini disusun berdasarkan **C-1 versi v1.0** (Proposal Proyek) dan **C-2 versi v1.0** (Spesifikasi Produk) untuk proyek **PILAR HR** oleh tim *Grow in The Dark*. Setiap rancangan mencantumkan kode kebutuhan (FR/NFR) yang dipenuhi untuk mendukung keterlacakan menuju C-4.

## B. Rancangan Arsitektur Sistem

Sistem dibangun dengan **Layered Architecture** menggunakan framework Laravel. Lapisan yang digunakan meliputi:

1. **Presentation Layer** — antarmuka web yang digunakan Admin HR, Manager/Atasan, dan Pegawai melalui browser (koneksi HTTPS).
2. **Application Layer** — modul-modul aplikasi (autentikasi, manajemen pengguna, manajemen pegawai, departemen, jabatan, presensi, cuti, lembur, jadwal kerja, dashboard).
3. **Business Layer** — controller, service, validasi, dan aturan bisnis (geofencing, device binding, perhitungan hak cuti).
4. **Data Layer** — Eloquent ORM untuk akses basis data.
5. **Database Layer** — MySQL/MariaDB sebagai penyimpanan data.
6. **Security Layer** — melindungi seluruh lapisan (hashing password, CSRF, session, RBAC, device binding, geofencing, audit trail).

> **Gambar 3.1. DES-ARCH-01 – Arsitektur Sistem PILAR HR**
>
> *[Diagram dikosongkan — sedang direvisi.]*

> **Complex-2 (ketergantungan antarkomponen):** modul presensi bergantung pada `DeviceBindingService` (perangkat), `GeolocationService` (lokasi, rumus Haversine), `AttendanceConsent` (persetujuan), `AttendanceLocation` (titik lokasi), dan `EmployeeSchedule` (jadwal). Kegagalan salah satu komponen menyebabkan presensi ditolak; perubahan struktur data pegawai berdampak pada modul presensi, cuti, dan pelaporan.

## C. Rancangan Basis Data

Struktur basis data digambarkan melalui ERD. Entitas utamanya adalah **Karyawan (Pegawai)** yang berhubungan dengan data master (Jabatan, Departemen, Unit Kerja, Status Pegawai, Golongan, Pangkat, Kelompok Shift, Lokasi Presensi) dan data transaksi (Presensi, Cuti, Lembur, Jadwal Kerja, Riwayat Kepegawaian, Saldo Cuti). Entitas pengguna (Users) dihubungkan ke Karyawan dan ke Perangkat Pengguna serta Persetujuan Absensi.

> **Gambar 3.2. DES-DB-01 – Entity Relationship Diagram (ERD) Sistem PILAR HR**
>
> *[Diagram dikosongkan — sedang direvisi.]*

**Tabel 3.1. Kamus data ringkas (tabel utama)**

| Tabel | Deskripsi |
|---|---|
| users | Data pengguna sistem (email, password hash, peran) |
| karyawans | Data pegawai master (data umum & kepegawaian) |
| employee_employment_histories | Riwayat kepegawaian pegawai |
| jabatans | Data jabatan |
| golongans | Data golongan |
| pangkats | Data pangkat |
| employee_statuses | Data status pegawai |
| departments | Data departemen |
| work_units | Data unit kerja |
| absensis | Riwayat presensi beserta metadata audit |
| attendance_locations | Titik lokasi untuk geofencing |
| attendance_consents | Persetujuan dokumen/aturan presensi |
| user_devices | Perangkat yang terikat pada akun (*device binding*) |
| cutis | Data pengajuan cuti |
| leave_types | Jenis cuti |
| employee_leave_balances | Saldo cuti pegawai |
| overtimes | Pengajuan lembur |
| holidays | Hari libur nasional |
| shift_groups | Grup shift kerja |
| work_shifts | Definisi shift kerja |
| employee_schedules | Jadwal kerja pegawai |
| provinces, regencies, districts, villages | Data wilayah Indonesia (referensi alamat) |

## D. Rancangan Antarmuka Pengguna

Rancangan antarmuka mencakup halaman login, dashboard Admin HR, portal pegawai (beranda, presensi, riwayat absensi, cuti, lembur, profil), serta halaman pengelolaan master data dan persetujuan. Setiap tampilan dirancang responsif agar dapat digunakan pada desktop maupun perangkat bergerak.

> **Gambar 3.3. DES-UI-01 – Rancangan Antarmuka Halaman Login**
>
> *[Diagram dikosongkan — sedang direvisi.]*
>
> **Gambar 3.4. DES-UI-02 – Rancangan Antarmuka Portal Presensi**
>
> *[Diagram dikosongkan — sedang direvisi.]*
>
> **Gambar 3.5. DES-UI-03 – Rancangan Antarmuka Dashboard Admin HR**
>
> *[Diagram dikosongkan — sedang direvisi.]*
>
> **Gambar 3.6. DES-UI-04 – Rancangan Antarmuka Pengajuan Cuti**
>
> *[Diagram dikosongkan — sedang direvisi.]*

## E. Diagram Alur Sistem

Artefak perancangan diberi kode `DES-[jenis]-xx`. Sistem ini dibangun dengan pendekatan **pemrograman berorientasi objek (OOP)**, sehingga diagram yang disertakan meliputi use case diagram, class diagram, sequence diagram, activity diagram, dan arsitektur sistem (Bagian B). Setiap artefak visual diberi caption gambar.

**Daftar artefak rancangan:**

- DES-ARCH-01 – Arsitektur Sistem (Bagian B)
- DES-DB-01 – ERD (Bagian C)
- DES-UC-01 – Use Case Diagram Admin HR
- DES-UC-02 – Use Case Diagram Manager/Atasan
- DES-UC-03 – Use Case Diagram Pegawai
- DES-CL-01 – Class Diagram
- DES-SEQ-01 – Sequence Diagram Login & Role Redirect
- DES-SEQ-02 – Sequence Diagram Presensi
- DES-AD-01 – Activity Diagram Presensi
- DES-AD-02 – Activity Diagram Pengajuan Cuti
- DES-AD-03 – Activity Diagram Pengajuan Lembur
- DES-UI-01..04 – Rancangan Antarmuka (Bagian D)

> **Gambar 3.7. DES-UC-01 – Use Case Diagram Admin HR** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.8. DES-UC-02 – Use Case Diagram Manager/Atasan** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.9. DES-UC-03 – Use Case Diagram Pegawai** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.10. DES-CL-01 – Class Diagram** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.11. DES-SEQ-01 – Sequence Diagram Login & Role Redirect** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.12. DES-SEQ-02 – Sequence Diagram Presensi** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.13. DES-AD-01 – Activity Diagram Presensi** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.14. DES-AD-02 – Activity Diagram Pengajuan Cuti** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 3.15. DES-AD-03 – Activity Diagram Pengajuan Lembur** — *[dikosongkan — sedang direvisi]*

## F. Keterlacakan Rancangan

**Tabel 3.2. Keterlacakan kebutuhan terhadap rancangan**

| Kode FR/NFR | Kode DES | Komponen/diagram | Kebutuhan yang dipenuhi | Rencana implementasi |
|---|---|---|---|---|
| FR-01 | DES-UC-01, DES-SEQ-01 | Use Case & Sequence Login | Autentikasi & role redirect | ACT-01 |
| FR-02, FR-03 | DES-DB-01, DES-UI-01 | ERD & UI Master | Pengelolaan master data | ACT-02 |
| FR-04, FR-11 | DES-AD-01, DES-SEQ-02 | Activity & Sequence Presensi | Presensi aman & persetujuan absensi | ACT-03 |
| FR-05 | DES-AD-02 | Activity Cuti | Pengajuan & persetujuan cuti | ACT-04 |
| FR-06 | DES-AD-03 | Activity Lembur | Pengajuan & persetujuan lembur | ACT-05 |
| FR-07, FR-08 | DES-DB-01 | ERD Jadwal & Lokasi | Jadwal kerja, hari libur, lokasi | ACT-06 |
| FR-09, FR-10 | DES-UI-03 | UI Dashboard | Dashboard & pelaporan | ACT-07 |
| NFR-02, NFR-03 | DES-ARCH-01 | Arsitektur & Security Layer | RBAC, hashing, validasi | ACT-08 |

## G. Rencana Implementasi dan Pembagian Tanggung Jawab

**Tabel 3.3. Rencana implementasi, jadwal, dan ketergantungan**

| Kode/aktivitas | Kode FR/DES | PJ awal | Mulai | Selesai | Prasyarat | Hasil/target |
|---|---|---|---|---|---|---|
| ACT-01 / Autentikasi & manajemen peran | FR-01 / DES-SEQ-01 | Tim | Sprint 3 | Sprint 3 | ERD selesai | Login & role redirect berjalan |
| ACT-02 / Master data pegawai & pendukung | FR-02, FR-03 / DES-DB-01 | Tim | Sprint 4 | Sprint 4 | ACT-01 | CRUD master data berjalan |
| ACT-03 / Presensi aman | FR-04, FR-11 / DES-AD-01 | Tim | Sprint 5 | Sprint 5 | ACT-02 | Presensi dengan geofencing, device binding, biometrik, consent |
| ACT-04 / Cuti & approval | FR-05 / DES-AD-02 | Tim | Sprint 5 | Sprint 5 | ACT-03 | Pengajuan & persetujuan cuti berjenjang |
| ACT-05 / Lembur | FR-06 / DES-AD-03 | Tim | Sprint 5 | Sprint 5 | ACT-03 | Pengajuan & persetujuan lembur |
| ACT-06 / Jadwal kerja & lokasi absensi | FR-07, FR-08 / DES-DB-01 | Tim | Sprint 4 | Sprint 5 | ACT-02 | Jadwal shift, hari libur, lokasi absensi |
| ACT-07 / Dashboard & pelaporan | FR-09, FR-10 / DES-UI-03 | Tim | Sprint 6 | Sprint 6 | ACT-03, ACT-04 | Dashboard & rekap periodik |
| ACT-08 / Kontrol keamanan & deployment | NFR-02, NFR-03 / DES-ARCH-01 | Tim | Sprint 5 | Sprint 7 | ACT-01 | RBAC, hashing, validasi, pemasangan sistem |

> **Gambar 3.16. Bagan Gantt rencana implementasi** — *[dikosongkan — sedang direvisi]*

## H. Rancangan Keamanan Sistem

**Tabel 3.4. Rancangan keamanan sistem**

| Aset/risiko | CIA | Kontrol yang dirancang | Kode kebutuhan/komponen | Rencana uji |
|---|---|---|---|---|
| Pengguna tanpa hak mengakses fungsi administrasi | C | *Role-based access control* (RBAC) | NFR-02 / Modul Admin | ST-01 |
| Data masukan tidak valid tersimpan | I | Validasi data pada sisi server | NFR-03 / Controller | ST-02 |
| Presensi dari lokasi/perangkat tidak sah | I | Geofencing (rumus Haversine) + device binding | NFR-02, NFR-03 / Presensi | ST-03 |
| Password terbaca | C | *Hashing* Bcrypt/Argon2; CSRF; session aman | NFR-03 / Auth | ST-04 |
| Kehilangan data | A | Pencadangan berkala dan prosedur *restore* | NFR-06 | NFT-03 |

## I. Validasi Permasalahan Komputasi Kompleks

**Tabel 3.5. Validasi permasalahan komputasi kompleks**

| Kode | Karakteristik | Ringkasan | Bukti dalam Proyek | Rujukan Laporan | Status |
|---|---|---|---|---|---|
| Complex-1 | Kebutuhan/batasan saling bertentangan | Mitra menginginkan pengamanan presensi ketat, namun perangkat pengguna dan komputer mitra berspesifikasi terbatas. Tim memilih pengamanan berlapis di sisi server dengan *fallback* biometrik di sisi klien, dengan konsekuensi sebagian verifikasi bergantung pada dukungan perangkat pengguna. | C-1 Bagian E; C-2 Tabel 2.2 (NFR-02, NFR-03); EV-02 | C-1 E; C-2 C | Memenuhi |
| Complex-2 | Ketergantungan antarkomponen | Modul presensi bergantung pada komponen perangkat, lokasi, persetujuan, dan jadwal. Kegagalan salah satu komponen menyebabkan presensi ditolak; perubahan struktur data pegawai berdampak pada modul presensi, cuti, dan pelaporan. | DES-ARCH-01, DES-DB-01, DES-AD-01 | C-3 B, C | Memenuhi |

**Catatan Verifikasi Dosen Pembimbing:** [Diisi hasil pemeriksaan Complex-1 dan Complex-2, perbaikan yang diperlukan, nama pemeriksa, dan tanggal verifikasi.]

## J. Persetujuan C-3

**Tabel 3.6. Lembar persetujuan tahap C-3**

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
---

# C-4: IMPLEMENTASI DAN PEMASANGAN SISTEM

## A. Pendahuluan

### A.1 Ringkasan Hasil C-1 sampai C-3

C-1 menetapkan bahwa PILAR HR bertujuan membangun sistem HRM berbasis web terintegrasi untuk mengatasi pengelolaan data pegawai yang masih manual/terpisah (duplikasi data, keterlambatan informasi, kesulitan pemantauan kehadiran). Metode pengembangan yang dipilih adalah **Agile Scrum**. C-2 merinci kebutuhan fungsional utama (autentikasi berbasis peran, master data pegawai, presensi aman, cuti, lembur, jadwal kerja, dashboard) dan kebutuhan nonfungsional (keamanan, keandalan, kemudahan penggunaan, kompatibilitas). C-3 menetapkan arsitektur berlapis, ERD, rancangan antarmuka, dan rencana implementasi bertahap.

### A.2 Tujuan Implementasi

Tahap C-4 merealisasikan rancangan C-3 menjadi sistem yang dapat dijalankan, meliputi penulisan kode, integrasi antarmodul, koneksi basis data, penerapan logika bisnis presensi/cuti/lembur, serta implementasi kontrol keamanan. Tujuan khususnya: (1) menghasilkan sistem PILAR HR yang dapat dijalankan pada lingkungan pengembangan dan produksi; (2) merealisasikan kebutuhan fungsional C-2 sesuai prioritas sprint; (3) menerapkan kontrol keamanan yang dirancang pada C-3; (4) menyediakan dokumentasi pemasangan dan pengoperasian yang dapat diikuti kembali.

### A.3 Ruang Lingkup Implementasi

1. Autentikasi dan manajemen peran (login, logout, role-based redirect).
2. Manajemen data master (pegawai, jabatan, golongan, pangkat, status pegawai, departemen, unit kerja).
3. Presensi (check-in/check-out dengan geofencing, device binding, biometrik, consent).
4. Manajemen cuti (jenis cuti, saldo cuti, pengajuan, approval berlapis).
5. Manajemen lembur (overtime).
6. Jadwal kerja (shift group, work shift, jadwal pegawai, hari libur).
7. Lokasi absensi (titik lokasi + radius untuk geofencing).
8. Portal pegawai berbasis antarmuka ramah perangkat bergerak.

> Modul **Penggajian (Payroll)** dan **Reimbursement** **tidak** termasuk ruang lingkup implementasi.

### A.4 Kondisi Awal Sebelum Pengembangan

Sebelum implementasi, tim telah menyelesaikan analisis kebutuhan dan perancangan (Sprint 1 dan Sprint 2) melalui C-1 s.d. C-3. Repositori Laravel 11 disiapkan sebagai fondasi aplikasi, dan basis data dibuat pada MySQL/MariaDB di lingkungan pengembangan.

## B. Implementasi Sistem

### B.1 Pembagian Tugas dan Tanggung Jawab yang Dilaksanakan

**Tabel 4.1. Ringkasan kontribusi anggota**

| Anggota | Tanggung jawab yang dilaksanakan | Modul/fitur | Kode bukti |
|---|---|---|---|
| Muhammad Satrioadi / 2311501028 | Implementasi backend Laravel (controller, model, migrasi), logika bisnis cuti/lembur/presensi, kontrol keamanan (geofencing, device binding, biometrik), integrasi peta & notifikasi, dokumentasi teknis | Autentikasi, Master Data, Presensi, Cuti, Lembur, Jadwal Kerja, Lokasi Absensi, Portal Pegawai | EV-10, EV-11, EV-12 |
| Annisa Ashadia Nurhaliza Savana / 2311501050 | [Lengkapi: kontribusi & modul yang dikerjakan] | [Modul/fitur] | [EV-..] |
| Rizki Fachriadi Iskandar / 2311501054 | [Lengkapi: kontribusi & modul yang dikerjakan] | [Modul/fitur] | [EV-..] |
| Nathasya Destia Fany Sitorus / 2311501058 | [Lengkapi: kontribusi & modul yang dikerjakan] | [Modul/fitur] | [EV-..] |
| Nessa Aulia Rahma / 2311501024 | [Lengkapi: kontribusi & modul yang dikerjakan] | [Modul/fitur] | [EV-..] |

> **Catatan verifikasi:** kolom anggota 2–5 wajib diisi dengan bukti yang dapat diperiksa (catatan kegiatan, tangkapan layar pengerjaan, dokumen desain, hasil pengujian, atau riwayat perubahan kode). Kode EV merujuk pada Lampiran G.

### B.2 Teknologi dan Alat Bantu yang Digunakan

**Tabel 4.2. Teknologi, alasan pemilihan, dan keterbatasan**

| Teknologi/alat bantu | Fungsi | Alasan dipilih | Keterbatasan/risiko | Alternatif |
|---|---|---|---|---|
| Laravel 11 / PHP 8.2–8.3 | Framework backend | Menyediakan autentikasi, validasi, Eloquent ORM, routing, dan CSRF bawaan; mempercepat pengembangan | Membutuhkan runtime PHP dan pengelolaan *dependency*; performa perlu diperhatikan pada beban tinggi | CodeIgniter / native PHP — fitur keamanan & ORM lebih terbatas |
| MySQL/MariaDB | Penyimpanan data | Gratis, banyak digunakan, dukungan penuh pada lingkungan pengembangan & produksi | Perlu pemeliharaan basis data; optimasi indeks pada skala besar | PostgreSQL — kebutuhan proyek tercakup MySQL |
| HTML, CSS, JavaScript, Bootstrap 5 | Antarmuka pengguna (Blade) | Ringan, tanpa *build system*, mudah dipelihara | Interaksi dinamis kompleks terbatas | Vue/React — menghindari kompleksitas *build* |
| Leaflet.js | Pemetaan lokasi absensi | *Open-source*, ringan, tanpa API key | Butuh koneksi internet untuk *tile* peta | Google Maps API — memerlukan API key berbayar |
| SweetAlert2 | Notifikasi/konfirmasi | UX notifikasi lebih baik | Tambahan *dependency* JavaScript | `alert()` bawaan |
| Git / GitHub | Kendali versi & kolaborasi | Melacak riwayat & kontribusi | Butuh disiplin commit | GitLab |
| XAMPP | Server pengembangan lokal | Paket Apache+MySQL+PHP terpadu | Hanya untuk pengembangan | Laragon/Docker |
| Composer | Pengelola *dependency* PHP | Standar proyek Laravel | Butuh koneksi saat instalasi | — |
| Bcrypt/Argon2 (Laravel Hash) | *Hashing* password | Bawaan Laravel, aman | — | MD5/SHA — tidak aman |

### B.3 Implementasi Berdasarkan Rancangan

Implementasi dilakukan bertahap sesuai rencana sprint pada C-3.

**B.3.1 Autentikasi dan Manajemen Peran.** Autentikasi menggunakan `LoginController` dengan `Auth::attempt()` dan verifikasi password melalui `Hash::check()`. Setelah login berhasil, sistem mengarahkan pengguna berdasarkan peran melalui `redirectAfterLogin()`: peran pegawai/manager diarahkan ke portal, sedangkan admin diarahkan ke dashboard utama. Semua rute selain login dilindungi middleware `auth`.

**B.3.2 Manajemen Data Master.** Modul master data mencakup pegawai, jabatan, golongan, pangkat, status pegawai, departemen, unit kerja, hari libur, shift group, work shift, jenis cuti, dan lokasi absensi. Setiap modul menerapkan pola CRUD dengan validasi pada proses simpan dan ubah. `KaryawanController` menangani dua kelompok data: data umum dan data kepegawaian (relasi ke jabatan, golongan, pangkat, status, departemen, unit kerja).

**B.3.3 Presensi dengan Keamanan Berlapis.** Presensi diimplementasikan pada `PresensiController` dengan alur: (1) *device binding* melalui `DeviceBindingService`; (2) *attendance consent* sebelum presensi; (3) *geofencing* dengan `GeolocationService::distanceMeters()` (rumus Haversine); (4) verifikasi biometrik (WebAuthn) dengan *fallback*; (5) penyimpanan *audit metadata* (latitude, longitude, accuracy, jarak, device fingerprint, status biometrik, user agent, timestamp).

**B.3.4 Manajemen Cuti.** `CutiController` menangani pengajuan cuti dengan perhitungan hari kerja yang mengecualikan akhir pekan dan hari libur nasional (tabel `holidays`). `LeaveBalanceService` mengelola saldo cuti per jenis per tahun. Alur persetujuan berjenjang: `menunggu_atasan` → `menunggu_hr` → `disetujui`/`ditolak`.

**B.3.5 Lembur (Overtime).** `OvertimeController` menangani pengajuan lembur dengan perhitungan jam otomatis dan persetujuan berjenjang (atasan → HR).

**B.3.6 Jadwal Kerja dan Hari Libur.** `EmployeeScheduleController` menyediakan grid jadwal shift per bulan dengan penugasan massal. `HolidayController` mendukung sinkronisasi hari libur; `ShiftGroupController` dan `WorkShiftController` mengelola definisi shift.

**B.3.7 Lokasi Absensi.** `AttendanceLocationController` mengelola titik lokasi kantor beserta radius (meter) yang dipakai validasi geofencing.

**B.3.8 Portal Pegawai.** Antarmuka portal dirancang ramah perangkat bergerak, mencakup beranda, presensi, riwayat absensi, cuti (pengajuan & persetujuan), lembur, profil, consent, dan absensi bawahan (untuk manajer).

> **Gambar 4.1. Implementasi halaman login** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 4.2. Implementasi formulir master data pegawai** — *[dikosongkan — sedang direvisi]*
>
> **Gambar 4.3. Implementasi halaman presensi dengan peta** — *[dikosongkan — sedang direvisi]*

**Tabel 4.3. Matriks kesesuaian rancangan dan implementasi**

| Kode DES | Kode FR/NFR | Hasil implementasi | PJ | Kode bukti | Status |
|---|---|---|---|---|---|
| DES-SEQ-01 | FR-01 | Login dengan email/password dan role-based redirect pada `LoginController` | Satrioadi | EV-10 | Sesuai |
| DES-DB-01 | FR-02, FR-03 | CRUD master data pegawai & master pendukung | Satrioadi | EV-11 | Sesuai |
| DES-AD-01 | FR-04, FR-11 | Presensi check-in/out dengan geofencing, device binding, biometrik, consent, dan audit metadata | Satrioadi | EV-12 | Sesuai |
| DES-AD-02 | FR-05 | Pengajuan cuti dengan perhitungan hari kerja, saldo cuti, dan approval berjenjang | Satrioadi | EV-13 | Sesuai |
| DES-AD-03 | FR-06 | Pengajuan lembur dengan approval berjenjang | Satrioadi | EV-14 | Sesuai |
| DES-DB-01 | FR-07, FR-08 | Jadwal kerja (shift) dan lokasi absensi | Satrioadi | EV-15 | Sesuai |
| DES-UI-03 | FR-09, FR-10 | Dashboard & rekap periodik | Satrioadi | EV-16 | Sebagian |
| DES-ARCH-01 | NFR-02, NFR-03 | RBAC, hashing, CSRF, validasi input | Satrioadi | EV-17 | Sesuai |
| DES-ARCH-01 | FR-12 | Penyimpanan dokumen/lampiran pendukung | — | — | Belum |

### B.4 Ringkasan Keputusan dan Perubahan Rancangan

**Tabel 4.4. Ringkasan perubahan implementasi**

| Kode CHG | Perubahan | Alasan | Dampak | PJ |
|---|---|---|---|---|
| CHG-01 | Verifikasi biometrik diterapkan sebagai lapisan tambahan dengan *fallback* (bukan satu-satunya syarat) | Tidak semua perangkat pengguna mendukung WebAuthn | FR-04 tidak berubah; presensi tetap berjalan; keamanan bertumpu pada geofencing & device binding | Satrioadi |
| CHG-02 | Perubahan label peran pada tampilan persetujuan menjadi "Manager" | Menyesuaikan istilah pada mitra | Perubahan UI tanpa dampak fungsional | Satrioadi |

> Jika tidak ada perubahan lain dari C-3, tambahkan catatan "Tidak ada perubahan lain dari C-3 versi v1.0". Catatan Keputusan dan Perubahan lengkap diletakkan pada Lampiran B.

### B.5 Integrasi Komponen

Integrasi dilakukan melalui struktur MVC dan relasi Eloquent:

1. **Antarmuka → Controller → Basis Data:** aksi pengguna mengirim permintaan HTTP ke rute yang dipetakan ke method Controller; Controller memvalidasi input, memanggil service, dan berinteraksi dengan model Eloquent untuk membaca/menulis ke basis data.
2. **Presensi dengan Modul Pendukung:** `PresensiController` mengintegrasikan `DeviceBindingService`, `GeolocationService`, `AttendanceConsent`, `EmployeeSchedule`, dan `AttendanceLocation`. Kegagalan salah satu pemeriksaan menyebabkan presensi ditolak dengan pesan yang sesuai.
3. **Cuti dengan Saldo & Hari Libur:** `CutiController` menggunakan `LeaveBalanceService` untuk memastikan saldo tersedia dan tabel `holidays` untuk mengecualikan hari libur dari perhitungan hak cuti.
4. **Portal dengan Peran:** `PortalController` memfilter data sesuai peran (manajer melihat absensi bawahan; pegawai hanya data pribadi).

> Keberhasilan integrasi diverifikasi melalui IT-01 dan IT-02 pada C-5.

### B.6 Implementasi Kontrol Keamanan

**Tabel 4.5. Kontrol keamanan yang diimplementasikan**

| Kontrol | Implementasi | Rujukan NFR/DES | Bukti |
|---|---|---|---|
| Autentikasi | Laravel Auth + session (`Auth::attempt()`) | NFR-02 / DES-ARCH-01 | `LoginController.php` |
| *Password hashing* | Bcrypt (cast `hashed` pada model `User`) | NFR-03 | `app/Models/User.php` |
| RBAC | Middleware `auth` + filter peran; `redirectAfterLogin` | NFR-02 | `routes/web.php` |
| CSRF Protection | Bawaan Laravel pada semua form POST/PUT/DELETE | NFR-03 | Blade `@csrf` |
| Validasi input | `$request->validate()` pada Controller | NFR-03 | Seluruh Controller |
| *Device binding* | `DeviceBindingService` + tabel `user_devices` | NFR-02, NFR-03 | `DeviceBindingService.php` |
| *Geofencing* | `GeolocationService` (Haversine) + verifikasi backend | NFR-02, NFR-03 | `GeolocationService.php` |
| Biometrik | WebAuthn di frontend dengan *fallback* | NFR-03 | `resources/views/portal/presensi.blade.php` |
| *Attendance consent* | `AttendanceConsent` + pemeriksaan sebelum presensi | NFR-02 | `PresensiController` |
| *Audit metadata* | Kolom lokasi, akurasi, jarak, fingerprint, status biometrik, user agent, IP pada tabel absensi | NFR-03 | migrasi `absensis` |
| Perlindungan kredensial | Kredensial DB pada `.env` (tidak di-*commit*) | NFR-03 | `.gitignore`, `.env` |

> Efektivitas kontrol diuji melalui ST-01..ST-04 pada C-5. Untuk lingkungan produksi, kredensial basis data wajib diperkuat (tidak menggunakan akun root tanpa password).

## C. Ketuntasan Implementasi

**Tabel 4.6. Status ketuntasan implementasi**

| Fitur/komponen | Kode FR/NFR | Status | PJ | Kode bukti |
|---|---|---|---|---|
| Autentikasi & role-based redirect | FR-01 | Selesai | Satrioadi | EV-10 |
| Master data pegawai & master pendukung | FR-02, FR-03 | Selesai | Satrioadi | EV-11 |
| Presensi dengan pengamanan berlapis | FR-04, FR-11 | Selesai | Satrioadi | EV-12 |
| Manajemen cuti | FR-05 | Selesai | Satrioadi | EV-13 |
| Manajemen lembur | FR-06 | Selesai | Satrioadi | EV-14 |
| Jadwal kerja & hari libur | FR-07 | Selesai | Satrioadi | EV-15 |
| Lokasi absensi | FR-08 | Selesai | Satrioadi | EV-15 |
| Dashboard & pelaporan | FR-09, FR-10 | Sebagian | Satrioadi | EV-16 |
| Penyimpanan dokumen pendukung | FR-12 | Belum | — | — |

> "Sebagian" pada dashboard berarti kartu ringkasan tersedia, namun grafik/filter lanjutan perlu dilengkapi. Status "Selesai" belum berarti seluruh pengujian lulus; hasil pengujian dicatat pada C-5.

## D. Dokumentasi Pemasangan dan Pengoperasian Sistem

### D.1 Kebutuhan Perangkat Keras dan Perangkat Lunak

**Spesifikasi server produksi:**
- CPU: *Common KVM processor* (2.0 GHz)
- RAM: 6 GB
- Penyimpanan: 40 GB SSD

**Perangkat lunak:**
- OS: Ubuntu 24.04.4 LTS
- Web server: OpenLiteSpeed (CyberPanel)
- Database: MariaDB 10.11
- PHP: 8.3.x
- Framework: Laravel 11
- Composer, Git

### D.2 Lingkungan Tujuan Pemasangan

1. **Pengembangan (lokal):** XAMPP (Apache + MySQL) pada Windows.
2. **Produksi (server):** Ubuntu 24.04 + CyberPanel/OpenLiteSpeed, domain dengan HTTPS, basis data MariaDB, *deployment* melalui Git Pull.

### D.3 Langkah Pemasangan

**Tabel 4.7. Dokumentasi pemasangan dan pengoperasian sistem**

| Langkah/tindakan | Cara/alat | Lingkungan tujuan | Konfigurasi teknis utama | Konfigurasi keamanan | Kode bukti |
|---|---|---|---|---|---|
| 1. Menyiapkan server | XAMPP / CyberPanel | Lokal & Server | PHP, MySQL/MariaDB, LiteSpeed/Apache | Firewall, SSL (HTTPS) | EV-20 |
| 2. *Deployment* kode | Git Pull / Clone | Server | Repositori proyek | SSH/HTTPS auth | EV-21 |
| 3. Instal dependensi | `composer install` | Lokal & Server | `--no-dev --optimize-autoloader` (produksi) | — | EV-22 |
| 4. Konfigurasi env | `.env` | Lokal & Server | `APP_KEY`, `APP_ENV`, koneksi DB | Kredensial rahasia tidak di-*commit* | EV-23 |
| 5. Migrasi basis data | `php artisan migrate` | Lokal & Server | `--force` pada produksi | — | EV-24 |
| 6. Optimasi cache | `php artisan optimize` | Server | cache config/route/view | — | EV-25 |
| 7. Pengaturan izin | `chown` & `chmod` | Server | Folder `storage`, `bootstrap/cache` | Izin 775/755 | EV-26 |
| 8. Uji akses | Browser | Server | Akses domain (HTTPS) | Login berbasis peran | EV-27 |

### D.4 Perintah Instalasi dan Konfigurasi Server (Produksi)

```bash
# 1. Masuk ke direktori public_html
cd /home/hr.yhotech.id/public_html

# 2. Ambil pembaruan kode terbaru
git pull origin main

# 3. Instal dependensi produksi
composer install --no-dev --optimize-autoloader

# 4. Jalankan migrasi database
php artisan migrate --force

# 5. Bersihkan dan perbarui cache
php artisan optimize:clear

# 6. Atur kepemilikan dan izin folder
chown -R hr.yhotech.id:hr.yhotech.id /home/hr.yhotech.id/public_html
chmod -R 775 storage bootstrap/cache
```

### D.5 Menjalankan, Menghentikan, dan Memulai Ulang Sistem

- **Menjalankan (lokal):** `php artisan serve` atau aktifkan Apache & MySQL pada XAMPP.
- **Menghentikan:** `Ctrl+C` pada `php artisan serve`, atau Stop Apache/MySQL pada XAMPP.
- **Memulai ulang:** jalankan ulang langkah di atas.

### D.6 Pengamanan yang Diterapkan

1. Kredensial basis data disimpan di `.env` (tidak di-*commit*).
2. Password pengguna di-*hash* (Bcrypt/Argon2).
3. CSRF protection aktif pada semua form.
4. Validasi input pada setiap Controller.
5. RBAC melalui middleware `auth` dan filter peran.
6. *Device binding* (satu akun satu perangkat).
7. *Geofencing* backend pada presensi.
8. HTTPS/SSL pada domain produksi.
9. Pengaturan izin berkas yang ketat pada server.

### D.7 Pemeriksaan Keberhasilan Pemasangan

1. Halaman login tampil saat URL diakses.
2. Login admin berhasil dan diarahkan ke dashboard.
3. Login pegawai/manager berhasil dan diarahkan ke portal.
4. Data master dapat ditambah/diubah/dihapus.
5. Presensi berhasil dari dalam radius dan ditolak dari luar radius.
6. Pengajuan cuti melalui alur persetujuan berjalan.

> **Gambar 4.4. Bukti sistem berjalan setelah pemasangan** — *[dikosongkan — sedang direvisi]*

## E. Persetujuan C-4

**Tabel 4.8. Lembar persetujuan tahap C-4**

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
---

# C-5: PENGUJIAN DAN EVALUASI

## A. Strategi dan Metode Pengujian

Pengujian dilakukan untuk membuktikan sistem berjalan sesuai kebutuhan C-1/C-2 dan rancangan C-3 secara objektif dan terukur. Ruang lingkup pengujian meliputi pengujian **fungsional**, **integrasi**, **nonfungsional** (kinerja, keandalan, ketersediaan, kemudahan penggunaan), dan **keamanan**, serta validasi bersama mitra.

**Metode:** *black-box testing* (fungsional), uji integrasi antarkomponen, *load/recovery/backup-restore test* (nonfungsional), dan *access-control/validation/geofencing test* (keamanan). Setiap kasus uji mencatat versi produk, tanggal, lingkungan, penguji, data/prasyarat, prosedur, dan kriteria lulus. Hasil yang belum tersedia ditandai **"Belum dilaksanakan"** dan tidak dianggap Lulus/Gagal.

## B. Pengujian Fungsional

**Tabel 5.1. Ringkasan hasil pengujian fungsional**

| Kode uji / kebutuhan | Skenario | Jumlah uji | Hasil yang diharapkan | Hasil uji | Lulus/Gagal (%) | PJ / bukti |
|---|---|---|---|---|---|---|
| FT-01 / FR-01 | Login dengan kredensial sesuai peran | [n] | Pengguna diarahkan sesuai perannya | [Belum dilaksanakan] | [—] | Tim / EV-30 |
| FT-02 / FR-02, FR-03 | Tambah/ubah/hapus data master | [n] | Data tersimpan dan tampil konsisten | [Belum dilaksanakan] | [—] | Tim / EV-31 |
| FT-03 / FR-04 | Presensi dari dalam radius lokasi | [n] | Presensi tersimpan lengkap dengan metadata | [Belum dilaksanakan] | [—] | Tim / EV-32 |
| FT-04 / FR-11 | Presensi tanpa persetujuan absensi | [n] | Presensi ditolak dengan pesan yang sesuai | [Belum dilaksanakan] | [—] | Tim / EV-33 |
| FT-05 / FR-05 | Pengajuan cuti hingga disetujui | [n] | Status berubah sesuai alur; saldo berkurang | [Belum dilaksanakan] | [—] | Tim / EV-34 |
| FT-06 / FR-06 | Pengajuan lembur hingga disetujui | [n] | Pengajuan tersimpan dan berstatus jelas | [Belum dilaksanakan] | [—] | Tim / EV-35 |
| FT-07 / FR-07, FR-08 | Penetapan jadwal shift & lokasi absensi | [n] | Jadwal/lokasi tersimpan dan dipakai validasi | [Belum dilaksanakan] | [—] | Tim / EV-36 |
| FT-08 / FR-09, FR-10 | Dashboard & rekap per periode | [n] | Rekap tampil sesuai periode terpilih | [Belum dilaksanakan] | [—] | Tim / EV-37 |
| IT-01 / FR-01..FR-11 | Integrasi antarmuka → controller → basis data | [n] | Data tersimpan dan terbaca antar modul | [Belum dilaksanakan] | [—] | Tim / EV-38 |
| IT-02 / FR-05 | Integrasi cuti dengan saldo & hari libur | [n] | Perhitungan hak cuti sesuai aturan | [Belum dilaksanakan] | [—] | Tim / EV-39 |

> **Cara menghitung persentase:** persentase lulus = jumlah pelaksanaan uji yang lulus ÷ jumlah pelaksanaan uji yang sudah memiliki hasil × 100%. Uji yang belum dilaksanakan dilaporkan terpisah.

## C. Pengujian Nonfungsional

**Tabel 5.2. Ringkasan hasil pengujian nonfungsional**

| Kode uji / NFR | Aspek | Target/kondisi | Metode | Beban/iterasi | Hasil/bukti | Status |
|---|---|---|---|---|---|---|
| NFT-01 / NFR-01 | Kinerja | Rata-rata waktu respons ≤ 3 detik | *Load test* | [jumlah pengguna & data] | [Belum dilaksanakan] / EV-40 | [—] |
| NFT-02 / NFR-04 | Keandalan | Sistem dapat dipakai kembali setelah restart | *Recovery test* | [n] restart terkontrol | [Belum dilaksanakan] / EV-41 | [—] |
| NFT-03 / NFR-06 | Ketersediaan | Data dapat dipulihkan dari cadangan | *Backup-restore test* | [n] siklus | [Belum dilaksanakan] / EV-42 | [—] |
| NFT-04 / NFR-05, NFR-07 | Kemudahan & kompatibilitas | ≥ 80% tugas utama selesai tanpa bantuan pada desktop & mobile | *Task-based usability test* | [n] pengguna × [n] tugas | [Belum dilaksanakan] / EV-43 | [—] |

## D. Pengujian Keamanan

**Tabel 5.3. Ringkasan pengujian keamanan**

| Kode uji / NFR | Risiko/kontrol | CIA | Skenario | Hasil yang diharapkan | Hasil/bukti | Status |
|---|---|---|---|---|---|---|
| ST-01 / NFR-02 | Akses tanpa hak / RBAC | C | Pengguna berperan pegawai membuka URL fungsi admin | Akses ditolak (HTTP 403) | [Belum dilaksanakan] / EV-44 | [—] |
| ST-02 / NFR-03 | Input tidak valid / validasi sisi server | I | Mengirim nilai tidak valid melalui permintaan langsung | Permintaan ditolak dan tidak tersimpan | [Belum dilaksanakan] / EV-45 | [—] |
| ST-03 / NFR-02, NFR-03 | Presensi tidak sah / geofencing & device binding | I | Presensi dari luar radius / perangkat berbeda | Presensi ditolak | [Belum dilaksanakan] / EV-46 | [—] |
| ST-04 / NFR-03 | Kredensial terbaca / hashing & CSRF | C | Memeriksa penyimpanan password & permintaan tanpa token CSRF | Password ter-*hash*; permintaan tanpa token ditolak | [Belum dilaksanakan] / EV-47 | [—] |

## E. Analisis, Temuan, Perbaikan, dan Pengujian Ulang

**Tabel 5.4. Temuan dan tindak lanjut**

| Kode/temuan uji | Penyebab | Dampak | Perbaikan/uji ulang | PJ/bukti |
|---|---|---|---|---|
| [BUG-01 / FT-xx] | [Belum terverifikasi — isi setelah pengujian] | [dampak] | [tindakan & uji ulang] | Tim / EV-48 |

> Jika belum ada temuan, tulis "Belum ada temuan" dan jelaskan bahwa pengujian belum dilaksanakan.

## F. Umpan Balik dan Validasi Mitra/Pengguna

**Tabel 5.5. Ringkasan validasi mitra/pengguna**

| Aspek | Umpan balik/hasil | Status | Tindak lanjut | Bukti |
|---|---|---|---|---|
| Presensi | [Isi setelah sesi validasi dengan mitra] | [—] | [—] | EV-49 |
| Cuti & approval | [Isi setelah sesi validasi dengan mitra] | [—] | [—] | EV-50 |
| Dashboard & pelaporan | [Isi setelah sesi validasi dengan mitra] | [—] | [—] | EV-51 |

## G. Rekomendasi Perbaikan

Berdasarkan rancangan dan keterbatasan yang diketahui, disarankan: melengkapi grafik/filter lanjutan pada dashboard; memperluas pengujian kinerja pada jumlah pengguna dan data yang lebih besar; menambah pencatatan aktivitas (*audit log*) pengguna; serta melengkapi penyimpanan dokumen pendukung (FR-12). Pengembangan berikutnya juga dapat mempertimbangkan notifikasi internal untuk perubahan status pengajuan.

## H. Evaluasi Ketercapaian Kebutuhan dan Masalah C-1

**Tabel 5.6. Evaluasi ketercapaian kebutuhan dan masalah C-1**

| Kode masalah/kebutuhan | Target/indikator | Hasil akhir | Status | Kode uji/bukti |
|---|---|---|---|---|
| PR-01 / NEED-01 | Data pegawai tercatat dalam satu sistem dan dapat ditelusuri | [Isi setelah pengujian] | [—] | FT-02 / EV-31 |
| PR-02 / NEED-02 | Presensi terekam otomatis dan aman | [Isi setelah pengujian] | [—] | FT-03, ST-03 / EV-32 |
| PR-03 / NEED-03 | Pengajuan cuti/lembur tercatat dan berstatus jelas | [Isi setelah pengujian] | [—] | FT-05, FT-06 / EV-34 |
| PR-04 / NEED-04 | Rekap kehadiran/cuti diperoleh tanpa penyusunan manual | [Isi setelah pengujian] | [—] | FT-08 / EV-37 |

> Bila data dampak belum tersedia, nyatakan "Dampak belum terverifikasi" dan jelaskan batas kesimpulan; jangan menyatakan efisiensi meningkat tanpa pengukuran.

## I. Kesimpulan

[Isi setelah seluruh pengujian selesai. Contoh pola: Berdasarkan hasil pengujian, kebutuhan NEED-01 hingga NEED-xx terpenuhi/terpenuhi sebagian; seluruh fungsi utama yang diimplementasikan dapat digunakan pada lingkungan pemasangan yang disepakati. Produk masih memiliki keterbatasan pada [aspek], sehingga hasil proyek tidak digunakan untuk menyimpulkan kemampuan di luar kondisi pengujian.]

## J. Persetujuan C-5

**Tabel 5.7. Lembar persetujuan tahap C-5**

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
---

# DAFTAR REFERENSI

> *Diisi otomatis dari aplikasi pengelola referensi, gaya APA edisi ke-7. Cantumkan hanya referensi yang benar-benar dirujuk.*

---
---

# LAMPIRAN

## Lampiran A. Matriks Pembagian Tugas dan Kontribusi Anggota

**Lampiran A.1. Matriks kontribusi anggota**

| Anggota | Tugas/modul | Periode | Hasil pekerjaan | Kode bukti |
|---|---|---|---|---|
| Muhammad Satrioadi / 2311501028 | Backend, keamanan presensi, integrasi | Sprint 3–7 | Backend & kontrol keamanan | EV-10, EV-12 |
| Annisa Ashadia Nurhaliza Savana / 2311501050 | [Lengkapi] | [Periode] | [Hasil] | [EV-..] |
| Rizki Fachriadi Iskandar / 2311501054 | [Lengkapi] | [Periode] | [Hasil] | [EV-..] |
| Nathasya Destia Fany Sitorus / 2311501058 | [Lengkapi] | [Periode] | [Hasil] | [EV-..] |
| Nessa Aulia Rahma / 2311501024 | [Lengkapi] | [Periode] | [Hasil] | [EV-..] |

## Lampiran B. Catatan Keputusan dan Perubahan

**Lampiran B.1. Catatan Keputusan dan Perubahan**

| Kode | Tanggal | Rancangan lama → baru | Alasan | Dampak | Persetujuan |
|---|---|---|---|---|---|
| CHG-01 | [tanggal] | Biometrik sebagai syarat → biometrik dengan *fallback* | Dukungan perangkat tidak seragam | Keamanan bertumpu pada geofencing & device binding | [pembimbing] |
| CHG-02 | [tanggal] | Label "Admin" → "Manager" pada tampilan approval | Menyesuaikan istilah mitra | Perubahan UI | [pembimbing] |

## Lampiran C. Rincian Kasus Uji dan Hasil Pengujian

**Lampiran C.1. Rincian kasus uji**

| Kode | Prasyarat | Langkah | Hasil yang diharapkan | Hasil aktual | Status |
|---|---|---|---|---|---|
| FT-01 | Akun admin & pegawai tersedia | Buka login, masukkan kredensial tiap peran | Diarahkan sesuai peran | [Belum dilaksanakan] | — |
| FT-03 | Presensi diaktifkan, dalam radius | Lakukan check-in | Data presensi tersimpan + metadata | [Belum dilaksanakan] | — |
| ST-03 | Akun pegawai, di luar radius | Lakukan check-in dari luar radius | Presensi ditolak | [Belum dilaksanakan] | — |

> Tambahkan baris sesuai jumlah kasus uji. Rincian lengkap tiap pelaksanaan dapat dirujuk ke catatan pengujian tim.

## Lampiran D. Rincian Pengujian Keamanan

**Lampiran D.1. Rincian pengujian keamanan**

| Kode | Kontrol diuji | Prosedur | Hasil yang diharapkan | Bukti |
|---|---|---|---|---|
| ST-01 | RBAC | Akses URL fungsi admin dengan peran pegawai | HTTP 403 | EV-44 |
| ST-02 | Validasi sisi server | Kirim nilai tidak valid via permintaan langsung | Permintaan ditolak | EV-45 |
| ST-03 | Geofencing & device binding | Presensi dari luar radius/perangkat lain | Presensi ditolak | EV-46 |
| ST-04 | Hashing & CSRF | Periksa penyimpanan password & token CSRF | Password ter-*hash*; tanpa token ditolak | EV-47 |

## Lampiran E. Formulir Validasi/Umpan Balik Mitra

> *Lampirkan formulir/notulen validasi bersama mitra (peran, tanggal, kegiatan yang dicoba, umpan balik, dan persetujuan).*

## Lampiran F. Pernyataan Etika, Penggunaan Kecerdasan Artifisial, dan Alat Bantu

**Lampiran F.1. Pernyataan etika dan penggunaan sumber daya**

| No. | Aspek | Keterangan |
|---|---|---|
| 1 | Data mitra | Data yang digunakan hanya untuk keperluan proyek dan sesuai izin mitra |
| 2 | Privasi pegawai | Data lokasi/perangkat hanya untuk validasi presensi dan audit |
| 3 | Penggunaan AI/alat bantu | [Jelaskan penggunaan AI/alat bantu dan batasannya] |
| 4 | Lisensi pustaka | Laravel (MIT), Bootstrap (MIT), Leaflet (BSD-2), SweetAlert2 (MIT) |

## Lampiran G. Indeks Bukti Proyek dan Kontribusi Individu

**Lampiran G.1. Indeks bukti proyek dan kontribusi individu**

| Kode bukti | Jenis bukti | Deskripsi | Lokasi/tanggal |
|---|---|---|---|
| EV-01..EV-05 | Notulen/observasi | Bukti pengumpulan kebutuhan | Lampiran/notulen, Feb–Mar 2026 |
| EV-10..EV-17 | Kode/screenshot | Bukti implementasi modul | Repositori proyek |
| EV-20..EV-27 | Screenshot/log | Bukti pemasangan & pengoperasian | Lingkungan produksi |
| EV-30..EV-47 | Hasil uji | Bukti pengujian fungsional/nonfungsional/keamanan | Lampiran C & D |
| EV-48..EV-51 | Validasi | Umpan balik mitra | Lampiran E |

---

> **Catatan akhir (hapus sebelum pengumpulan):** bagian yang masih bertanda `[...]` dan seluruh diagram wajib dilengkapi. Modul Penggajian (Payroll) dan Reimbursement tidak dicantumkan sesuai lingkup proyek.
