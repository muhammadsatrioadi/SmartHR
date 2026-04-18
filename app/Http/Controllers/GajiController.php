<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Support\PayrollCalculator;
use App\Support\StatutoryPayrollEstimator;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        $gajis = Gaji::with(['karyawan.jabatan', 'karyawan.salaryHistories', 'karyawan.overtimes'])->get();

        $gajis = $gajis->map(function ($gaji) {
            $components = PayrollCalculator::resolveSalaryComponents($gaji->karyawan);
            $gaji->gaji_pokok_aktif = $components['gaji_pokok'];
            $gaji->tunjangan_tetap_aktif = $components['tunjangan_tetap'];
            $gaji->tunjangan_lain_aktif = $components['tunjangan_lain'];
            $gaji->hari_kerja_per_minggu = $components['hari_kerja_per_minggu'];
            $gaji->lembur_bulan_ini = $this->calculateApprovedOvertime($gaji);
            $gaji->total_potongan = $this->calculateTotalPotongan($gaji);
            $gaji->total_tunjangan = $this->calculateTotalTunjangan($gaji);
            $gaji->total_gaji = $this->calculateTotalGaji($gaji);

            $est = StatutoryPayrollEstimator::estimateMonthly(
                $gaji->gaji_pokok_aktif,
                $gaji->tunjangan_tetap_aktif,
                $gaji->tunjangan_lain_aktif,
                $gaji->lembur_bulan_ini,
                $gaji->total_potongan
            );
            $gaji->bruto_bulanan = $est['bruto_bulanan'];
            $gaji->perkiraan_bpjs_kesehatan = $est['bpjs_kesehatan_pekerja'];
            $gaji->perkiraan_bpjs_jht = $est['bpjs_jht_pekerja'];
            $gaji->perkiraan_bpjs_jp = $est['bpjs_jp_pekerja'];
            $gaji->perkiraan_total_iuran_bpjs = $est['total_iuran_bpjs_pekerja'];
            $gaji->perkiraan_pph21 = $est['pph21_perkiraan'];
            $gaji->perkiraan_potongan_wajib = $est['total_potongan_wajib_perkiraan'];
            $gaji->thp_perkiraan = $est['thp_perkiraan'];

            return $gaji;
        });

        return view('gaji.index', compact('gajis'));
    }

    private function calculateTotalPotongan($gaji) {
        return $gaji->karyawan->total_kontak * $gaji->karyawan->jabatan->potongan;
    }

    private function calculateTotalTunjangan($gaji) {
        return $gaji->tunjangan_tetap_aktif + $gaji->tunjangan_lain_aktif + $gaji->lembur_bulan_ini;
    }

    private function calculateTotalGaji($gaji) {
        $gajiPokok = $gaji->gaji_pokok_aktif;
        $tunjangan = $gaji->tunjangan_tetap_aktif + $gaji->tunjangan_lain_aktif + $gaji->lembur_bulan_ini;
        $totalPotongan = $this->calculateTotalPotongan($gaji);

        return ($gajiPokok + $tunjangan) - $totalPotongan;
    }

    private function calculateApprovedOvertime($gaji)
    {
        return $gaji->karyawan->overtimes
            ->where('status', 'disetujui')
            ->filter(function ($overtime) use ($gaji) {
                return $overtime->tanggal !== null
                    && $overtime->tanggal->format('Y-m') === $gaji->created_at->format('Y-m');
            })
            ->sum('nominal_lembur');
    }
}
