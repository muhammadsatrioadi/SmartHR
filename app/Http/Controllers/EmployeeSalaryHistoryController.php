<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSalaryHistory;
use App\Models\Karyawan;
use App\Models\SalaryStep;
use App\Support\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryHistoryController extends Controller
{
    public function index()
    {
        $histories = EmployeeSalaryHistory::with(['karyawan', 'salaryStep'])
            ->orderByDesc('tanggal_berlaku')
            ->paginate(15);
        $histories->getCollection()->transform(function ($history) {
            $components = self::komponenLembur($history);
            $history->dasar_upah_lembur = PayrollCalculator::calculateOvertimeBase($components);
            $history->upah_per_jam = PayrollCalculator::calculateHourlyWage($components);
            return $history;
        });
        return view('salary_histories.index', compact('histories'));
    }

    public function create()
    {
        $karyawans = Karyawan::orderBy('name')->get();
        $steps = SalaryStep::orderBy('kode')->get();
        return view('salary_histories.create', compact('karyawans', 'steps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'salary_step_id' => 'nullable|exists:salary_steps,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_fungsional' => 'nullable|numeric|min:0',
            'tunjangan_profesi' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_pulsa' => 'nullable|numeric|min:0',
            'tkp' => 'nullable|numeric|min:0',
            'pasangan_ditanggung' => 'nullable|boolean',
            'jumlah_anak_ditanggung' => 'nullable|integer|min:0|max:10',
            'tunjangan_lain' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu' => 'required|in:5,6',
            'tanggal_berlaku' => 'required|date',
            'alasan' => 'nullable|string|max:150',
        ]);

        $rinci = ['tunjangan_jabatan', 'tunjangan_fungsional', 'tunjangan_profesi', 'tunjangan_makan', 'tunjangan_pulsa', 'tkp'];
        $totalRinci = 0;
        foreach ($rinci as $kolom) {
            $validated[$kolom] = (float) ($validated[$kolom] ?? 0);
            $totalRinci += $validated[$kolom];
        }

        // Kolom tunjangan_tetap lama diisi dari jumlah komponen rinci agar modul
        // lain (lembur & estimasi) tetap mendapat angka tunjangan yang benar.
        $validated['tunjangan_tetap'] = $totalRinci;
        $validated['tunjangan_lain'] = $validated['tunjangan_lain'] ?? 0;
        $validated['pasangan_ditanggung'] = $request->boolean('pasangan_ditanggung');
        $validated['jumlah_anak_ditanggung'] = (int) ($validated['jumlah_anak_ditanggung'] ?? 0);
        $validated['disetujui_oleh'] = Auth::id();

        EmployeeSalaryHistory::create($validated);

        return redirect()->route('salaryHistory.index')->with('success', 'Data gaji dasar pegawai berhasil ditambahkan.');
    }

    /**
     * Komponen untuk perhitungan upah lembur memakai komponen rinci bila ada,
     * atau kolom lama sebagai cadangan.
     */
    public static function komponenLembur(EmployeeSalaryHistory $history): array
    {
        return [
            'gaji_pokok' => $history->gaji_pokok,
            'tunjangan_tetap' => $history->tunjanganTetapTotal(),
            'tunjangan_lain' => $history->tunjangan_lain,
        ];
    }
}
