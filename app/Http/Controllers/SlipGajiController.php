<?php

namespace App\Http\Controllers;

use App\Models\EmployeeMonthlyPayroll;
use App\Models\Karyawan;
use App\Support\ActivityLogger;
use App\Support\PanduanGajiCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $rekap = $this->rekap($karyawan, $periode);
        $slip = PanduanGajiCalculator::slip($karyawan, $periode, $rekap);

        return view('gaji.slip_show', compact('karyawan', 'rekap', 'slip', 'periode'));
    }

    /**
     * Slip siap cetak (print-friendly). Admin HR boleh semua pegawai,
     * sedangkan pegawai lain hanya boleh mencetak slip-nya sendiri.
     */
    public function cetak(Request $request, Karyawan $karyawan)
    {
        $this->otorisasiSlip($karyawan);

        $periode = $this->resolvePeriode($request->query('periode'));

        $karyawan->load(['jabatan', 'department', 'employeeStatus', 'salaryHistories']);

        $rekap = $this->rekap($karyawan, $periode);
        $slip = PanduanGajiCalculator::slip($karyawan, $periode, $rekap);

        ActivityLogger::catat('slip_gaji', 'cetak', [
            'referensi' => 'Slip ' . $periode . ' karyawan #' . $karyawan->id,
            'karyawan_id' => $karyawan->id,
            'keterangan' => 'Slip gaji dicetak/diekspor',
        ]);

        return view('gaji.slip_cetak', compact('karyawan', 'rekap', 'slip', 'periode'));
    }

    /**
     * Cegah pegawai melihat slip pegawai lain kecuali Admin HR.
     */
    private function otorisasiSlip(Karyawan $karyawan): void
    {
        $user = Auth::user();

        abort_unless($user, 403, 'Anda harus masuk terlebih dahulu.');

        if ($user->role === 'admin_hr') {
            return;
        }

        abort_unless($karyawan->email && $karyawan->email === $user->email, 403, 'Anda tidak berhak melihat slip gaji pegawai lain.');
    }

    private function rekap(Karyawan $karyawan, string $periode): ?EmployeeMonthlyPayroll
    {
        return EmployeeMonthlyPayroll::where('karyawan_id', $karyawan->id)
            ->where('periode', $periode)
            ->first();
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
