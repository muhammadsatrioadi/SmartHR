<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\Karyawan;
use App\Support\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = Overtime::with('karyawan')->orderByDesc('tanggal')->paginate(10);
        return view('overtime.index', compact('overtimes'));
    }

    public function create()
    {
        $karyawans = Karyawan::with(['jabatan', 'salaryHistories' => function ($query) {
            $query->orderByDesc('tanggal_berlaku')->orderByDesc('id');
        }])->orderBy('name')->get();
        return view('overtime.create', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jenis_hari' => 'required|in:hari_kerja,hari_libur,hari_raya',
            'is_hari_kerja_terpendek' => 'nullable|boolean',
            'keterangan_pekerjaan' => 'required|string|max:255',
            'pegawai_telah_menyetujui' => 'required|accepted',
            'referensi_persetujuan_pegawai' => 'nullable|string|max:255',
        ]);

        $mulai = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $selesai = Carbon::createFromFormat('H:i', $validated['jam_selesai']);
        $validated['jumlah_jam'] = round($mulai->floatDiffInHours($selesai), 2);
        $validated['is_hari_kerja_terpendek'] = (bool) ($validated['is_hari_kerja_terpendek'] ?? false);
        $validated['pegawai_telah_menyetujui'] = true;

        $karyawan = Karyawan::with(['jabatan', 'salaryHistories' => function ($query) {
            $query->orderByDesc('tanggal_berlaku')->orderByDesc('id');
        }])->findOrFail($validated['karyawan_id']);

        $components = PayrollCalculator::resolveSalaryComponents($karyawan);
        $validated['hari_kerja_per_minggu'] = $components['hari_kerja_per_minggu'];

        $maxHours = PayrollCalculator::maxOvertimeHours(
            $validated['jenis_hari'],
            $validated['hari_kerja_per_minggu'],
            $validated['is_hari_kerja_terpendek']
        );

        if ($validated['jumlah_jam'] > $maxHours) {
            return back()
                ->withInput()
                ->withErrors([
                    'jam_selesai' => 'Durasi lembur melebihi batas maksimal ' . rtrim(rtrim(number_format($maxHours, 2, '.', ''), '0'), '.') . ' jam untuk jenis hari yang dipilih.',
                ]);
        }

        $tanggalLembur = Carbon::parse($validated['tanggal']);
        $jamMingguIni = PayrollCalculator::overtimeHoursInWeek((int) $validated['karyawan_id'], $tanggalLembur);
        if ($jamMingguIni + $validated['jumlah_jam'] > PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK + 0.0001) {
            $sisa = max(0, PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK - $jamMingguIni);

            return back()
                ->withInput()
                ->withErrors([
                    'tanggal' => 'Total lembur pegawai dalam minggu yang sama (Senin–Minggu) tidak boleh melebihi '
                        . (int) PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK
                        . ' jam menurut ketentuan umum. Sisa kuota minggu ini sekitar '
                        . rtrim(rtrim(number_format($sisa, 2, '.', ''), '0'), '.')
                        . ' jam.',
                ]);
        }

        $overtimePay = PayrollCalculator::calculateOvertimePay(
            $validated['jumlah_jam'],
            $validated['jenis_hari'],
            $validated['hari_kerja_per_minggu'],
            $validated['is_hari_kerja_terpendek'],
            $components
        );

        $validated = array_merge($validated, $overtimePay);

        Overtime::create($validated);

        return redirect()->route('overtime.index')->with('success', 'Order lembur berhasil dibuat dan nominal lembur dihitung otomatis.');
    }

    public function approveSupervisor($id)
    {
        $ot = Overtime::findOrFail($id);
        if (Auth::user()->role !== 'atasan') {
            return back()->with('error', 'Hanya atasan yang dapat menyetujui lembur sebagai atasan.');
        }
        if ($ot->status !== 'menunggu_approval') {
            return back()->with('error', 'Status lembur tidak valid untuk persetujuan atasan.');
        }
        if (!$this->weeklyOvertimeQuotaAllows($ot)) {
            return back()->with('error', $this->weeklyQuotaErrorMessage($ot));
        }
        $ot->update([
            'status' => 'disetujui_atasan',
            'approved_by_supervisor_id' => Auth::id(),
            'approved_at_supervisor' => now(),
        ]);
        return back()->with('success', 'Lembur disetujui atasan.');
    }

    public function approveHr($id)
    {
        $ot = Overtime::findOrFail($id);
        if (Auth::user()->role !== 'admin_hr') {
            return back()->with('error', 'Hanya HR yang dapat menyetujui lembur sebagai HR.');
        }
        if (!in_array($ot->status, ['menunggu_approval', 'disetujui_atasan'])) {
            return back()->with('error', 'Status lembur tidak valid untuk persetujuan HR.');
        }
        if (!$this->weeklyOvertimeQuotaAllows($ot)) {
            return back()->with('error', $this->weeklyQuotaErrorMessage($ot));
        }
        $ot->update([
            'status' => 'disetujui',
            'approved_by_hr_id' => Auth::id(),
            'approved_at_hr' => now(),
        ]);
        return back()->with('success', 'Lembur disetujui HR.');
    }

    public function reject(Request $request, $id)
    {
        $ot = Overtime::findOrFail($id);
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);
        $ot->update([
            'status' => 'ditolak',
            'rejected_by_id' => Auth::id(),
            'rejected_reason' => $request->input('alasan'),
        ]);
        return back()->with('success', 'Lembur ditolak.');
    }

    private function weeklyOvertimeQuotaAllows(Overtime $ot): bool
    {
        $jamLain = PayrollCalculator::overtimeHoursInWeek((int) $ot->karyawan_id, $ot->tanggal, $ot->id);

        return ($jamLain + (float) $ot->jumlah_jam) <= PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK + 0.0001;
    }

    private function weeklyQuotaErrorMessage(Overtime $ot): string
    {
        $jamLain = PayrollCalculator::overtimeHoursInWeek((int) $ot->karyawan_id, $ot->tanggal, $ot->id);
        $sisa = max(0, PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK - $jamLain);

        return 'Total lembur pegawai dalam minggu yang sama (Senin–Minggu) tidak boleh melebihi '
            . (int) PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK
            . ' jam. Sisa kuota minggu ini sekitar '
            . rtrim(rtrim(number_format($sisa, 2, '.', ''), '0'), '.')
            . ' jam.';
    }
}
