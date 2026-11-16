<?php

namespace App\Http\Controllers;

use App\Models\EmployeeMonthlyPayroll;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMonthlyPayrollController extends Controller
{
    /**
     * Daftar rekap gaji bulanan per pegawai.
     */
    public function index(Request $request)
    {
        $periode = $request->query('periode');

        $rekap = EmployeeMonthlyPayroll::with(['karyawan', 'penyusun'])
            ->when($periode, fn ($q) => $q->where('periode', $periode))
            ->orderByDesc('periode')
            ->orderBy('karyawan_id')
            ->paginate(20)
            ->withQueryString();

        $periodes = EmployeeMonthlyPayroll::query()
            ->select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');

        return view('payroll_monthly.index', compact('rekap', 'periodes', 'periode'));
    }

    public function create(Request $request)
    {
        $karyawans = Karyawan::with(['jabatan', 'employeeStatus', 'salaryHistories'])
            ->orderBy('name')
            ->get();

        $selected = $request->query('karyawan_id');
        $periode = $request->query('periode', now('Asia/Jakarta')->format('Y-m'));

        return view('payroll_monthly.create', compact('karyawans', 'selected', 'periode'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        EmployeeMonthlyPayroll::updateOrCreate(
            ['karyawan_id' => $validated['karyawan_id'], 'periode' => $validated['periode']],
            array_merge($validated, ['disiapkan_oleh' => Auth::id()])
        );

        return redirect()->route('payrollMonthly.index', ['periode' => $validated['periode']])
            ->with('success', 'Rekap gaji bulanan berhasil disimpan.');
    }

    public function edit(EmployeeMonthlyPayroll $payrollMonthly)
    {
        $karyawans = Karyawan::with(['jabatan', 'employeeStatus', 'salaryHistories'])
            ->orderBy('name')
            ->get();

        $rekap = $payrollMonthly;
        $selected = $rekap->karyawan_id;
        $periode = $rekap->periode;

        return view('payroll_monthly.edit', compact('rekap', 'karyawans', 'selected', 'periode'));
    }

    public function update(Request $request, EmployeeMonthlyPayroll $payrollMonthly)
    {
        $validated = $this->validasi($request);

        $payrollMonthly->update($validated);

        return redirect()->route('payrollMonthly.index', ['periode' => $payrollMonthly->periode])
            ->with('success', 'Rekap gaji bulanan berhasil diperbarui.');
    }

    public function destroy(EmployeeMonthlyPayroll $payrollMonthly)
    {
        $periode = $payrollMonthly->periode;
        $payrollMonthly->delete();

        return redirect()->route('payrollMonthly.index', ['periode' => $periode])
            ->with('success', 'Rekap gaji bulanan berhasil dihapus.');
    }

    /**
     * Parameter validasi rekap bulanan; aturan periode unik per pegawai
     * mengikuti model agar updateOrCreate tidak menggandakan baris.
     */
    private function validasi(Request $request): array
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'hari_pagi' => 'nullable|integer|min:0|max:31',
            'hari_siang' => 'nullable|integer|min:0|max:31',
            'hari_malam' => 'nullable|integer|min:0|max:31',
            'hari_telat_pulang_awal' => 'nullable|integer|min:0|max:31',
            'is_koordinator' => 'nullable|boolean',
            'nominal_on_call' => 'nullable|numeric|min:0',
            'nominal_cuti_besar' => 'nullable|numeric|min:0',
            'nominal_rapel' => 'nullable|numeric|min:0',
            'nominal_insentif_hari_raya' => 'nullable|numeric|min:0',
            'nominal_pendapatan_lain' => 'nullable|numeric|min:0',
            'nominal_pph21' => 'nullable|numeric|min:0',
            'potongan_infaq' => 'nullable|numeric|min:0',
            'potongan_angsuran_pinjaman' => 'nullable|numeric|min:0',
            'potongan_bank_syariah' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'potongan_farmasi' => 'nullable|numeric|min:0',
            'potongan_bsi' => 'nullable|numeric|min:0',
            'potongan_perumahan' => 'nullable|numeric|min:0',
            'potongan_bsm' => 'nullable|numeric|min:0',
            'potongan_lain' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        $angka = [
            'hari_pagi', 'hari_siang', 'hari_malam', 'hari_telat_pulang_awal',
            'nominal_on_call', 'nominal_cuti_besar', 'nominal_rapel',
            'nominal_insentif_hari_raya', 'nominal_pendapatan_lain', 'nominal_pph21',
            'potongan_angsuran_pinjaman', 'potongan_bank_syariah', 'potongan_koperasi',
            'potongan_farmasi', 'potongan_bsi', 'potongan_perumahan', 'potongan_bsm',
            'potongan_lain',
        ];
        foreach ($angka as $kolom) {
            $validated[$kolom] = (float) ($validated[$kolom] ?? 0);
        }

        // Infaq dikosongkan berarti dihitung otomatis 2,5% x dasar perhitungan.
        $validated['potongan_infaq'] = $request->filled('potongan_infaq')
            ? (float) $validated['potongan_infaq']
            : null;

        $validated['is_koordinator'] = $request->boolean('is_koordinator');

        return $validated;
    }
}
