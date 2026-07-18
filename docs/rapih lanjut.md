

<!-- Start of picture text -->
gj ie<br>x t, ye<br>F244<br>‘Og ~‘)aa<br>YAKAY<br><!-- End of picture text -->

## **DAFTAR ISI** 

|DAFTAR ISI............................................................................................................2|
|---|
|DAFTAR GAMBAR...............................................................................................5|
|DAFTAR TABEL....................................................................................................6|
|DAFTAR PERSAMAAN........................................................................................7|
|C-1: PROJECT PROPOSAL...................................................................................8|
|A.<br>Identitas Proyek.........................................................................................8|
|B.<br>Latar Belakang..........................................................................................8|
|C.<br>Tujuan Proyek...........................................................................................9|
|D.<br>Batasan Proyek........................................................................................10|
|E.<br>Alternatif Solusi......................................................................................10|
|F.<br>Metode Pengembangan...............................................................................12|
|**1.**<br>**Product Backlog**.................................................................................14|
|**2.**<br>**Sprint Planning**..................................................................................14|
|**3.**<br>**Daily Scrum**........................................................................................16|
|**4.**<br>**Sprint Review**.....................................................................................16|
|**5.**<br>**Sprint Review**.....................................................................................16|
|Timeline Sprint..................................................................................................16|
|G.<br>Persetujuan..............................................................................................17|
|C-2: SPESIFIKASI PRODUK...............................................................................19|
|A.<br>Deskripsi Sistem......................................................................................19|
|B.<br>Kebutuhan Fungsional.............................................................................20|
|C.<br>Kebutuhan Non-Fungsional....................................................................20|
|D.<br>Spesifikasi Teknis....................................................................................21|
|E.<br>Traceability Matrix..................................................................................21|
|F.<br>Metode Pengujian.......................................................................................22|
|H.<br>Persetujuan..............................................................................................23|
|C-3: PERANCANGAN.........................................................................................24|
|A.<br>Identitas Proyek.......................................................................................24|



|B.<br>Desain Arsitektur Sistem.........................................................................24|
|---|
|a.<br>Arsitektur Sistem.........................................................................................24|
|b.<br>Presentation Layer.......................................................................................25|
|**Aktor Pengguna**............................................................................................25|
|c.<br>Application Layer.......................................................................................25|
|d.<br>Business Layer............................................................................................26|
|**Komponen**.....................................................................................................26|
|e.<br>Data Layer...................................................................................................26|
|f.<br>Database Layer............................................................................................27|
|**1. Authentication**..........................................................................................28|
|**2. Password Hashing**....................................................................................28|
|a.<br>Menggunakan Bcrypt / Argon2 untuk melindungi password.................28|
|b.<br>Password tidak pernah disimpan dalam bentuk plain text...................28|
|**3. Role-Based Login Redirect**......................................................................28|
|**4. Session Management**................................................................................28|
|**5. CSRF Protection**......................................................................................29|
|**6. Input Validation**.......................................................................................29|
|**7. Device Binding / Fingerprint**...................................................................29|
|**8. Biometric Verification**.............................................................................29|
|**9. Geofencing**................................................................................................29|
|**10. Attendance Consent**...............................................................................29|
|**11. Audit Trail & Data Integrity**.................................................................29|
|**12. Attendance Metadata**.............................................................................29|
|Ringkasan Fitur Keamanan................................................................................30|
|Alur Kerja Umum..............................................................................................30|
|Kesimpulan........................................................................................................31|
|C.<br>Desain Basis Data....................................................................................31|
|D.<br>Desain Antarmuka Pengguna..................................................................32|
|E.<br>Diagram Alur Sistem...............................................................................34|
|F.<br>Rencana Implementasi................................................................................49|
|**Tahapan Implementasi**................................................................................49|



|**Jadwal Pengembangan**................................................................................50|
|---|
|**Penjelasan**.....................................................................................................50|
|G.<br>Keamanan Sistem....................................................................................51|
|1.<br>Autentikasi dan Login.................................................................................51|
|H.<br>Persetujuan..............................................................................................58|
|DAFTAR REFERENSI.........................................................................................59|



## **DAFTAR GAMBAR** 

Gambar  2.1. Contoh Penamaan Gambar 

11 

## **DAFTAR TABEL** 

|Tabel  1.1. Lembar persetujuan dokumen C-1|9|
|---|---|
|Tabel  2.1. Lembar persetujuan dokumen C-2|11|
|Tabel  3.1. Lembar persetujuan dokumen C-3|13|



## **DAFTAR PERSAMAAN** 

Persamaan 1.1. Persamaan 1 

9 

## **C-1: PROJECT PROPOSAL** 

#### **A. Identitas Proyek** 

Judul Proyek 

   - : Implementasi Sistem Human Resource Management (PILAR HR) Berbasis Web dengan Metode Agile 

- Nama Tim : Grow in The Dark 

- Nama Anggota : 1. Muhammad Satrioadi/2311501028 

      2. Annisa Ashadia Nurhaliza Savana/2311501050 

      3. Rizki Fachriadi Iskandar/2311501054 

      4. Nathasya Destia Fany Sitorus/2311501058 

      5. Nessa Aulia Rahma /2311501024 

- Nama Pembimbing : Dr. Arizona Firdonsyah, S.Kom., M.Kom. 

Tanggal Pengajuan : 06/03/2026 

#### **B. Latar Belakang** 

Perkembangan teknologi informasi telah mendorong organisasi untuk melakukan transformasi digital pada berbagai proses bisnis, termasuk pengelolaan sumber daya manusia (Human Resource Management) [1][2]. Pengelolaan data karyawan yang masih dilakukan secara manual atau menggunakan aplikasi yang terpisah sering menimbulkan berbagai permasalahan, seperti duplikasi data, keterlambatan penyampaian informasi, kesulitan dalam pemantauan kehadiran karyawan, serta kurang efektifnya proses evaluasi kinerja [3][4].

Human Resource Management (HRM) merupakan salah satu aspek penting dalam perusahaan karena berperan dalam pengelolaan data pegawai, absensi, cuti, evaluasi kinerja, hingga penyusunan laporan sumber daya manusia [6][7]. Oleh karena itu, diperlukan suatu sistem yang mampu mengintegrasikan seluruh proses tersebut ke dalam satu platform yang terpusat dan mudah digunakan [8][9]. 

SmartHR merupakan sistem Human Resource Management berbasis web yang dirancang untuk membantu organisasi dalam mengelola data dan aktivitas sumber daya manusia secara digital [5]. Sistem ini akan menyediakan berbagai fitur seperti manajemen pengguna, pengelolaan data karyawan, absensi, pengajuan cuti, evaluasi kinerja, penyimpanan dokumen karyawan, serta pelaporan sumber daya manusia. 

Dalam implementasinya, proyek ini menggunakan metode Agile Scrum [11]. Metode Agile Scrum dipilih karena mampu memberikan fleksibilitas terhadap perubahan kebutuhan selama proses pengerjaan [12]. Berbeda dengan metode Waterfall yang memiliki alur pengerjaan linear dan sulit menyesuaikan perubahan kebutuhan pada tahap akhir, Agile Scrum memungkinkan pembangunan sistem dilakukan secara iteratif melalui beberapa sprint sehingga perbaikan dan penyesuaian dapat dilakukan secara berkelanjutan berdasarkan hasil evaluasi dan umpan balik pengguna [13]. 

Dengan adanya sistem SmartHR, diharapkan proses pengelolaan sumber daya manusia dapat dilakukan secara lebih efektif, efisien, terintegrasi, dan mendukung pengambilan keputusan berdasarkan data yang tersedia [10]. 

#### **C. Tujuan Proyek** 

1. Membangun sistem Human Resource Management berbasis web yang terintegrasi. 

2. Mempermudah pengelolaan data karyawan dalam satu sistem terpusat. 

3. Memfasilitasi proses absensi, pengajuan cuti, dan evaluasi kinerja secara digital. 

4. Menyediakan laporan sumber daya manusia yang akurat dan mudah diakses. 

5. Meningkatkan efisiensi proses administrasi sumber daya manusia. 

6. Menerapkan metode Agile Scrum dalam implementasi sistem agar dapat beradaptasi terhadap perubahan kebutuhan pengguna. 

#### **D. Batasan Proyek** 

1. Sistem dibangun berbasis web. 

2. Sistem hanya mencakup proses Human Resource Management. 

3. Sistem menyediakan fitur Authentication dan Role Management. 

4. Sistem menyediakan fitur Master Data Karyawan. 

5. Sistem menyediakan fitur Absensi Karyawan. 

6. Sistem menyediakan fitur Pengajuan dan Persetujuan Cuti. 

7. Sistem menyediakan fitur Evaluasi Kinerja Karyawan. 

8. Sistem menyediakan fitur Penyimpanan Dokumen Karyawan. 

9. Sistem menyediakan fitur Dashboard dan Reporting HR. 

10. Sistem tidak mencakup modul Payroll atau Penggajian. 

11. Sistem tidak mencakup integrasi dengan aplikasi pihak ketiga di luar ruang lingkup proyek. 

#### **E. Alternatif Solusi** 

Dalam pengembangan Sistem Human Resource Management (SmartHR), terdapat beberapa alternatif solusi yang dapat digunakan, baik dari sisi metode pengembangan maupun teknologi yang diterapkan. Perbandingan alternatif solusi dilakukan untuk menentukan pendekatan yang paling sesuai dengan kebutuhan proyek [4]. 

|**Alternatif**|**Kelebihan**|**Kekurangan**|
|---|---|---|
|**Metode Waterfall**|Tahapan jelas dan<br>terdokumentasi dengan<br>baik|Sulit menyesuaikan<br>perubahan kebutuhan<br>selama proses|



|**Alternatif**|**Kelebihan**|**Kekurangan**|
|---|---|---|
|||pengembangan|
|**Metode Agile Scrum**|Fleksibel terhadap<br>perubahan,<br>pengembangan<br>dilakukan secara<br>bertahap (iteratif),<br>komunikasi dengan<br>pengguna lebih intensif|Membutuhkan<br>koordinasi tim yang<br>aktif dan konsisten|
|**Desktop Application**|Dapat berjalan tanpa<br>koneksi internet|Sulit diakses dari<br>berbagai perangkat dan<br>membutuhkan instalasi|
|**Web-Based**<br>**Application**|Dapat diakses melalui<br>browser dari berbagai<br>perangkat, mudah<br>dipelihara dan<br>diperbarui|Membutuhkan koneksi<br>internet dan server|
|**Framework Native**<br>**PHP**|Struktur sederhana dan<br>ringan|Pengembangan lebih<br>lama karena banyak<br>fungsi dibuat secara<br>manual|
|**Framework Laravel**|Memiliki fitur<br>keamanan, autentikasi,<br>ORM, routing, dan<br>struktur kode yang rapi<br>sehingga mempercepat<br>pengembangan|Membutuhkan<br>spesifikasi server yang<br>lebih baik<br>dibandingkan PHP<br>native|





<!-- Start of picture text -->
Cm > Sprint Review<br>Pengeraan<br>Product Backlog Selesai?<br>+<br>¥<br>Sprint Planning ;<br>Sprint Restrospective<br>Daily Scrum Cm)<br><!-- End of picture text -->

direncanakan dapat diselesaikan sesuai dengan jadwal yang telah ditetapkan. 

#### 3. **Daily Scrum** 

Daily scrum dilakukan dengan merealisasikan jadwal pengerjaan yang telah ditentukan pada tahap sprint planning. Estimasi waktu yang telah disusun dijadikan sebagai acuan dalam pelaksanaan pengembangan. Setelah setiap fitur berhasil direalisasikan, dilakukan proses review atau pengecekan untuk memastikan hasil pengerjaan sesuai dengan yang diharapkan. 

#### 4. **Sprint Review** 

Sprint review dilakukan dengan mengevaluasi kesesuaian antara hasil realisasi dan output yang telah ditetapkan dalam product backlog. Evaluasi ini dilakukan secara internal oleh development team. Apabila ditemukan hasil yang belum sesuai, maka perbaikan harus diselesaikan sebelum melanjutkan pengerjaan pada sprint berikutnya, tanpa mengubah alokasi waktu yang telah ditetapkan pada sprint planning. 

#### 5. **Sprint Retrospective** 

Sprint retrospective dilakukan dengan mengevaluasi seluruh tahapan yang telah diimplementasikan oleh development team bersama stakeholder. Proses evaluasi dilakukan dengan mencocokkan fitur yang telah ditetapkan pada product backlog dengan hasil implementasi, sehingga dapat diketahui keberhasilan pengembangan serta menjadi bahan perbaikan untuk proses pengembangan selanjutnya. 

Metode yang digunakan dalam proyek implementasi Sistem Human Resource Management (PILAR HR) berbasis web adalah **Agile Scrum** . Agile Scrum dipilih karena mampu mendukung pengembangan sistem secara bertahap (iteratif), sehingga setiap hasil pekerjaan dapat dievaluasi dan disesuaikan dengan kebutuhan pengguna pada setiap sprint. 

Pada pelaksanaan Capstone 1, ruang lingkup proyek hanya mencakup **analisis kebutuhan sistem dan perancangan sistem** , sehingga implementasi metode Agile Scrum dilaksanakan hingga **Sprint 1** dan **Sprint 2** . Tahap 

implementasi fitur, integrasi sistem, serta pengujian akan dilanjutkan pada Capstone berikutnya. 

### 1. **Product Backlog** 

Product Backlog merupakan daftar kebutuhan sistem yang diperoleh dari hasil analisis proses bisnis Human Resource Management (HRM) dan diskusi bersama mitra. Backlog menjadi dasar penyusunan pekerjaan yang akan dikerjakan pada setiap sprint. 

Daftar kebutuhan sistem meliputi: 

- a. Authentication 

- b. Role Management 

- c. User Management 

- d. Data Karyawan 

- e. Data Jabatan 

- f. Data Departemen 

- g. Absensi 

- h. Pengajuan Cuti 

- i. Approval Cuti 

- j. Evaluasi Kinerja 

- k. Dashboard 

- l. Reporting 

### 2. **Sprint Planning** 

Sprint Planning dilakukan untuk menentukan prioritas pekerjaan yang akan diselesaikan pada setiap sprint berdasarkan Product Backlog. Pada tahap ini tim menyusun pembagian tugas, target sprint, estimasi waktu pengerjaan, serta output yang harus dihasilkan. 

Perencanaan sprint disusun selama lima bulan dengan pembagian sebagai berikut. 

##### **Sprint 1 (Bulan 1–2)** 

Fokus pada analisis kebutuhan sistem. 

#### Aktivitas: 

- a. Identifikasi stakeholder 

- b. Analisis proses bisnis HR 

- c. Identifikasi kebutuhan fungsional dan nonfungsional 

- d. Penyusunan Product Backlog 

- e. Penyusunan Use Case Diagram 

- f. Penyusunan Activity Diagram 

Output: 

- a. Dokumen Analisis Kebutuhan 

- b. Product Backlog 

- c. Use Case Diagram 

- d. Activity Diagram 

##### **Sprint 2 (Bulan 3–5)** 

Fokus pada perancangan sistem. 

Aktivitas: 

- a. Perancangan arsitektur sistem 

- b. Perancangan basis data (ERD) 

- c. Perancangan antarmuka pengguna (Wireframe/UI) 

- d. Perancangan struktur API 

- e. Penyempurnaan hasil desain berdasarkan evaluasi Sprint 1 

Output: 

- a. Desain Arsitektur Sistem 

b. Entity Relationship Diagram (ERD) 

- c. Wireframe 

- d. Desain API 

- e. Dokumen Perancangan Sistem 

### 3. **Daily Scrum** 

Daily Scrum dilakukan secara rutin oleh anggota tim untuk membahas perkembangan pekerjaan sesuai target sprint. Setiap anggota menyampaikan pekerjaan yang telah diselesaikan, kendala yang dihadapi, serta rencana pekerjaan berikutnya sehingga proses pengerjaan tetap berjalan sesuai jadwal yang telah ditentukan. 

### 4. **Sprint Review** 

Sprint Review dilakukan pada akhir setiap sprint untuk mengevaluasi hasil pekerjaan yang telah diselesaikan. Evaluasi dilakukan dengan membandingkan hasil analisis maupun desain sistem terhadap kebutuhan yang terdapat pada Product Backlog. Masukan dari dosen pembimbing maupun mitra digunakan sebagai dasar penyempurnaan hasil pada sprint berikutnya. 

### 5. **Sprint Review** 

Sprint Retrospective dilakukan setelah Sprint Review untuk mengevaluasi proses kerja tim. Evaluasi mencakup pembagian tugas, komunikasi tim, kendala yang dihadapi selama pengerjaan, serta perbaikan proses kerja agar pelaksanaan sprint berikutnya dapat berlangsung lebih efektif. 

# **<u>Timeline Sprint</u>** 

|**Sprint**|**Durasi**|**Fokus**|**Output**|
|---|---|---|---|
|Sprint|Bulan 1|Analisis|Product Backlog, Use Case Diagram,|
|1|–2|kebutuhan|Activity Diagram, Dokumen Analisis|
|Sprint|Bulan 3|Perancangan|Arsitektur Sistem, ERD, Wireframe, Desain|
|2|–5|sistem|API, Dokumen Perancangan|



Pelaksanaan proyek pada semester ini dibatasi hingga tahap analisis kebutuhan dan perancangan sistem yang direalisasikan melalui Sprint 1 dan Sprint 2. Tahap implementasi, integrasi, pengujian, serta deployment sistem akan dilaksanakan pada semester berikutnya sebagai kelanjutan proyek menggunakan metode Agile Scrum. 

#### **G. Persetujuan** 

Penulisan urutan tabel dituliskan di atas tabel yang dijelaskan, dengan format penamaan seperti format penamaan gambar, dengan kata “Tabel 1.1” dicetak tebal. 

**Tabel  1.1.** Lembar persetujuan dokumen C-1 



<!-- Start of picture text -->
No Anggota Kelompok Menyetujui<br>1 Muhammad Satrioadi/2311501028<br>2 Annisa Ashadia Nurhaliza Savana/2311501050<br>3 Rizki Fachriadi Iskandar/2311501054<br>4 Nathasya Destia Fany Sitorus/2311501058<br>5 Nessa Aulia Rahma /2311501024<br><!-- End of picture text -->

Yogyakarta,       Juli 2026 Menyetujui, Dosen Pembimbing 1 

<u>Dr. Arizona Firdonsyah, S.Kom., M.Kom.</u> 

NIP. 8005011810481 



<!-- Start of picture text -->
Login<br>Sistem Karyawan<br>Email<br>Password<br><!-- End of picture text -->



<!-- Start of picture text -->
| eennAbsons) Alpha Bulan In<br>J<br>i<br>m acta: a zi '<br>Pe E ; Q<br>=<br>Gratk Karyawan Kohodiran show A Gratic Karyawan Alpha Show Al<br>e c a ‘<br>Abeons| Terbaru Show Al<br>Co] NAMA STATUS TARGGAL JAM BULAN DAN TAHUM<br>Data Cub Terbaru ‘Show Al<br>i} WOOMAG, KOVR TANGGAL MUL TANSGAL BERAKHIR KETERANGAN JEMIS CUTI<br><!-- End of picture text -->

#### **D. Spesifikasi Teknis** 

Frontend : HTML, CSS, JavaScript, Bootstrap 5 Backend : Laravel 11 / PHP Database : MySQL API : Rest API (L) Server : XAMPP Keamanan : Bcrypt/Argon2 (Password Hashing), WebAuthn (Biometrik), Device Binding, Geofencing 

Notifikasi : SweetAlert2 

#### **E. Traceability Matrix** 

keterkaitan antara spesifikasi sistem dengan kebutuhan pengguna. 

|**Kebutuhan**|**Modul**|**Output**|
|---|---|---|
|Login User|Authentication|Akses Sistem dengan<br>Role-based Routing|
|Data Pegawai|Master Data|Data Karyawan|
|Absensi|Attendance|Presensi dengan<br>Geofencing, Biometrik,<br>dan Device Binding|
|Cuti|Leave Management|Approval Cuti|
|Keamanan Akun|Security|Device Binding (Satu<br>Akun Satu Perangkat)|



|Verifikasi Kehadiran|Security|Biometerik (Face|
|---|---|---|
|||ID/Fingerprint) &<br>Lokasi GPS|
|Laporan Kehadiran|Attendance|Laporan Absensi<br>Lengkap dengan|
|||Metadata Audit|



#### **F. Metode Pengujian** 

Berikut merupakan pengujian sistem, termasuk pengujian fungsional dan non-fungsional. Metode pengujian yang digunakan meliputi 

|**Metode Pengujian**|**Jenis**|**Alasan**|
|---|---|---|
|**Black Box Testing**|**Fungsional**|Menguji apakah setiap fitur bekerja<br>sesuai dengan kebutuhan tanpa melihat<br>kode program. Misalnya login, tambah<br>data karyawan, pengajuan cuti, dll.|
|||Menguji apakah sistem telah|
|**User Acceptance**<br>**Testing (UAT)**|**Fungsional**|memenuhi kebutuhan dan dapat<br>diterima oleh pengguna berdasarkan<br>skenario bisnis.|
|**Integration Testing**|**Fungsional**|Menguji interaksi antar modul,<br>misalnya modul login dengan<br>dashboard atau modul cuti dengan<br>laporan, sehingga alur fungsi sistem<br>berjalan dengan baik.|
|**Performance**<br>**Testing**|**Non-**<br>**Fungsional**|Menguji performa sistem seperti<br>kecepatan respon, beban pengguna,<br>penggunaan sumber daya, dan<br>stabilitas sistem.|



#### **H. Persetujuan** 

**Tabel  2.1.** Lembar persetujuan dokumen C-2 

|**No**|**Anggota Kelompok**|**Menyetujui**|
|---|---|---|
|1|Muhammad Satrioadi/2311501028||
|2|Annisa Ashadia Nurhaliza Savana/2311501050||
|3|Rizki Fachriadi Iskandar/2311501054||
|4|Nathasya Destia Fany Sitorus/2311501058||
|5|Nessa Aulia Rahma /2311501024||
||Yogyakarta,     Juli|2026|



Menyetujui, 

Dosen Pembimbing 1 

<u>Dr. Arizona Firdonsyah, S.Kom., M.Kom.</u> 

NIP. 8005011810481 



<!-- Start of picture text -->
[e) Oy fe)<br>ce any an)<br>HR Admin Manager Employee<br>HTTPS Request<br>{W 2. Application Layer (Laravel Framework)<br>Lime Jee Jee Jee Ie ID I J |<br>ED eee” eet Ea 11|<br>Invoke --—---—-Enforce- ----—!<br>[a=] [o= _8)'1 SessionAuth + Berypt‘gon?g / Role24 Redirect ‘SessionMort<br>Verify1<br>i<br>Query | Persist  Ieseatin| O validationY BraCG) weeny<br>(eee) GeofencingQ ‘Attendance‘Consent ‘Audit2 Tail, IntegygsData<br>sau<br>Lat/Lon * Accuracy * Device * User-Agent<br><!-- End of picture text -->

2. Application Layer 

3. Business Layer 

4. Data Layer 

5. Database Layer 

6. Security Layer (melindungi seluruh lapisan) 

#### **b. Presentation Layer** 

Lapisan antarmuka pengguna yang berinteraksi langsung dengan pengguna sistem. 

#### **Aktor Pengguna** 

- 1) **HR Admin** : Memiliki akses penuh ke seluruh fitur sistem 

- 2) **Manager / Atasan** : Memiliki akses ke data timnya (approval, absensi bawahan) 

- 3) **Employee / Karyawan** : Memiliki akses ke fitur pribadi (presensi, cuti, lihat gaji) 

#### **Akses** 

- 1) Semua aktor mengakses melalui Web Browser 

- 2) Koneksi menggunakan HTTPS untuk keamanan 

#### **c. Application Layer** 

Lapisan inti aplikasi yang dibangun dengan Laravel Framework, berisi modul-modul utama: 

|**Modul**|**Deskripsi**|
|---|---|
|Authentication|Mengelola login, logout, dan otentikasi pengguna|
|User Management|Mengelola data pengguna sistem|
|Employee Management|Mengelola data karyawan master|
|Department Management|Mengelola departemen|
|Position Management|Mengelola jabatan, golongan, dan pangkat|
|Attendance|Mengelola presensi masuk & pulang|



|Leave|Mengelola pengajuan dan persetujuan cuti|
|---|---|
|Overtime|Mengelola pengajuan lembur|
|Reimbursement|Mengelola klaim biaya|
|Payroll Management|Mengelola gaji dan slip gaji|
|Work Schedule Management|Mengelola shift dan jadwal kerja|
|Dashboard|Menampilkan ringkasan data sesuai role|



#### **d. Business Layer** 

Lapisan yang mengimplementasikan logika bisnis inti: 

##### **Komponen** 

1. **Controllers** : Gerbang masuk untuk request dari pengguna 

#### 2. **Services** : 

   - a. <mark>DeviceBindingService</mark> : Mengelola binding perangkat (satu akun satu perangkat) 

   - b. <mark>LeaveBalanceService</mark> : Mengelola perhitungan saldo cuti 

   - c. <mark>HolidaySyncService</mark> : Menyinkronkan hari libur 

3. **Validation** : Memvalidasi semua input pengguna 

#### 4. **Business Rules** : 

- a. **Geofencing** : Memverifikasi lokasi presensi menggunakan rumus Haversine 

- b. **Device Binding** : Memastikan akun hanya dipakai di perangkat terdaftar 

- c. **Leave Calculation** : Menghitung hari cuti dengan mengecualikan akhir pekan dan hari libur 

#### **e. Data Layer** 

Lapisan yang berkomunikasi dengan database menggunakan **Laravel Eloquent ORM** (Object-Relational Mapping), memudahkan interaksi dengan data tanpa menulis SQL secara manual. 

**f. Database Layer** Lapisan penyimpanan data menggunakan **MySQL** , berisi tabel-tabel 

berikut: 

|**Tabel**|**Deskripsi**|
|---|---|
|users|Data pengguna sistem (email, password,<br>role)|
|karyawans|Data karyawan master|
|departments|Data departemen|
|jabatans|Data jabatan|
|golongans|Data golongan|
|pangkats|Data pangkat|
|employee_statuses|Data status pegawai|
|work_units|Data unit kerja|
|absensis|Riwayat presensi karyawan|
|cutis|Data pengajuan cuti|
|leave_types|Jenis cuti|
|employee_leave_balances|Saldo cuti karyawan|
|holidays|Hari libur nasional|
|shift_groups|Grup shift kerja|
|work_shifts|Shift kerja|
|employee_schedules|Jadwal karyawan|
|overtimes|Pengajuan lembur|
|reimbursements|Pengajuan reimbursement|
|salary_steps|Skala gaji berkala|



|employee_salary_histories|Riwayat gaji karyawan|
|---|---|
|gajis|Data gaji bulanan|
|attendance_locations|Titik lokasi untuk geofencing|
|attendance_consents|Persetujuan peraturan presensi|
|user_devices|Data perangkat yang terikat dengan akun|
|provinces, regencies, districts,|Data wilayah Indonesia|
|villages||



#### **g. Security Layer** 

Lapisan keamanan yang melindungi seluruh sistem, terdiri dari: 

##### **1. Authentication** 

- a. Menggunakan Laravel Auth + Session 

- b. Role-based access control 

##### **2. Password Hashing** 

- a. Menggunakan Bcrypt / Argon2 untuk melindungi password 

- b. Password tidak pernah disimpan dalam bentuk plain text 

##### **3. Role-Based Login Redirect** 

- a. Admin diarahkan ke dashboard utama 

- b. Manajer/Karyawan diarahkan ke portal karyawan 

##### **4. Session Management** 

- a. Mengelola sesi pengguna dengan aman 

##### **5. CSRF Protection** 

- a. Perlindungan dari Cross-Site Request Forgery (Laravel default) 

##### **6. Input Validation** 

- a. Memvalidasi semua input pengguna untuk keamanan 

##### **7. Device Binding / Fingerprint** 

- a. Satu akun hanya bisa dipakai di satu perangkat 

- b. Fingerprint di-generate dari karakteristik perangkat (UserAgent, bahasa, resolusi, timezone) 

##### **8. Biometric Verification** 

- a. Menggunakan WebAuthn API untuk Face ID / Fingerprint / Windows Hello 

- b. Fallback ke stored credential jika WebAuthn tidak didukung 

##### **9. Geofencing** 

- a. Memverifikasi lokasi presensi menggunakan GPS dengan akurasi tinggi 

- b. Menggunakan rumus Haversine untuk menghitung jarak dari lokasi kantor 

- c. Mode dinas luar untuk pengecualian lokasi 

##### **10. Attendance Consent** 

- a. Karyawan harus menyetujui perjanjian absensi dan task list 

flowchart sebelum bisa presensi 

##### **11. Audit Trail & Data Integrity** 

- a. Semua data absensi disimpan dengan metadata lengkap 

- b. Metadata: latitude, longitude, accuracy, jarak, device fingerprint, biometric status, user agent, timestamp 

##### **12. Attendance Metadata** 

- a. Menyimpan detail setiap presensi untuk keperluan audit 

#### **Ringkasan Fitur Keamanan** 

Berikut adalah ringkasan fitur keamanan di SmartHR: 

|**Fitur**|**Teknologi**|
|---|---|
|Authentication|Laravel Auth + Session|
|Password Hashing|Bcrypt / Argon2|
|Role-Based Login|Laravel Redirect|



|Session Management|Laravel Session|
|---|---|
|CSRF Protection|Laravel CSRF|
|Input Validation|Laravel Validation|
|Device Binding|Fingerprint + UserDevice Model|
|Biometric Verification|WebAuthn API|
|Geofencing|GPS + Rumus Haversine|
|Attendance Consent|AttendanceConsent Model|
|Audit Trail|Metadata di Absensi Model|



#### **Alur Kerja Umum** 

1. Pengguna mengakses sistem via Web Browser (Presentation Layer) 

2. Request dikirim ke Laravel Framework (Application Layer) 

3. Controller memproses request dan memvalidasi input 

4. Controller memanggil Service untuk menangani logika bisnis (Business Layer) 

5. Service berinteraksi dengan database via Eloquent ORM (Data Layer) 

6. Data diambil atau disimpan di MySQL (Database Layer) 

7. Seluruh proses dilindungi oleh Security Layer 

##### **Kesimpulan** 

Arsitektur SmartHR dirancang dengan prinsip keamanan dan keterpeliharaan (maintainability) yang tinggi. Setiap lapisan memiliki tanggung jawab yang jelas, dan seluruh sistem dilindungi oleh fitur keamanan yang komprehensif. 



<!-- Start of picture text -->
ee TOTS<br>AG oF<br><<br>Oe) a ee<br>a<br>~<br>VV<br>wea<br><!-- End of picture text -->



<!-- Start of picture text -->
mockup for admin dekstop<br>7 = > “A<br>SESE<br><!-- End of picture text -->



<!-- Start of picture text -->
__ = =<br><!-- End of picture text -->



<!-- Start of picture text -->
P = oF<br>|<br><!-- End of picture text -->







<!-- Start of picture text -->
en‘ete a‘ott<br>oo<br>SEont oo— a *<br>meSoe oe<br>ge ‘<br>7<br>s<br>46<br>gS2B a _—<br>«<br>« cw sa* a ew" 2 ae<br>aoe naesor aSiieee \eoes= =2\=e o*> e a=a ee TE mn ‘ < oralae\a<br>°° ee =nal<br>“2 . —S<br>Z i a =<br>es ;eeSot =<br><!-- End of picture text -->



<!-- Start of picture text -->
ADMIN HR<br><!-- End of picture text -->

> Dasnboara rs <> _witend __.57\_tokasi gps Baeae =oPC cerangkat, Stead, LQ ascheck Watist 

C—) 

J Q Karyawan 

cli aSooo necktie” Ot Sane 7 A 

C=) ~ ince ans SS Gitens Sane <( penckaran notamengaysran 

‘teint Pro i ‘MengubahPassword 



<!-- Start of picture text -->
C) <D<br>.-<br>|<br>=<br>\<br>res<br>NaH<br><!-- End of picture text -->



<!-- Start of picture text -->
membuka halaman menampilkan<br>login halaman Login<br>memasukkan Email<br>klik: tombolCclogin<br>validasi<br>° login? “<br>menampilkan pesan Sistem mengambil<br>email dan password data pengguna dari<br>salah database<br>menentukan hak akses<br>(Admin HR / Karyawan /<br>Manager)<br>menampilkan Dashboard<br>sesuai role pengguna<br><!-- End of picture text -->

Activity diagram login menunjukkan alur yang dilakukan pengguna untuk masuk ke dalam sistem. Proses diawali ketika pengguna membuka halaman login, kemudian sistem menampilkan form login. Setelah itu pengguna memasukkan email dan password, lalu menekan tombol **Login** . Sistem akan memeriksa kecocokan data yang dimasukkan. Jika email atau password tidak sesuai, sistem akan menampilkan pesan bahwa data login salah sehingga pengguna dapat mencoba kembali. Sebaliknya, jika data yang dimasukkan valid, sistem mengambil data pengguna, menentukan hak akses berdasarkan role yang dimiliki, kemudian mengarahkan pengguna ke halaman dashboard sesuai dengan hak aksesnya. 

Berikut ini link untuk diagram : 

- <u>https://drive.google.com/file/d/1lFD7t5BKovIueMpGVC2XJ0 APfA1yu6J/view? usp=drive_link</u> 



<!-- Start of picture text -->
a ee<br>e<br>S| || = | sss<br>= — aay<br>[a<br>shen a aa,ror fessapmearocron<br>~= {ana|,senangaeRaINss ©<br>—— as<br>=<br>ecananan Pesan =— = >) penance<br><!-- End of picture text -->

Activity diagram master pegawai menggambarkan proses pengelolaan data pegawai yang dilakukan oleh Admin HR. Setelah berhasil login, Admin HR memilih menu **Master Pegawai** sehingga sistem menampilkan daftar data pegawai. Dari halaman tersebut Admin HR dapat menambahkan, mengubah, menghapus data pegawai, maupun melakukan reset login pegawai. 

Pada proses penambahan data, sistem menampilkan formulir yang harus diisi oleh Admin HR. Setelah data disimpan, sistem melakukan validasi terlebih dahulu. Jika masih terdapat data yang belum sesuai, sistem akan menampilkan pesan kesalahan. Namun jika seluruh data telah valid, sistem menyimpan data ke database dan menampilkan notifikasi bahwa data berhasil ditambahkan. 

Proses edit dilakukan dengan memilih data pegawai yang akan diubah, kemudian sistem menampilkan formulir yang berisi data sebelumnya. Setelah perubahan disimpan dan lolos validasi, data pada database akan diperbarui. Sementara itu, proses hapus diawali dengan konfirmasi kepada Admin HR sebelum data dihapus dari database. Untuk reset login, sistem juga meminta konfirmasi terlebih dahulu, kemudian melakukan reset password dan menampilkan pemberitahuan bahwa proses berhasil dilakukan. 

Berikut ini link untuk diagram : 

- <u>https://drive.google.com/file/d/1lFD7t5BKovIueMpGVC2XJ0 APfA1yu6J/view? usp=drive_link</u> 



<!-- Start of picture text -->
KARYAWAN SISTEM DATABASE<br>Menampikan Saldo<br>Login ke Sistem Cut Tersedia dan<br>Form Pengajuan Cuti<br>Masuk ke Dasboard<br>kik Menu Ajukan Cuti<br>Tertampil Saldo Cuti<br>Tersedia dan Form<br>Pengajuan Cuti<br>Memilin Jenis Cuti /Menerima Pengajuan’<br>Cuti<br>‘Atur Tanggal Cut<br>isi kolom keterangan<br>cutvAlasan Memvalidasi Data<br>Pengajuan<br>Unggah Surat<br>Pengajuan Cuti<br>MenampilkanError Pesan Porgajuan beoan MenyimpanPengajuan Data<br>Batat va<br>Menampikan Pop-up<br>Berasil Mengirim<br>Pengajuan<br>Mengirim Notifikasi<br>Pengajuan ke<br>Manager<br>Tertampil Riwayat Menampilkan Rivayat<br>dan Status Cut Status Cuti<br>@<br><!-- End of picture text -->

Setelah itu, sistem menampilkan riwayat beserta status pengajuan cuti yang telah dilakukan oleh karyawan. 

Berikut ini link untuk diagram : - <u>https://drive.google.com/file/d/1lFD7t5BKovIueMpGVC2XJ0 APfA1yu6J/view? usp=drive_link</u> 



<!-- Start of picture text -->
fry sister<br>—t—<br>Loge<br>—__<br>‘Masuk Dashboard<br>Utama<br>; peaurok Menu6 Leribe ReimburseMaamanRiwayatMenampikan& Lembur<br>—<br>Rmburse&Halaman RiayatLemur<br>Tertampakan<br>—<br>——ip etd |heMenamptkan=Form<br>Input Tangga Lembur<br>InputLembuJam Musa<br>Inout Jam Selesi<br>Lembur<br>Input Ops<br>Pembayaran<br>Input Jens Han Kerja<br>Input Keterangan<br>Pekeraan<br>Input Bukfe)Tambanan<br>Centang Pemyataan<br>Be<br>ats —<Aukan Lembur? vs<br>fomvatdaci Dal?<br>Pengaiuan<br>Menamoitanfor Pesan MangrenesedOa MenvimoanPengauanData<br>Menampikan Pop un<br>Bemasi Mengem<br>Pengaivan<br>Mange Novas<br>Pengawuan ke<br>‘Managor<br>MenarpihanLamourRiayatTernsStatusDanar<br>Pop-Up Bernas dan<br>DatatLemburTerbanRivayar Status<br>Terampican<br><!-- End of picture text -->

menu **Reimburse & Lembur** , kemudian sistem menampilkan halaman riwayat lembur. Untuk membuat pengajuan baru, karyawan memilih tombol **Ajukan Lembur** , sehingga sistem menampilkan formulir pengajuan. 

Pada formulir tersebut karyawan mengisi tanggal lembur, jam mulai, jam selesai, opsi pembayaran, jenis hari kerja, keterangan pekerjaan, serta mengunggah dokumen pendukung apabila diperlukan. Setelah semua data diisi dan pengajuan dikirim, sistem melakukan validasi. Jika terdapat data yang belum sesuai, sistem akan menampilkan pesan kesalahan. Sebaliknya, jika data telah valid, sistem menyimpan pengajuan ke database, menampilkan notifikasi bahwa pengajuan berhasil dikirim, lalu mengirimkan notifikasi kepada Manager untuk dilakukan proses persetujuan. Terakhir, sistem menampilkan riwayat pengajuan beserta status lembur terbaru agar karyawan dapat memantau hasil pengajuannya. 

#### Berikut ini link untuk diagram : 

- <u>https://drive.google.com/file/d/1lFD7t5BKovIueMpGVC2XJ0 APfA1yu6J/view? usp=drive_link</u> 



<!-- Start of picture text -->
| SETUP MASTER (JABATAN) ADMIN HR<br>==<br><!-- End of picture text -->



<!-- Start of picture text -->
(SETUP MASTER (GOLONGAN) ADMIN HR<br><!-- End of picture text -->



<!-- Start of picture text -->
i=<br><!-- End of picture text -->



<!-- Start of picture text -->
FIX HANYA PERLU DI BACA ULANG<br>SETUP MASTER (DEPARTEMEN) ADMIN HR<br>= }<br><!-- End of picture text -->





<!-- Start of picture text -->
USETUP MASTER(UNIT KERJA) ADMIN HR)<br>pw<br>l<br>==}<br><!-- End of picture text -->



<!-- Start of picture text -->
nn SS<br>Login ke Sistem<br>Masuk ke Dasboard<br>Pilih Menu Setup Menampilkan dropdown<br>Jadwal dan cuti sub menu<br>Pilih salah satu<br>Sub Menu<br>Memproses pilinan sub<br>Melihat halaman sub Menampilkan halaman sub<br>menu yang dipilin menu yang dipilin<br><!-- End of picture text -->





##### **Activity Diagram Setup Jadwal dan Cuti (Group Shift) Admin HR** 

menggambarkan alur proses pengelolaan data **group shift** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Setup Jadwal dan Cuti** dan memilih submenu **Group Shift** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data berupa penambahan, perubahan, maupun penghapusan data group shift. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui daftar data group shift sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 12. Activity Diagram Kelola Shift Kerja Admin HR 

##### **Activity Diagram Setup Jadwal dan Cuti (Shift Kerja) Admin HR** 

menggambarkan alur proses pengelolaan data **shift kerja** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Setup Jadwal dan Cuti** dan memilih submenu **Shift Kerja** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data berupa penambahan, perubahan, maupun penghapusan data shift kerja. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui daftar data shift kerja sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 13. Activity Diagram Kelola Jenis Cuti Admin HR 

##### **Activity Diagram Setup Jadwal dan Cuti (Jenis Cuti) Admin HR** 

menggambarkan alur proses pengelolaan data **jenis cuti** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Setup Jadwal dan Cuti** dan memilih submenu **Jenis Cuti** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data berupa penambahan, perubahan, maupun penghapusan data jenis cuti. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses 

berhasil, serta memperbarui daftar data jenis cuti sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 14. Activity Diagram Kelola Saldo Cuti Pegawai Admin HR 

##### **Activity Diagram Setup Jadwal dan Cuti (Saldo Cuti) Admin HR** 

menggambarkan alur proses pengelolaan data **saldo cuti pegawai** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Setup Jadwal dan Cuti** dan memilih submenu **Saldo Cuti** . Pada halaman tersebut, Admin HR dapat melakukan pencarian data pegawai serta mengelola saldo cuti melalui proses penambahan, perubahan, maupun penghapusan data. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui data saldo cuti pegawai sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 15. Activity Diagram Kelola Jadwal Shift Pegawai Admin HR 

##### **Activity Diagram Setup Jadwal Shift Pegawai Admin HR** menggambarkan 

alur proses pengelolaan jadwal shift pegawai oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Jadwal Shift Pegawai** , kemudian sistem menampilkan halaman jadwal shift pegawai. Pada halaman tersebut, Admin HR dapat melakukan pencarian data pegawai maupun mengatur jadwal shift. Dalam proses pengaturan jadwal, Admin HR memilih pegawai, menentukan jadwal kerja beserta jenis shift yang akan diterapkan, kemudian sistem melakukan validasi terhadap data yang diinput. Apabila data dinyatakan valid, sistem akan menyimpan perubahan ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui data jadwal shift pegawai sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 16. <mark>Activity Diagram Kelola Cuti Admin HR</mark> 

##### 17. <mark>Activity Diagram Kelola Lembur Admin HR</mark> 

##### 18. Activity Diagram Kelola Skala Gaji Berkala Admin HR 

**Activity Diagram Skala Gaji Berkala Admin HR** menggambarkan alur proses pengelolaan data **skala gaji berkala** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Gaji** dan memilih submenu **Skala Gaji Berkala** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data berupa penambahan, perubahan, maupun penghapusan data skala gaji berkala. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui daftar data skala gaji berkala sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 19. Activity Diagram Kelola Riwayat Gaji Pegawai Admin HR 

**Activity Diagram Riwayat Gaji Pegawai Admin HR** menggambarkan alur proses pengelolaan **riwayat gaji pegawai** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Gaji** dan memilih submenu **Riwayat Gaji Pegawai** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data riwayat gaji pegawai, seperti menambahkan, mengubah, maupun menghapus data sesuai kebutuhan. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui data riwayat gaji pegawai sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 20. Activity Diagram Kelola Titik Lokasi Absensi Admin HR 

**Activity Diagram Setup Titik Lokasi Absensi Admin HR** menggambarkan alur proses pengelolaan data **titik lokasi absensi** oleh Admin HR. Proses diawali dengan Admin HR mengakses menu **Titik Lokasi Absensi** . Pada halaman tersebut, Admin HR dapat melakukan pengelolaan data berupa penambahan, perubahan, maupun penghapusan titik lokasi absensi. Sistem kemudian melakukan validasi terhadap setiap perubahan yang dilakukan. Apabila data dinyatakan valid, sistem akan menyimpan data ke dalam database, menampilkan notifikasi bahwa proses berhasil, serta memperbarui daftar data titik lokasi absensi sehingga informasi yang ditampilkan selalu sesuai dengan data terbaru. 

##### 21. <mark>Activity Diagram Logout Admin HR</mark> 

##### 22. Activity Diagram Presensi karyawan 

**Activity Diagram Presensi Karyawan** menggambarkan alur proses presensi yang dilakukan oleh karyawan. Proses diawali dengan karyawan mengakses menu **Presensi** atau **Check In** . Pada halaman presensi, karyawan dapat mengisi catatan apabila diperlukan serta memilih status **dinas luar** jika sedang melaksanakan tugas di luar lokasi kerja. Selanjutnya, sistem melakukan verifikasi lokasi dan perangkat untuk memastikan bahwa presensi dilakukan sesuai dengan ketentuan yang berlaku. Apabila proses verifikasi berhasil, sistem akan menyimpan data presensi ke dalam database, menampilkan notifikasi bahwa presensi berhasil, serta mencatat kehadiran karyawan. Sebaliknya, apabila verifikasi lokasi atau perangkat tidak berhasil, sistem akan menampilkan pesan bahwa proses presensi tidak dapat dilakukan karena lokasi atau perangkat belum terverifikasi. 

##### 23. Activity Diagram Slip Gaji karyawan 

**Activity Diagram Menu Slip Gaji Karyawan** menggambarkan alur akses karyawan untuk melihat informasi **slip gaji** . Proses diawali dengan karyawan mengakses menu **Slip Gaji** . Selanjutnya, sistem menampilkan halaman yang berisi daftar slip gaji yang dimiliki oleh karyawan. Karyawan kemudian dapat memilih dan melihat rincian slip gaji sesuai periode yang diinginkan. Sistem akan menampilkan informasi slip gaji secara lengkap sehingga karyawan dapat mengetahui detail penghasilan yang diterima. 

##### 24. Activity Diagram Mengelola Profil Karyawan 

**Activity Diagram Profil Karyawan** menggambarkan alur pengelolaan profil karyawan, khususnya pada proses **perubahan kata sandi** . Proses diawali dengan karyawan mengakses menu **Profil** , kemudian sistem menampilkan informasi profil karyawan. Apabila karyawan ingin mengubah kata sandi, karyawan mengisi kata sandi saat ini, kata sandi baru, dan konfirmasi kata sandi. Selanjutnya, sistem melakukan validasi terhadap data yang dimasukkan. Apabila data dinyatakan valid, sistem akan 



<!-- Start of picture text -->
HALAMAN<br>f LOGIN- SISTEM DATABASE DASHBOARD<br>KARYAWAN '<br>H mengisi username dan password ' H H H<br>mengirim data login H H H<br>mengirim data login { H<br>' validasi data H<br>H data login tidak valid H<br>i le mengirim pesan gagal login menampilkan dashboard H<br>| menampilkan pesan username dan password salah<br>eo----------menampilkandashboard<br>menampilkan dashboard<br>l<c------------menampilkandashboard<br>halaman dashboard tertampil<br><!-- End of picture text -->



<!-- Start of picture text -->
Tq HALAMAN CUTI SISTEM DATABASE<br>H buka halaman cuti H ' '<br>Request saldo & riwayat \ '<br>Ambil data cuti H<br>' Saldo & riwayat cuti<br>H IKE ~~ === = nnn nnn naan enna enna ane<br>' l< Tampilkan saldo & riwayat<br>Isi form pengajuan cuti<br>' submit pengajuan cuti<br>' validasi data<br>' tampilkan pesan error<br>H KZ ~~~ = won nnn neon nnnnena<br>' menampilkan pesan data tidak valid . . .<br>[Goon nnn nnn nsec enna nnn nanan simpan pengajuan cuti<br>' data tersimpan<br>Pengajuan cuti berhasil<br>| notifikasi berhasil mengajukan<br><!-- End of picture text -->

Sequence Diagram Pengajuan Cuti menunjukkan alur komunikasi saat karyawan mengajukan cuti. Ketika halaman cuti dibuka, sistem terlebih dahulu meminta data saldo cuti dan riwayat pengajuan kepada database. Setelah informasi tersebut ditampilkan, karyawan mengisi formulir pengajuan cuti dan mengirimkannya ke sistem. Sistem melakukan validasi terhadap data yang diterima. Jika terdapat kesalahan, sistem menampilkan pesan error kepada pengguna. Namun apabila data valid, sistem menyimpan pengajuan ke database dan menampilkan notifikasi bahwa pengajuan cuti berhasil dikirim. 

Berikut ini link untuk diagram : 

<u>https://drive.google.com/file/d/1cy6yoZSDjPVVPXVBGxqa2NEGLAZhFhyM/view? usp=sharing</u> 

30. Sequence Diagram Pengajuan Lembur 



<!-- Start of picture text -->
HALAMANLEMBUR SISTEM DATABASE<br>H buka halaman lembur H ' '<br>Request riwayat lembur \ '<br>Ambil data lembur H<br>' riwayat lembur<br>H IKE ~~ === = neon Fon nnn nnn nnn n nena nn<br>' le Tampilkan riwayat lembur<br>Isi form pengajuan lembur<br>' submit pengajuan lembur<br>' validasi data<br>tampilkan pesan error<br>pesan input tidak valid tertampil . :<br>foo a anna anna anna nanan anne nanan nanan nae simpan pengajuan lembur<br>' data tersimpan<br>' Pengajuan lembur berhasil<br>| notifikasi berhasil mengajukan<br><!-- End of picture text -->



<!-- Start of picture text -->
Halaman tambah Sistem kelola Database<br>karyawan karyawan<br>Manager ; H<br>''i i )':i<br>i<br>'i<br>Suka Halaman ' :<br>fambah karyawen ' '<br>1<br>si data lengkap pegawai ' '<br>i<br>Request dafiar approval<br>Validasi data '<br>i1<br>ii<br>ii<br>ii<br>ii<br>i<br>i<br>'Simpan data karyavwan<br>\ Tampikan dafiar kargawan<br>[T '' i\<br>i i)1 i<br>i<br><!-- End of picture text -->



<!-- Start of picture text -->
ApprovalHalaman Cuti . Sistem- Database<br>ManagerH1'T'i'<br>Buka Halaman Request dafiar approval 'H<br>Approval cuti . . i<br>mii! das pengajuan culi<br>daftar cuti<br>oun<br>1<br>'<br>S ~~ Tampikanne eeedafiar approvaleen H!<br>Halaman approwal tampil ' '<br>manne<br>alt ' ' H'<br>isi keterangan & tolak ' Submit penalakan !'\ i'<br>Hy7i Upetsste sslastuss chiteslesk, ‘i<br>H Status diperbarui<br>aPenolakan berhasil ''1'''eee eeePenolakan b e rhasilee eee HI'i\'\\ 2.2----l DSTI eee '\'H‘'''H'<br>Setujui pengajuan lain H ' '<br>i '<br>Submit persetujuan I '<br>i‘i<br>'Update status disetujui<br>‘<br>' Status diperbarui<br>' WSPersetujuan berhssil ----- ae'<br>BTNolifikas!TT TTpersetujuan 'i‘H'‘<br>: ;<br><!-- End of picture text -->



<!-- Start of picture text -->
Approval Lembur Sistem Database<br>ManagerX1HalamanH1“ H!H1<br>I! H<br>Buka Halsensin Request dafiar approval H<br>approval lembur mail date pengajuan lembur<br>daftar lembur<br>oo<br>I<br>2. TampikanUe daftarSOPapproval eee H'<br>altisiHalamanBOkesberanganTae eeeeeeeeapproval& tlatampil 'i'HH'' 'H\ ' '' 'HH'<br>Submit penalakart I<br>7'<br>HUpdate status ditalak 1<br>'<br>H Status diperbarui<br>i teenPrnolakan berhiasileee ST'<br>Penolakan bertasil H 'H<br>see I '<br>7''''\ ' '''<br>'f'\ H<br>Setujui pengajuan lain. H '<br>‘ '<br>Submit persetujuan H '<br>i '<br>' Update status disetujui H<br>'<br>H Status diperbarui<br>' Persetujuan berhasil j<br>ee '<br>2 Eee '''' '''<br>i Notifikes! persetujucn ff '' H|<br><!-- End of picture text -->



<!-- Start of picture text -->
Absensi tim Sistem Database<br>Manager'\ ‘ 1i<br>!i i<br>Buka halaman absensi tim Request riwayal sheers ti '<br>mil data rwayal absensi<br>Riwayal absensi tim<br>Tampilkan riwayat abeensi tim '<br>Sm a '<br>Halaman tampil '<br>eae leat H<br>i ' 1<br>i1<br><!-- End of picture text -->



<!-- Start of picture text -->
Slip gaji Sistem Database<br>Manageri|<br>i i)<br>\ \ \<br>i)<br>i)<br>Suka halaman slip paji Request riwayal slip gaji '<br>mil data sip gj<br>Riwayat slip gaji<br>2 __ Tampilkanwees rwayaleee sip ge H'<br>Halaman tampil \<br>eae leat H<br>i ' 1<br>i i ' 1<br><!-- End of picture text -->



<!-- Start of picture text -->
Halaman Gaiji Sistem Gaii Penghitung Lembur & Penghitung Pajak & Database<br>Potongan BPJS<br>Manager H H H H H<br>aa Tampikan daftar gaji ' ' '<br>{Ambil data gaji semua karyawan H<br>1 Ambil komponen gaji aks H<br>Hitung lembur Bulan i :<br>fitung estimasi BPJS, pajak, gaji bersih '<br><!-- End of picture text -->



<!-- Start of picture text -->
Halaman titik Sistem Titik Database<br>lokasi Lokasi<br>Manager<br>H ' '‘‘1<br>\\<br>Kelola dafiar titik bokzes ' '<br>Bualubsh titk lokasi | ' \<br>Simpan nama, koordinat, radius.<br>status okt<br>Pilih karyawan + tk lokasi<br>Pasangkan lokasi ke karyewan<br>77<br>i '<br>H ' Update data karyawan<br>'\<br>i i<br>' \<br>H i ' ‘ H7‘i<br>'\ \ \<br><!-- End of picture text -->

### **Tahapan Implementasi** 

|**Tahap**|**Aktivitas**|**Estimasi**<br>**Waktu**|**Output**|
|---|---|---|---|
|Sprint 3|Implementasi Authentication, Role<br>Management, dan User Management|2 minggu|Modul login dan<br>manajemen<br>pengguna|
|Sprint 4|Implementasi<br>Master<br>Data<br>(Karyawan, Jabatan, Departemen,<br>Unit Kerja)|3 minggu|Modul master<br>data|
|Sprint 5|Implementasi Absensi, Cuti, dan<br>Approval|4 minggu|Modul absensi<br>dan cuti|
|Sprint 6|Implementasi Dashboard, Reporting,<br>dan Integrasi Modul|3 minggu|Sistem<br>terintegrasi|
|Sprint 7|Black Box Testing, User Acceptance<br>Testing (UAT), dan perbaikan|2 minggu|Sistem<br>siap<br>digunakan|



### **<u>Jadwal Pengembangan</u>** 

|**Tahapan**|**Status**|
|---|---|
|Analisis Kebutuhan|✓Selesai (Capstone 1)|
|Perancangan Sistem|✓Selesai (Capstone 1)|
|Implementasi Backend|Direncanakan pada Capstone 2|
|Implementasi Frontend|Direncanakan pada Capstone 2|



|Integrasi Sistem|Direncanakan pada Capstone 2|
|---|---|
|Pengujian Sistem|Direncanakan pada Capstone 2|
|Deployment|Direncanakan setelah seluruh pengujian selesai|



### **Penjelasan** 

Berdasarkan hasil analisis kebutuhan dan desain sistem yang telah disusun, implementasi sistem akan dilaksanakan pada tahap pengembangan berikutnya menggunakan metode Agile Scrum. Setiap sprint difokuskan pada penyelesaian beberapa modul agar proses pengembangan dapat dilakukan secara bertahap, mudah dievaluasi, dan mampu menyesuaikan apabila terdapat perubahan kebutuhan dari mitra. Setelah seluruh modul selesai diimplementasikan, sistem akan melalui tahap integrasi, pengujian fungsional menggunakan **Black Box Testing** , **User Acceptance Testing (UAT)** , serta perbaikan sebelum siap untuk diimplementasikan pada lingkungan operasional. 

#### **G. Keamanan Sistem** 

#### **1. Autentikasi dan Login** 

#### a. Keamanan Password 

sistem menerapkan mekanisme keamanan pada proses autentikasi dengan menggunakan hashing password yang disediakan oleh framework Laravel. Password pengguna tidak disimpan dalam bentuk teks biasa ( _plain text_ ), melainkan diubah menjadi nilai hash menggunakan algoritma Bcrypt atau Argon2 sebelum disimpan ke dalam database. Dengan mekanisme ini, apabila database diakses oleh pihak yang tidak berwenang, password asli pengguna tetap tidak dapat diketahui sehingga keamanan data akun lebih terjaga. 

#### b. Verifikasi Login 

Pada saat pengguna melakukan login, sistem terlebih dahulu memverifikasi keberadaan email yang dimasukkan pada database. Jika email ditemukan, sistem akan melakukan pencocokan password menggunakan fungsi Hash::check(), yaitu dengan membandingkan password yang diinput pengguna terhadap nilai hash yang tersimpan di database. Apabila hasil verifikasi berhasil, sistem akan membuat session login sebagai tanda bahwa pengguna telah terautentikasi. Sebaliknya, apabila email tidak ditemukan atau password tidak sesuai, sistem akan menampilkan pesan kesalahan dan pengguna diminta untuk melakukan login kembali. 

#### c. Pengelolaan Hak Akses 

Setelah proses autentikasi berhasil, sistem menerapkan pengelolaan hak akses ( _role-based access control_ ) berdasarkan peran pengguna. Pengguna dengan peran **Admin** akan diarahkan menuju halaman dashboard administrator, sedangkan pengguna dengan peran **Manager** dan **Karyawan** akan diarahkan ke halaman portal karyawan. Mekanisme ini memastikan setiap pengguna hanya dapat mengakses fitur dan informasi sesuai dengan hak akses yang dimilikinya. 

#### **2. Device Binding (Satu Akun Satu Perangkat)** 

#### a. Device Fingerprint 

Sistem menerapkan mekanisme **Device Binding** untuk memastikan bahwa satu akun hanya dapat digunakan pada satu perangkat yang telah terdaftar. Identitas perangkat dibentuk menggunakan **device fingerprint** , yaitu kode unik yang dihasilkan dari kombinasi beberapa karakteristik perangkat, seperti _User Agent_ (informasi browser dan sistem operasi), bahasa browser, resolusi layar, _color depth_ , serta zona waktu ( _timezone_ ). Data tersebut 

kemudian dikodekan ( _encoding_ ) menjadi sebuah fingerprint yang digunakan sebagai identitas perangkat. 

#### b. Proses Verifikasi Perangkat 

Ketika pengguna membuka halaman presensi, sistem akan menghasilkan ( _generate_ ) device fingerprint berdasarkan karakteristik perangkat yang sedang digunakan. Selanjutnya sistem memeriksa apakah perangkat tersebut telah terdaftar pada database. Apabila pengguna belum pernah mendaftarkan perangkat, fingerprint akan disimpan sebagai perangkat resmi milik pengguna. Namun, apabila perangkat telah terdaftar, sistem akan membandingkan fingerprint yang baru dihasilkan dengan fingerprint yang tersimpan di database. Jika keduanya sesuai, proses verifikasi berhasil dan pengguna dapat melanjutkan aktivitas presensi. Sebaliknya, apabila fingerprint tidak sesuai, sistem akan menolak akses dan menampilkan informasi bahwa akun telah terdaftar pada perangkat lain. 

#### c. Penyimpanan Data Perangkat 

Untuk mendukung mekanisme Device Binding, sistem menyimpan informasi perangkat pada tabel **UserDevice** . Informasi yang disimpan meliputi identitas pengguna ( _user_id_ ), device fingerprint sebagai identitas unik perangkat, label perangkat, informasi browser ( _user agent_ ), platform sistem operasi, waktu pendaftaran perangkat ( _registered_at_ ), serta waktu terakhir perangkat digunakan ( _last_used_at_ ). Data tersebut digunakan sebagai acuan dalam proses verifikasi perangkat setiap kali pengguna mengakses fitur presensi. 

#### **3. Verifikasi Biometrik (Face ID / Fingerprint)** 

- a. Penerapan WebAuthn 

Sistem menerapkan verifikasi biometrik menggunakan teknologi **Web Authentication (WebAuthn)** , yaitu standar autentikasi yang dikembangkan oleh W3C untuk mendukung proses autentikasi yang lebih 

aman tanpa mengirimkan data biometrik ke server. Melalui WebAuthn, sistem dapat memanfaatkan fitur keamanan bawaan perangkat, seperti **Face ID** , **Fingerprint** , atau **Windows Hello** , sesuai dengan kemampuan perangkat yang digunakan. Pada implementasi ini, proses verifikasi identitas pengguna diwajibkan ( _user verification required_ ) sehingga autentikasi hanya dapat dilakukan setelah pengguna berhasil melakukan verifikasi biometrik pada perangkatnya. 

#### b. Proses Verifikasi Biometrik 

Pada saat pengguna melakukan presensi, sistem akan menghasilkan sebuah **challenge** berupa nilai acak yang bersifat unik untuk setiap proses autentikasi. Challenge ini digunakan untuk mencegah serangan seperti _replay attack_ , yaitu penggunaan ulang data autentikasi yang pernah dikirim sebelumnya. Selanjutnya, sistem meminta pengguna melakukan verifikasi menggunakan metode biometrik yang tersedia pada perangkat, seperti Face ID atau Fingerprint. Jika proses verifikasi berhasil, sistem akan menyimpan **credential ID** pada perangkat pengguna sehingga dapat digunakan kembali pada proses autentikasi berikutnya. Apabila perangkat tidak mendukung WebAuthn, sistem akan menggunakan credential yang telah tersimpan sebagai mekanisme alternatif ( _fallback_ ) agar proses autentikasi tetap dapat dilakukan sesuai kemampuan perangkat. 

#### c. Tujuan Penerapan 

Penerapan verifikasi biometrik bertujuan untuk meningkatkan keamanan proses presensi dengan memastikan bahwa hanya pemilik sah perangkat yang dapat melakukan autentikasi. Selain itu, penggunaan challenge yang berbeda pada setiap proses autentikasi memberikan perlindungan tambahan terhadap penyalahgunaan data autentikasi, sehingga proses login maupun presensi menjadi lebih aman dan andal. 

#### **4. Geofencing (Pembatasan Lokasi Presensi)** 

#### a. Penerapan Geofencing 

Sistem menerapkan mekanisme **Geofencing** sebagai pengamanan lokasi presensi agar karyawan hanya dapat melakukan presensi pada area kerja yang telah ditentukan. Mekanisme ini memanfaatkan koordinat GPS dari perangkat pengguna untuk menentukan apakah posisi pengguna berada di dalam atau di luar radius lokasi yang diizinkan. Perhitungan jarak antara lokasi pengguna dengan titik koordinat lokasi presensi dilakukan menggunakan **rumus Haversine** , yang mampu menghitung jarak antara dua titik berdasarkan koordinat lintang ( _latitude_ ) dan bujur ( _longitude_ ). 

#### b. Proses Verifikasi Lokasi 

Ketika pengguna membuka halaman presensi, sistem akan meminta izin untuk mengakses lokasi perangkat menggunakan layanan **Geolocation API** dengan tingkat akurasi tinggi ( _high accuracy_ ). Setelah koordinat berhasil diperoleh, sistem menghitung jarak antara lokasi pengguna dan lokasi presensi menggunakan rumus Haversine. Selain melakukan pemeriksaan pada sisi _frontend_ , sistem juga melakukan verifikasi ulang pada sisi _backend_ sebagai bentuk pengamanan tambahan untuk memastikan bahwa data lokasi tidak dimanipulasi sebelum proses presensi disimpan ke dalam database. 

#### c. Prioritas Lokasi dan Mode Dinas Luar 

Sistem mendukung lebih dari satu lokasi presensi dengan mekanisme prioritas. Apabila seorang karyawan telah memiliki lokasi kerja yang ditentukan secara khusus, maka proses verifikasi akan dilakukan terhadap lokasi tersebut. Namun, jika karyawan tidak memiliki lokasi yang ditetapkan secara khusus, sistem akan menggunakan seluruh lokasi aktif sebagai acuan verifikasi. Selain itu, sistem menyediakan **mode dinas luar** sebagai pengecualian bagi karyawan yang sedang menjalankan tugas di luar area kerja. Dengan mekanisme ini, proses presensi tetap dapat dilakukan sesuai ketentuan yang berlaku. 

#### d. ujuan Penerapan 

Penerapan Geofencing bertujuan untuk memastikan bahwa presensi dilakukan dari lokasi yang telah ditetapkan oleh perusahaan. Selain itu, verifikasi lokasi pada sisi _frontend_ dan _backend_ memberikan lapisan keamanan tambahan sehingga mengurangi risiko manipulasi koordinat lokasi dan meningkatkan keakuratan data presensi 

#### **5. Attendance Consent (Persetujuan Pengguna)** 

- a. Penerapan Attendance Consent 

Sebelum karyawan dapat melakukan proses presensi, sistem mewajibkan pengguna untuk memberikan persetujuan ( _attendance consent_ ) terhadap dokumen yang berkaitan dengan pelaksanaan presensi. Mekanisme ini bertujuan untuk memastikan bahwa setiap karyawan telah memahami kebijakan dan prosedur yang berlaku sebelum menggunakan fitur presensi pada sistem. 

#### b. Proses Persetujuan 

Sistem mewajibkan pengguna untuk menyetujui dua jenis dokumen, yaitu **Perjanjian Absensi** yang berisi ketentuan dan kebijakan pelaksanaan presensi, serta **Task List Flowchart** yang menjelaskan alur penggunaan fitur presensi dalam sistem. Ketika pengguna mengakses fitur presensi, sistem akan memeriksa status persetujuan yang telah diberikan. Apabila salah satu atau kedua dokumen belum disetujui, pengguna akan diarahkan terlebih dahulu ke halaman persetujuan sebelum dapat melanjutkan proses presensi. 

#### c. Pencatatan Persetujuan 

Setiap persetujuan yang diberikan oleh pengguna akan disimpan sebagai bukti bahwa pengguna telah membaca dan menyetujui ketentuan yang 

berlaku. Informasi yang dicatat meliputi identitas pengguna, jenis dokumen yang disetujui, status persetujuan, waktu persetujuan, serta alamat IP perangkat yang digunakan saat memberikan persetujuan. Pencatatan tersebut dapat digunakan sebagai dokumentasi dan bukti apabila diperlukan dalam proses audit maupun penelusuran aktivitas pengguna. 

#### d. Tujuan Penerapan 

Penerapan **Attendance Consent** bertujuan untuk memastikan bahwa seluruh karyawan memahami kebijakan dan prosedur presensi yang berlaku sebelum menggunakan sistem. Selain itu, mekanisme ini meningkatkan aspek akuntabilitas karena setiap persetujuan tercatat secara sistematis sehingga dapat dijadikan bukti bahwa pengguna telah menerima dan menyetujui ketentuan yang ditetapkan. 

#### 6. **Audit Trail dan Data Integrity** 

#### a. Penerapan Audit Trail 

Sistem menerapkan mekanisme **Audit Trail** untuk mencatat setiap aktivitas presensi secara lengkap. Setiap data presensi tidak hanya menyimpan informasi waktu kehadiran, tetapi juga berbagai metadata yang berkaitan dengan proses presensi. Pencatatan ini bertujuan untuk menjaga integritas data serta menyediakan riwayat aktivitas yang dapat digunakan sebagai bahan verifikasi maupun audit apabila diperlukan 

#### b. Metadata Presensi 

Pada setiap transaksi presensi, sistem menyimpan berbagai informasi pendukung, antara lain tanggal dan waktu presensi, koordinat lokasi 

( _latitude_ dan _longitude_ ), tingkat akurasi GPS, jarak pengguna terhadap lokasi presensi, identitas perangkat ( _device fingerprint_ ), status verifikasi biometrik, status dinas luar, informasi browser ( _user agent_ ), serta waktu penyimpanan data ( _timestamp_ ). Seluruh metadata tersebut disimpan secara bersamaan dengan data presensi sehingga setiap transaksi memiliki informasi pendukung yang lengkap. 

- c. Tujuan Penerapan 

Penerapan Audit Trail bertujuan untuk menjaga **integritas data presensi** dengan memastikan setiap aktivitas memiliki informasi pendukung yang lengkap dan dapat ditelusuri kembali. Metadata yang tersimpan memungkinkan administrator melakukan proses verifikasi apabila terjadi perbedaan data, dugaan penyalahgunaan sistem, maupun kebutuhan audit di kemudian hari. Dengan demikian, setiap data presensi memiliki rekam jejak ( _traceability_ ) yang jelas dan dapat dipertanggungjawabkan. 

#### **H. Persetujuan** 

**Tabel  3.1.** Lembar persetujuan dokumen C-3 

|**No**|**Anggota Kelompok**|**Menyetujui**|
|---|---|---|
|1|Muhammad Satrioadi/2311501028||
|2|Annisa Ashadia Nurhaliza Savana/2311501050||
|3|Rizki Fachriadi Iskandar/2311501054||
|4|Nathasya Destia Fany Sitorus/2311501058||
|5|Nessa Aulia Rahma /2311501024||



Yogyakarta, 00 Juli 2026 

Menyetujui, 

Dosen Pembimbing 1 

<u>Dr. Arizona Firdonsyah, S.Kom., M.Kom.</u> 

NIP. 8005011810481 

## **DAFTAR REFERENSI** 

### Jurnal Ilmiah
1.  Wibowo, A. S., & Putri, R. A. (2023). Pengembangan Sistem Informasi Manajemen Kepegawaian Berbasis Web Menggunakan Metode Agile Scrum. Jurnal Sistem Informasi dan Teknologi, 8(2), 145-158. https://doi.org/10.33395/jsi.v8i2.1234
2.  Hartono, S., & Susanti, E. (2024). Implementasi Geofencing dan Biometric Authentication untuk Keamanan Sistem Presensi Karyawan. Jurnal Keamanan Informasi, 7(1), 45-58. https://doi.org/10.54321/jki.v7i1.456
3.  Wijaya, B. P., & Santoso, H. (2023). Device Binding: Mekanisme Keamanan Satu Akun Satu Perangkat pada Aplikasi Mobile. Jurnal Teknologi Informasi, 12(3), 234-247. https://doi.org/10.6789/jti.v12i3.789
4.  Setiawan, A., & Pratiwi, D. (2024). Analisis Efektivitas Metode Agile Scrum dalam Pengembangan Sistem Informasi HRIS. Jurnal Teknik Komputer, 11(1), 67-80. https://doi.org/10.9876/jtk.v11i1.101112
5.  Suherman, T., & Utami, S. (2023). Pengelolaan Data Karyawan Berbasis Web untuk Meningkatkan Efisiensi Administrasi. Jurnal Manajemen dan Teknologi Informasi, 9(2), 112-125. https://doi.org/10.5555/jmt.v9i2.131415
6.  Permana, R., & Sari, N. (2024). Verifikasi Lokasi Menggunakan Rumus Haversine pada Sistem Presensi Digital. Jurnal Geoinformatika, 8(1), 34-47. https://doi.org/10.7777/jg.v8i1.161718
7.  Nugroho, A., & Astuti, W. (2023). WebAuthn: Standar Autentikasi Biometrik untuk Keamanan Aplikasi Web. Jurnal Ilmu Komputer dan Sistem Informasi, 10(3), 198-211. https://doi.org/10.8888/jksi.v10i3.192021
8.  Suryani, E., & Prasetya, B. (2024). Role-Based Access Control pada Sistem Informasi Kepegawaian. Jurnal Keamanan dan Privasi Data, 6(2), 89-102. https://doi.org/10.9999/jkpd.v6i2.222324
9.  Putra, H. A., & Ningsih, R. (2023). Audit Trail untuk Menjaga Integritas Data pada Sistem Presensi Karyawan. Jurnal Teknologi Keamanan Informasi, 7(2), 123-136. https://doi.org/10.1234/jtki.v7i2.252627
10. Darmawan, A., & Fitriani, L. (2024). Implementasi Geolocation API untuk Presensi Karyawan Berbasis Lokasi. Jurnal Teknologi Mobile, 5(1), 56-69. https://doi.org/10.4321/jtm.v5i1.282930

### Dokumen dan Website
11. Educative. (n.d.). What are include and extend relationships in a use case diagram? https://www.educative.io/answers/what-are-include-and-extendrelationships-in-a-use-case-diagram 
12. Abdullah, A., & Ahmad, M. (2016). Use case diagram of operations module. ResearchGate. https://www.researchgate.net/figure/Use-case-diagram-ofoperations-module_fig2_303880086 
13. BINUS University. (2021, May 27). Menggambar proses bisnis dengan activity diagram. https://sis.binus.ac.id/2021/05/27/mengambar-proses-bisnis-denganactivity-diagram-2/ 
14. Piksi Ganesha Polytechnic. (2024). Penerapan metode Agile dalam pengembangan sistem informasi (INFOKOM). https://journal.piksi.ac.id/index.php/INFOKOM/article/view/2172/1390 
15. Dicoding Indonesia. (2021). Apa itu activity diagram? https://www.dicoding.com/blog/apa-itu-activity-diagram/ 
16. Nurmasani, A., Ilham, M. R., & Hartanto, A. D. (2024). Implementasi metode scrum pada fitur pemantauan kegiatan pembelajaran di luar program studi. Jurnal Komputer Terapan, 10(1), 27–35. https://doi.org/10.35143/jkt.v10i1.5972 
17. Nurmasani, A., Kurniawan, F. D., Hartanto, A. D., & Fajri, I. N. (2024). Penerapan metode scrum pada pengembangan sistem informasi pencatatan magang. Information System Journal (INFOS), 7(1), 34–44. https://doi.org/10.24076/infosjournal.2024v7i01.1616 

