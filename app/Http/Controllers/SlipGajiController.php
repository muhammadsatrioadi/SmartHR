<?php

namespace App\Http\Controllers;

use App\Models\EmployeeMonthlyPayroll;
use App\Models\Karyawan;
use App\Support\PanduanGajiCalculator;
use Illuminate\Http\Request;

class SlipGajiController extends Controller
{
    /**
     * Daftar slip gaji seluruh pegawai untuk satu periode.
     */
    public function index(Request $request)
    {
        $periode = $this->resolvePeriode($request->query('periode'));

        $karyawans = Karyawan::with([
            'jabatan',
            'department',
            'employeeStatus',
            'salaryHistories',
            'monthlyPayrolls' => fn ($q) => $q->where('periode', $periode),
        ])->orderBy('name')->get();

        $slips = $karyawans->map(fn ($karyawan) => [
            'karyawan' => $karyawan,
            'rekap' => $karyawan->monthlyPayrolls->first(),
            'slip' => PanduanGajiCalculator::slip($karyawan, $periode, $karyawan->monthlyPayrolls->first()),
        ]);

        return view('gaji.slip_index', compact('slips', 'periode'));
    }

    /**
     * Slip gaji satu pegawai pada periode terpilih.
     */
    public function show(Request $request, Karyawan $karyawan)
    {
        $periode = $this->resolvePeriode($request->query('periode'));

        $karyawan->load(['jabatan', 'department', 'employeeStatus', 'salaryHistories']);

        $rekap = EmployeeMonthlyPayroll::where('karyawan_id', $karyawan->id)
            ->where('periode', $periode)
            ->first();

        $slip = PanduanGajiCalculator::slip($karyawan, $periode, $rekap);

        return view('gaji.slip_show', compact('karyawan', 'rekap', 'slip', 'periode'));
    }

    /**
     * Pastikan periode berformat YYYY-MM; bila tidak valid dipakai bulan berjalan.
     */
    private function resolvePeriode(?string $periode): string
    {
        if (is_string($periode) && preg_match('/^\d{4}-\d{2}$/', $periode)) {
            return $periode;
        }

        return now('Asia/Jakarta')->format('Y-m');
    }
}
