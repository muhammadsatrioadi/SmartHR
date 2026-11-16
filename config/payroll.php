<?php

/**
 * Parameter estimasi potongan wajib & PPh untuk ringkasan payroll.
 * Nilai plafon dan tarif berubah mengikuti kebijakan pemerintah — sesuaikan secara berkala.
 */
return [
    'bpjs_kesehatan' => [
        'iuran_pekerja_persen' => 0.01,
        'dasar_perhitungan_maks' => 12_000_000.0,
    ],
    'bpjs_ketenagakerjaan' => [
        'jht_pekerja_persen' => 0.02,
        'jp_pekerja_persen' => 0.01,
        'dasar_iuran_maks' => 10_547_400.0,
    ],
    'pph21' => [
        /**
         * none = tidak diestimasi (disarankan hubungkan ke perhitungan TER resmi).
         * flat_percent = terapkan persen flat ke penghasilan neto setelah iuran pekerja.
         */
        'mode' => env('PAYROLL_PPH21_MODE', 'none'),
        'flat_percent' => (float) env('PAYROLL_PPH21_FLAT_PERCENT', 0),
    ],

    /**
     * Parameter perhitungan slip gaji mengikuti "Panduan Membaca Komponen Gaji".
     * Semua persentase dan tarif di bawah ini mengacu pada panduan tersebut.
     */
    'panduan' => [
        'tarif_beras_per_satuan' => 12_000.0,
        'beras_satuan_pegawai' => 10,
        'beras_satuan_pasangan' => 8,
        'beras_satuan_anak' => 5,

        'tarif_thd_pagi' => 32_500.0,
        'tarif_thd_siang' => 32_500.0,
        'tarif_thd_malam' => 37_500.0,
        'thd_batas_hari_telat_pulang_awal' => 11,
        'thd_pengali_koordinator' => 2,

        'persen_tunjangan_pasangan' => 0.08,
        'persen_tunjangan_anak' => 0.05,
        'maks_anak' => 3,
        'tambahan_tunjangan_pendidikan' => 100_000.0,

        'persen_dana_pensiun_tunjangan' => 0.145,
        'persen_bpjs_kesehatan_tunjangan' => 0.05,
        'persen_bpjs_ketenagakerjaan_tunjangan' => 0.0624,
        'persen_bpjs_ketenagakerjaan_pegawai' => 0.03,
        'persen_iuran_pensiun_pegawai' => 0.05,
        'persen_infaq' => 0.025,
        'persen_biaya_jabatan' => 0.05,

        /**
         * Status kepegawaian (dicocokkan ke kode/nama employee_statuses) yang tidak
         * memperoleh tunjangan keluarga dan dana pensiun, misalnya peserta magang.
         */
        'status_tanpa_tunjangan_keluarga' => ['magang', 'intern', 'peserta'],
    ],
];
