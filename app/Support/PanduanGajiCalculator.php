<?php

namespace App\Support;

use App\Models\EmployeeMonthlyPayroll;
use App\Models\Karyawan;
use App\Models\Overtime;

/**
 * Perhitungan slip gaji mengikuti "Panduan Membaca Komponen Gaji".
 *
 * Alur panduan:
 *  - Tahap 1: data dasar (komponen tetap + status keluarga) dari riwayat gaji aktif.
 *  - Tahap 2: penggabungan dengan rekap bulan (kehadiran, lembur, on call, potongan).
 *
 * Hasil akhir dibedakan menjadi A (penghasilan bruto), B (potongan resmi),
 * D (potongan lain), gaji bersih (A - B), dan terima bersih (A - B - D).
 */
class PanduanGajiCalculator
{
    public static function konfigurasi(): array
    {
        return config('payroll.panduan', []);
    }

    /**
     * Data dasar aktif: baris riwayat gaji terbaru berdasarkan tanggal berlaku.
     * Bila belum ada riwayat, dipakai komponen gaji pada jabatan.
     */
    public static function dataDasar(Karyawan $karyawan): array
    {
        $cfg = self::konfigurasi();

        $histories = $karyawan->relationLoaded('salaryHistories')
            ? $karyawan->salaryHistories->sortByDesc(fn ($row) => sprintf('%s-%010d', optional($row->tanggal_berlaku)->format('Ymd') ?? '00000000', $row->id))
            : $karyawan->salaryHistories()->orderByDesc('tanggal_berlaku')->orderByDesc('id')->get();

        $history = $histories->first();

        if ($history) {
            return [
                'gaji_pokok' => (float) $history->gaji_pokok,
                'tunjangan_jabatan' => (float) $history->tunjangan_jabatan,
                'tunjangan_fungsional' => (float) $history->tunjangan_fungsional,
                'tunjangan_profesi' => (float) $history->tunjangan_profesi,
                'tunjangan_makan' => (float) $history->tunjangan_makan,
                'tunjangan_pulsa' => (float) $history->tunjangan_pulsa,
                'tkp' => (float) $history->tkp,
                'pasangan_ditanggung' => (bool) $history->pasangan_ditanggung,
                'jumlah_anak' => min((int) $history->jumlah_anak_ditanggung, (int) ($cfg['maks_anak'] ?? 3)),
                'hari_kerja_per_minggu' => (int) ($history->hari_kerja_per_minggu ?: 5),
                'tanggal_berlaku' => $history->tanggal_berlaku,
                'sumber' => 'riwayat_gaji',
            ];
        }

        return [
            'gaji_pokok' => (float) ($karyawan->jabatan->gaji_pokok ?? 0),
            'tunjangan_jabatan' => 0.0,
            'tunjangan_fungsional' => 0.0,
            'tunjangan_profesi' => 0.0,
            'tunjangan_makan' => 0.0,
            'tunjangan_pulsa' => 0.0,
            'tkp' => 0.0,
            'pasangan_ditanggung' => false,
            'jumlah_anak' => 0,
            'hari_kerja_per_minggu' => 5,
            'tanggal_berlaku' => null,
            'sumber' => 'jabatan',
        ];
    }

    /**
     * Peserta magang dan sejenisnya tidak memperoleh tunjangan keluarga,
     * tunjangan beras, dan dana pensiun.
     */
    public static function berhakTunjanganKeluarga(Karyawan $karyawan): bool
    {
        $status = $karyawan->employeeStatus;
        if (!$status) {
            return true;
        }

        foreach (self::konfigurasi()['status_tanpa_tunjangan_keluarga'] ?? [] as $kata) {
            if (stripos($status->kode, $kata) !== false || stripos($status->nama, $kata) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Susun slip gaji satu periode.
     *
     * @return array{periode:string,label_periode:string,pendapatan:array,potongan_resmi:array,potongan_lain:array,...}
     */
    public static function slip(Karyawan $karyawan, string $periode, ?EmployeeMonthlyPayroll $rekap = null): array
    {
        $cfg = self::konfigurasi();
        $dasar = self::dataDasar($karyawan);
        $berhakKeluarga = self::berhakTunjanganKeluarga($karyawan);

        $gajiPokok = $dasar['gaji_pokok'];
        $pasanganDitanggung = $berhakKeluarga && $dasar['pasangan_ditanggung'];
        $jumlahAnak = $berhakKeluarga ? $dasar['jumlah_anak'] : 0;

        // Kelompok 2: komponen yang mengikuti gaji pokok / status keluarga.
        $tunjanganPasangan = $pasanganDitanggung
            ? self::bulatkanAtas($gajiPokok * (float) $cfg['persen_tunjangan_pasangan'])
            : 0.0;
        $tunjanganAnak = $jumlahAnak > 0
            ? $jumlahAnak * self::bulatkanAtas($gajiPokok * (float) $cfg['persen_tunjangan_anak'])
            : 0.0;

        $satuanBeras = (float) $cfg['tarif_beras_per_satuan'];
        $tunjanganBeras = $berhakKeluarga
            ? $satuanBeras * (
                (float) $cfg['beras_satuan_pegawai']
                + ($pasanganDitanggung ? (float) $cfg['beras_satuan_pasangan'] : 0)
                + ($jumlahAnak * (float) $cfg['beras_satuan_anak'])
            )
            : 0.0;

        $tunjanganPendidikan = self::bulatkanAtas(2 * (
            $gajiPokok
            + $tunjanganBeras
            + $dasar['tunjangan_fungsional']
            + $tunjanganPasangan
            + $tunjanganAnak
            + $dasar['tunjangan_jabatan']
            + $dasar['tunjangan_profesi']
            + $dasar['tunjangan_makan']
            + (float) $cfg['tambahan_tunjangan_pendidikan']
            + $dasar['tkp']
        ) / 12);

        $dasarPerhitungan = $gajiPokok
            + $tunjanganPasangan
            + $tunjanganAnak
            + $tunjanganBeras
            + $dasar['tunjangan_jabatan']
            + $dasar['tunjangan_fungsional']
            + $dasar['tunjangan_makan']
            + $dasar['tunjangan_profesi']
            + $dasar['tkp']
            + $tunjanganPendidikan;

        $tunjanganBpjsKesehatan = self::bulatkanAtas($dasarPerhitungan * (float) $cfg['persen_bpjs_kesehatan_tunjangan']);
        $tunjanganBpjsKetenagakerjaan = self::bulatkanAtas($dasarPerhitungan * (float) $cfg['persen_bpjs_ketenagakerjaan_tunjangan']);
        $tunjanganDanaPensiun = $berhakKeluarga
            ? self::bulatkanAtas($gajiPokok * (float) $cfg['persen_dana_pensiun_tunjangan'])
            : 0.0;

        // Kelompok 3: kondisi bulan berjalan.
        $tunjanganKehadiran = self::hitungTunjanganKehadiran($rekap, $cfg);
        $uangLembur = self::hitungUangLembur($karyawan, $periode);

        $pendapatan = array_values(array_filter([
            self::baris('Gaji pokok', $gajiPokok, 'Sesuai data dasar aktif'),
            self::baris('Tunjangan jabatan', $dasar['tunjangan_jabatan'], 'Sesuai data dasar'),
            self::baris('Tunjangan fungsional', $dasar['tunjangan_fungsional'], 'Sesuai data dasar'),
            self::baris('Tunjangan profesi', $dasar['tunjangan_profesi'], 'Sesuai data dasar'),
            self::baris('Tunjangan makan', $dasar['tunjangan_makan'], 'Sesuai data dasar'),
            self::baris('TKP', $dasar['tkp'], 'Sesuai data dasar'),
            self::baris('Tunjangan pulsa', $dasar['tunjangan_pulsa'], 'Sesuai data dasar'),
            self::baris('Tunjangan suami/istri', $tunjanganPasangan, '8% x gaji pokok'),
            self::baris('Tunjangan anak', $tunjanganAnak, $jumlahAnak . ' anak x 5% x gaji pokok'),
            self::baris('Tunjangan beras', $tunjanganBeras, '10 + pasangan 8 + 5 per anak, x Rp12.000'),
            self::baris('Tunjangan pendidikan', $tunjanganPendidikan, '2 x (komponen tetap + Rp100.000) / 12'),
            self::baris('Tunjangan dana pensiun', $tunjanganDanaPensiun, '14,5% x gaji pokok (ditanggung perusahaan)'),
            self::baris('Tunjangan BPJS Kesehatan', $tunjanganBpjsKesehatan, '5% x dasar perhitungan (ditanggung perusahaan)'),
            self::baris('Tunjangan BPJS Ketenagakerjaan', $tunjanganBpjsKetenagakerjaan, '6,24% x dasar perhitungan (ditanggung perusahaan)'),
            self::baris('Tunjangan kehadiran (THD)', $tunjanganKehadiran, self::keteranganKehadiran($rekap, $cfg)),
            self::baris('Uang lembur', $uangLembur, 'Rekap lembur disetujui'),
            self::baris('On call', (float) ($rekap->nominal_on_call ?? 0), 'Dari rekap jaga on call'),
            self::baris('Cuti besar', (float) ($rekap->nominal_cuti_besar ?? 0), 'Dibayar bila ada cuti besar'),
            self::baris('Rapel', (float) ($rekap->nominal_rapel ?? 0), 'Selisih gaji masa lalu'),
            self::baris('Insentif hari raya', (float) ($rekap->nominal_insentif_hari_raya ?? 0), 'Sesuai kebijakan perusahaan'),
            self::baris('Pendapatan lain', (float) ($rekap->nominal_pendapatan_lain ?? 0), 'Sesuai kebijakan atau kesepakatan'),
        ], fn ($baris) => $baris !== null));

        $iuranPensiunPegawai = $berhakKeluarga
            ? self::bulatkanAtas($gajiPokok * (float) $cfg['persen_iuran_pensiun_pegawai'])
            : 0.0;

        $potonganResmi = array_values(array_filter([
            self::baris('Pajak penghasilan (PPh 21)', (float) ($rekap->nominal_pph21 ?? 0), 'Diisi sesuai perhitungan pajak'),
            self::baris('BPJS Kesehatan - penyeimbang', $tunjanganBpjsKesehatan, '5% x dasar perhitungan'),
            self::baris('BPJS Ketenagakerjaan - bagian pegawai', self::bulatkanAtas($dasarPerhitungan * (float) $cfg['persen_bpjs_ketenagakerjaan_pegawai']), '3% x dasar perhitungan'),
            self::baris('BPJS Ketenagakerjaan - penyeimbang', $tunjanganBpjsKetenagakerjaan, '6,24% x dasar perhitungan'),
            self::baris('Iuran dana pensiun - bagian pegawai', $iuranPensiunPegawai, '5% x gaji pokok'),
            self::baris('Dana pensiun - penyeimbang', $tunjanganDanaPensiun, '14,5% x gaji pokok'),
        ]));

        $infaq = $rekap && $rekap->potongan_infaq !== null
            ? (float) $rekap->potongan_infaq
            : self::bulatkanAtas($dasarPerhitungan * (float) $cfg['persen_infaq']);

        $potonganLain = array_values(array_filter([
            self::baris('Infaq', $infaq, '2,5% x dasar perhitungan'),
            self::baris('Angsuran pinjaman bank konvensional', (float) ($rekap->potongan_angsuran_pinjaman ?? 0), 'Sesuai sisa angsuran bulan ini'),
            self::baris('Angsuran bank syariah', (float) ($rekap->potongan_bank_syariah ?? 0), 'Sesuai sisa angsuran bulan ini'),
            self::baris('Potongan koperasi / barokah', (float) ($rekap->potongan_koperasi ?? 0), 'Sesuai kesepakatan'),
            self::baris('Farmasi', (float) ($rekap->potongan_farmasi ?? 0), 'Sesuai pemakaian atau kesepakatan'),
            self::baris('BSI / Al Ikhlas', (float) ($rekap->potongan_bsi ?? 0), 'Sesuai kesepakatan'),
            self::baris('Perumahan', (float) ($rekap->potongan_perumahan ?? 0), 'Sesuai kesepakatan'),
            self::baris('BSM', (float) ($rekap->potongan_bsm ?? 0), 'Sesuai kesepakatan'),
            self::baris('Lain-lain', (float) ($rekap->potongan_lain ?? 0), 'Sesuai kesepakatan'),
        ]));

        $totalPendapatan = self::jumlah($pendapatan);
        $totalPotonganResmi = self::jumlah($potonganResmi);
        $totalPotonganLain = self::jumlah($potonganLain);

        // Usulan PPh 21 (admin HR tetap mengisi nominal final di rekap bulanan).
        $usulanPph21 = self::usulanPph21(
            $totalPendapatan,
            $iuranPensiunPegawai,
            $pasanganDitanggung,
            $jumlahAnak,
            $cfg
        );

        return [
            'periode' => $periode,
            'label_periode' => optional($rekap)->labelPeriode() ?? self::labelPeriode($periode),
            'data_dasar' => $dasar,
            'dasar_perhitungan' => $dasarPerhitungan,
            'berhak_tunjangan_keluarga' => $berhakKeluarga,
            'pasangan_ditanggung' => $pasanganDitanggung,
            'jumlah_anak_ditanggung' => $jumlahAnak,
            'pendapatan' => $pendapatan,
            'potongan_resmi' => $potonganResmi,
            'potongan_lain' => $potonganLain,
            'total_pendapatan' => $totalPendapatan,
            'total_potongan_resmi' => $totalPotonganResmi,
            'total_potongan_lain' => $totalPotonganLain,
            'gaji_bersih' => $totalPendapatan - $totalPotonganResmi,
            'terima_bersih' => $totalPendapatan - $totalPotonganResmi - $totalPotonganLain,
            'biaya_jabatan' => self::bulatkanAtas($totalPendapatan * (float) $cfg['persen_biaya_jabatan']),
            'usulan_pph21' => $usulanPph21,
            'uang_lembur' => $uangLembur,
            'tunjangan_kehadiran' => $tunjanganKehadiran,
            'infaq' => $infaq,
            'rekap' => $rekap,
        ];
    }

    /**
     * Usulan PPh 21 tahunan disetahunkan lalu dibagi 12 (metode setahun).
     * Hasil hanya sebagai rekomendasi; nominal final tetap diisi di rekap bulanan.
     *
     * @return array{pph_setahun:float,pph_sebulan:float,bruto_setahun:float,biaya_jabatan:float,iuran_pensiun_setahun:float,ptkp:float,pkp:float}
     */
    public static function usulanPph21(
        float $brutoSebulan,
        float $iuranPensiunSebulan,
        bool $pasanganDitanggung,
        int $jumlahAnak,
        ?array $cfg = null
    ): array {
        $cfg = $cfg ?? self::konfigurasi();
        $ptkpCfg = $cfg['ptkp'] ?? [];

        $brutoSetahun = $brutoSebulan * 12;
        $iuranPensiunSetahun = $iuranPensiunSebulan * 12;

        $biayaJabatan = min(
            $brutoSetahun * (float) ($cfg['persen_biaya_jabatan'] ?? 0.05),
            (float) ($cfg['maks_biaya_jabatan_setahun'] ?? 6_000_000)
        );

        $nettoSetahun = max(0.0, $brutoSetahun - $biayaJabatan - $iuranPensiunSetahun);

        $ptkp = (float) ($ptkpCfg['tk0'] ?? 54_000_000);
        if ($pasanganDitanggung) {
            $ptkp += (float) ($ptkpCfg['tambahan_kawin'] ?? 0);
        }
        $tanggungan = min($jumlahAnak, (int) ($ptkpCfg['maks_tanggungan'] ?? 3));
        $ptkp += $tanggungan * (float) ($ptkpCfg['tambahan_tanggungan'] ?? 0);

        // PKP dibulatkan ke bawah ke ribuan penuh.
        $pkp = max(0.0, floor(($nettoSetahun - $ptkp) / 1000) * 1000);

        $pphSetahun = self::hitungLapisan($pkp, $cfg['pph21_lapisan'] ?? []);

        return [
            'pph_setahun' => self::bulatkanAtas($pphSetahun),
            'pph_sebulan' => self::bulatkanAtas($pphSetahun / 12),
            'bruto_setahun' => $brutoSetahun,
            'biaya_jabatan' => self::bulatkanAtas($biayaJabatan),
            'iuran_pensiun_setahun' => $iuranPensiunSetahun,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
        ];
    }

    /**
     * Hitung PPh progresif per lapisan tarif.
     */
    protected static function hitungLapisan(float $pkp, array $lapisan): float
    {
        $pajak = 0.0;
        $batasBawah = 0.0;

        foreach ($lapisan as $layer) {
            $batas = $layer['batas'];
            $tarif = (float) $layer['tarif'];

            if ($pkp <= $batasBawah) {
                break;
            }

            $kena = ($batas === null ? $pkp : min($pkp, (float) $batas)) - $batasBawah;
            $pajak += $kena * $tarif;

            $batasBawah = (float) ($batas ?? $pkp);
        }

        return $pajak;
    }

    /**
     * THD: tarif harian per jenis shift, dikali dua untuk koordinator,
     * dan menjadi 0 bila keterlambatan + pulang awal mencapai batas.
     */
    protected static function hitungTunjanganKehadiran(?EmployeeMonthlyPayroll $rekap, array $cfg): float
    {
        if (!$rekap) {
            return 0.0;
        }

        if ((int) $rekap->hari_telat_pulang_awal >= (int) $cfg['thd_batas_hari_telat_pulang_awal']) {
            return 0.0;
        }

        $thd = ((int) $rekap->hari_pagi * (float) $cfg['tarif_thd_pagi'])
            + ((int) $rekap->hari_siang * (float) $cfg['tarif_thd_siang'])
            + ((int) $rekap->hari_malam * (float) $cfg['tarif_thd_malam']);

        if ($rekap->is_koordinator) {
            $thd *= (float) $cfg['thd_pengali_koordinator'];
        }

        return $thd;
    }

    protected static function keteranganKehadiran(?EmployeeMonthlyPayroll $rekap, array $cfg): string
    {
        if (!$rekap) {
            return 'Belum ada rekap kehadiran bulan ini';
        }

        if ((int) $rekap->hari_telat_pulang_awal >= (int) $cfg['thd_batas_hari_telat_pulang_awal']) {
            return 'Nol karena terlambat/pulang awal ' . $rekap->hari_telat_pulang_awal . ' hari';
        }

        $keterangan = $rekap->hari_pagi . ' pagi, ' . $rekap->hari_siang . ' siang, ' . $rekap->hari_malam . ' malam';

        return $rekap->is_koordinator ? $keterangan . ' (koordinator, 2x)' : $keterangan;
    }

    /**
     * Uang lembur diambil dari lembur yang sudah disetujui pada periode tersebut.
     */
    public static function hitungUangLembur(Karyawan $karyawan, string $periode): float
    {
        [$tahun, $bulan] = array_pad(explode('-', $periode), 2, '1');

        return (float) Overtime::where('karyawan_id', $karyawan->id)
            ->where('status', 'disetujui')
            ->whereYear('tanggal', (int) $tahun)
            ->whereMonth('tanggal', (int) $bulan)
            ->sum('nominal_lembur');
    }

    public static function labelPeriode(string $periode): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        [$tahun, $angkaBulan] = array_pad(explode('-', $periode), 2, '01');

        return ($bulan[(int) $angkaBulan] ?? $angkaBulan) . ' ' . $tahun;
    }

    /**
     * Panduan membulatkan hasil persentase ke atas pada satuan rupiah,
     * sehingga slip selalu dapat dicocokkan baris per baris.
     */
    protected static function bulatkanAtas(float $nilai): float
    {
        return ceil(round($nilai, 4));
    }

    protected static function baris(string $label, float $nilai, string $keterangan = ''): ?array
    {
        if (abs($nilai) < 0.005) {
            return null;
        }

        return ['label' => $label, 'nilai' => $nilai, 'keterangan' => $keterangan];
    }

    protected static function jumlah(array $baris): float
    {
        return array_reduce($baris, fn ($total, $item) => $total + $item['nilai'], 0.0);
    }
}
