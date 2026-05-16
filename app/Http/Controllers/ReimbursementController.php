<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Overtime;
use App\Models\Reimbursement;
use App\Support\KaryawanResolver;
use App\Support\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    /**
     * Portal: Daftar reimburse dan lembur karyawan
     */
    public function portalIndex()
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);

        $reimbursements = Reimbursement::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'reim_page');

        $overtimes = Overtime::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'ot_page');

        return view('portal.reimburse.index', compact('karyawan', 'reimbursements', 'overtimes'));
    }

    /**
     * Portal: Form pengajuan reimburse (nota)
     */
    public function portalCreate()
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);
        return view('portal.reimburse.create', compact('karyawan'));
    }

    /**
     * Portal: Simpan pengajuan reimburse
     */
    public function portalStore(Request $request)
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:500',
            'lampiran' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('reimbursements', 'public');
        }

        Reimbursement::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $validated['tanggal'],
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'],
            'lampiran' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('portal.reimburse.index')->with('success', 'Pengajuan reimburse berhasil dikirim.');
    }

    /**
     * Portal: Form pengajuan lembur
     */
    public function portalOvertimeCreate()
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);
        return view('portal.reimburse.overtime_create', compact('karyawan'));
    }

    /**
     * Portal: Simpan pengajuan lembur
     */
    public function portalOvertimeStore(Request $request)
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jenis_hari' => 'required|in:hari_kerja,hari_libur,hari_raya',
            'is_hari_kerja_terpendek' => 'nullable|boolean',
            'keterangan_pekerjaan' => 'required|string|max:255',
            'pilihan_pembayaran' => 'required|in:bulan_ini,bulan_depan',
            'bukti_screenshot' => 'nullable|image|max:2048',
        ]);

        $mulai = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $selesai = Carbon::createFromFormat('H:i', $validated['jam_selesai']);
        $validated['jumlah_jam'] = round($mulai->floatDiffInHours($selesai), 2);
        $validated['karyawan_id'] = $karyawan->id;
        $validated['pegawai_telah_menyetujui'] = true;
        $validated['status'] = 'menunggu_approval';
        $validated['is_hari_kerja_terpendek'] = (bool) ($validated['is_hari_kerja_terpendek'] ?? false);

        // Bukti Screenshot
        if ($request->hasFile('bukti_screenshot')) {
            $validated['bukti_screenshot'] = $request->file('bukti_screenshot')->store('overtimes/proofs', 'public');
        }

        // Load salary components for pay calculation
        $karyawan->load(['jabatan', 'salaryHistories' => function ($query) {
            $query->orderByDesc('tanggal_berlaku')->orderByDesc('id');
        }]);
        $components = PayrollCalculator::resolveSalaryComponents($karyawan);
        $validated['hari_kerja_per_minggu'] = $components['hari_kerja_per_minggu'];

        // Check max hours per day
        $maxHours = PayrollCalculator::maxOvertimeHours(
            $validated['jenis_hari'],
            $validated['hari_kerja_per_minggu'],
            $validated['is_hari_kerja_terpendek']
        );
        if ($validated['jumlah_jam'] > $maxHours) {
            return back()->withInput()->with('error', "Durasi lembur melebihi batas maksimal {$maxHours} jam.");
        }

        // Check weekly quota
        $jamMingguIni = PayrollCalculator::overtimeHoursInWeek($karyawan->id, Carbon::parse($validated['tanggal']));
        if ($jamMingguIni + $validated['jumlah_jam'] > PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK + 0.0001) {
            return back()->withInput()->with('error', "Total lembur mingguan melebihi batas " . PayrollCalculator::MAX_OVERTIME_HOURS_PER_WEEK . " jam.");
        }

        // Calculate pay
        $overtimePay = PayrollCalculator::calculateOvertimePay(
            $validated['jumlah_jam'],
            $validated['jenis_hari'],
            $validated['hari_kerja_per_minggu'],
            $validated['is_hari_kerja_terpendek'],
            $components
        );

        $data = array_merge($validated, $overtimePay);
        Overtime::create($data);

        return redirect()->route('portal.reimburse.index')->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    /**
     * Admin: Daftar semua reimburse untuk approval
     */
    public function adminIndex(Request $request)
    {
        $query = Reimbursement::with(['karyawan', 'supervisor', 'hr']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(20);
        return view('master.reimbursement.index', compact('items'));
    }

    /**
     * Admin/Atasan: Approve reimburse
     */
    public function approveSupervisor($id)
    {
        $reim = Reimbursement::findOrFail($id);
        abort_unless(in_array(Auth::user()->role, ['atasan', 'manajer'], true), 403);

        $reim->update([
            'status' => 'disetujui',
            'approved_by_supervisor_id' => Auth::id(),
            'approved_at_supervisor' => now(),
        ]);

        return back()->with('success', 'Reimburse disetujui.');
    }

    public function approveHr($id)
    {
        return back()->with('error', 'Persetujuan cukup dilakukan oleh Atasan/Manajer.');
    }

    public function reject(Request $request, $id)
    {
        $reim = Reimbursement::findOrFail($id);
        $request->validate(['alasan' => 'required|string|max:255']);

        $reim->update([
            'status' => 'ditolak',
            'rejected_by_id' => Auth::id(),
            'rejected_reason' => $request->alasan,
        ]);

        return back()->with('success', 'Reimburse ditolak.');
    }
}
