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
];
