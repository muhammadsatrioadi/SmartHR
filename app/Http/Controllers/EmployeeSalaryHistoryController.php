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
            $components = [
                'gaji_pokok' => $history->gaji_pokok,
                'tunjangan_tetap' => $history->tunjangan_tetap,
                'tunjangan_lain' => $history->tunjangan_lain,
            ];
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
            'tunjangan_tetap' => 'nullable|numeric|min:0',
            'tunjangan_lain' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu' => 'required|in:5,6',
            'tanggal_berlaku' => 'required|date',
            'alasan' => 'nullable|string|max:150',
        ]);

        $validated['tunjangan_tetap'] = $validated['tunjangan_tetap'] ?? 0;
        $validated['tunjangan_lain'] = $validated['tunjangan_lain'] ?? 0;
        $validated['disetujui_oleh'] = Auth::id();

        EmployeeSalaryHistory::create($validated);

        return redirect()->route('salaryHistory.index')->with('success', 'Riwayat gaji berkala berhasil ditambahkan.');
    }
}
