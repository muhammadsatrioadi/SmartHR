<?php

namespace App\Http\Controllers;

use App\Models\SalaryStep;
use App\Support\PayrollCalculator;
use Illuminate\Http\Request;

class SalaryStepController extends Controller
{
    public function index()
    {
        $steps = SalaryStep::orderBy('kode')->paginate(10);
        $steps->getCollection()->transform(function ($step) {
            $components = [
                'gaji_pokok' => $step->gaji_pokok,
                'tunjangan_tetap' => $step->tunjangan_tetap,
                'tunjangan_lain' => 0,
            ];
            $step->dasar_upah_lembur = PayrollCalculator::calculateOvertimeBase($components);
            $step->upah_per_jam = PayrollCalculator::calculateHourlyWage($components);
            return $step;
        });
        return view('salary_steps.index', compact('steps'));
    }

    public function create()
    {
        return view('salary_steps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:salary_steps,kode',
            'deskripsi' => 'nullable|string|max:150',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_tetap' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu' => 'required|in:5,6',
            'masa_kerja_min' => 'required|integer|min:0',
            'masa_kerja_maks' => 'nullable|integer|min:0',
        ]);

        $validated['tunjangan_tetap'] = $validated['tunjangan_tetap'] ?? 0;
        SalaryStep::create($validated);
        return redirect()->route('salaryStep.index')->with('success', 'Skala gaji berhasil dibuat.');
    }

    public function edit($id)
    {
        $step = SalaryStep::findOrFail($id);
        return view('salary_steps.edit', compact('step'));
    }

    public function update(Request $request, $id)
    {
        $step = SalaryStep::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:salary_steps,kode,' . $step->id,
            'deskripsi' => 'nullable|string|max:150',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_tetap' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu' => 'required|in:5,6',
            'masa_kerja_min' => 'required|integer|min:0',
            'masa_kerja_maks' => 'nullable|integer|min:0',
        ]);

        $validated['tunjangan_tetap'] = $validated['tunjangan_tetap'] ?? 0;
        $step->update($validated);
        return redirect()->route('salaryStep.index')->with('success', 'Skala gaji berhasil diperbarui.');
    }

    public function destroy($id)
    {
        SalaryStep::whereKey($id)->delete();
        return redirect()->route('salaryStep.index')->with('success', 'Skala gaji berhasil dihapus.');
    }
}
