<?php

namespace App\Support;

/**
 * Estimasi komponen potongan wajib (BPJS) dan opsi PPh sederhana untuk tampilan ringkasan.
 * Bukan pengganti konsultan pajak atau payroll bersertifikat.
 */
class StatutoryPayrollEstimator
{
    public static function estimateMonthly(
        float $gajiPokok,
        float $tunjanganTetap,
        float $tunjanganLain,
        float $lembur,
        float $potonganPerusahaanInternal = 0.0
    ): array {
        $bruto = max(0.0, $gajiPokok + $tunjanganTetap + $tunjanganLain + $lembur);

        $cfgKes = config('payroll.bpjs_kesehatan', []);
        $dasarKes = min($bruto, (float) ($cfgKes['dasar_perhitungan_maks'] ?? 12_000_000));
        $bpjsKesehatan = round($dasarKes * (float) ($cfgKes['iuran_pekerja_persen'] ?? 0.01), 2);

        $cfgTk = config('payroll.bpjs_ketenagakerjaan', []);
        $dasarTk = min($bruto, (float) ($cfgTk['dasar_iuran_maks'] ?? 10_547_400));
        $bpjsJht = round($dasarTk * (float) ($cfgTk['jht_pekerja_persen'] ?? 0.02), 2);
        $bpjsJp = round($dasarTk * (float) ($cfgTk['jp_pekerja_persen'] ?? 0.01), 2);

        $totalIuranPekerja = $bpjsKesehatan + $bpjsJht + $bpjsJp;
        $penghasilanNetoSetelahIuran = max(0.0, $bruto - $totalIuranPekerja);

        $pphCfg = config('payroll.pph21', []);
        $pphMode = $pphCfg['mode'] ?? 'none';
        $pphFlat = (float) ($pphCfg['flat_percent'] ?? 0);
        $pph21 = 0.0;
        if ($pphMode === 'flat_percent' && $pphFlat > 0) {
            $pph21 = round($penghasilanNetoSetelahIuran * ($pphFlat / 100), 2);
        }

        $totalPotonganWajib = $totalIuranPekerja + $pph21;
        $thp = $bruto - $potonganPerusahaanInternal - $totalPotonganWajib;

        return [
            'bruto_bulanan' => round($bruto, 2),
            'bpjs_kesehatan_pekerja' => $bpjsKesehatan,
            'bpjs_jht_pekerja' => $bpjsJht,
            'bpjs_jp_pekerja' => $bpjsJp,
            'total_iuran_bpjs_pekerja' => round($totalIuranPekerja, 2),
            'pph21_perkiraan' => $pph21,
            'total_potongan_wajib_perkiraan' => round($totalPotonganWajib, 2),
            'potongan_perusahaan_internal' => round(max(0.0, $potonganPerusahaanInternal), 2),
            'thp_perkiraan' => round($thp, 2),
        ];
    }
}
