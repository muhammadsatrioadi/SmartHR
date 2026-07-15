<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\User;
use App\Services\LeaveBalanceService;
use App\Support\KaryawanResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function __construct(
        protected LeaveBalanceService $leaveBalance
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil email user dengan role admin_hr dan atasan (manajer)
        $manajerEmails = User::whereIn('role', ['admin_hr', 'atasan'])
            ->whereNotNull('email')
            ->pluck('email');

        $dataKaryawan = Cuti::with(['karyawan', 'leaveType'])
            ->whereHas('karyawan', function ($query) use ($manajerEmails) {
                $query->whereNull('email')
                    ->orWhereNotIn('email', $manajerEmails);
            })
            ->orderByDesc('created_at')
            ->paginate(6, ['*'], 'karyawan_page');

        $dataManajer = Cuti::with(['karyawan', 'leaveType'])
            ->whereHas('karyawan', function ($query) use ($manajerEmails) {
                $query->whereIn('email', $manajerEmails);
            })
            ->orderByDesc('created_at')
            ->paginate(6, ['*'], 'manajer_page');

        return view('cuti/read', compact('dataKaryawan', 'dataManajer'));
    } 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = Karyawan::orderBy('name')->get();
        $leaveTypes = LeaveType::orderBy('kode')->get();
        return view('cuti/create',compact('karyawan','leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'leave_type_id' => 'nullable|exists:leave_types,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:255',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalBerakhir = Carbon::parse($validated['tanggal_berakhir']);

        // Hitung jumlah hari kerja (melewati akhir pekan dan hari libur nasional)
        $hakDiambil = 0;
        for ($date = $tanggalMulai->copy(); $date->lte($tanggalBerakhir); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }
            if (Holiday::whereDate('tanggal', $date)->exists()) {
                continue;
            }
            $hakDiambil++;
        }

        if ($hakDiambil <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Rentang tanggal yang dipilih hanya berisi hari libur atau akhir pekan.');
        }

        $tahun = $tanggalMulai->year;
        $leaveType = LeaveType::find($validated['leave_type_id']);
        $karyawan = Karyawan::findOrFail($validated['karyawan_id']);

        $this->leaveBalance->ensureBalancesForYear($karyawan, $tahun);
        $saldoAwal = $leaveType?->kuota_hari ?? 0;

        if ($leaveType && $validated['leave_type_id']) {
            $balance = \App\Models\EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
                ->where('leave_type_id', $leaveType->id)
                ->where('tahun', $tahun)
                ->first();

            if (!$balance || $balance->sisa < $hakDiambil) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Saldo cuti tidak mencukupi untuk jenis ' . $leaveType->nama . '. Sisa saldo: ' . ($balance->sisa ?? 0) . ' hari, Pengajuan: ' . $hakDiambil . ' hari kerja.');
            }
            $saldoAwal = $balance->kuota;
        }

        $totalDiambilTahunIni = Cuti::where('karyawan_id', $validated['karyawan_id'])
            ->where('leave_type_id', $validated['leave_type_id'])
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'disetujui')
            ->sum('hak_diambil');
        $saldoSisa = max($saldoAwal - ($totalDiambilTahunIni + $hakDiambil), 0);

        $cuti = Cuti::create([
            'karyawan_id' => $validated['karyawan_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'keterangan' => $validated['keterangan'],
            'jenis_cuti' => optional(LeaveType::find($validated['leave_type_id']))->nama ?? 'Cuti',
            'status' => 'menunggu_atasan',
            'saldo_awal' => $saldoAwal,
            'hak_diambil' => $hakDiambil,
            'saldo_sisa' => $saldoSisa,
        ]);

        // Catat absensi sebagai pending cuti (opsional)
        for ($date = $tanggalMulai->copy(); $date->lte($tanggalBerakhir); $date->addDay()) {
            Absensi::create([
                'karyawan_id' => $validated['karyawan_id'],
                'status_absen' => 'cuti',
                'keterangan' => $validated['keterangan'],
                'tanggal_absensi' => $date->toDateString(),
                'time' => $currentTime->toTimeString(),
            ]);
        }

        return redirect()->route('cuti.read')->with('success','Pengajuan cuti berhasil dibuat dan menunggu persetujuan atasan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cuti $cuti)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $data = Cuti::with(['karyawan', 'leaveType'])->findOrFail($id);
        $karyawan = Karyawan::orderBy('name')->get();
        $leaveTypes = LeaveType::orderBy('kode')->get();
        return view('cuti/edit',compact('data','karyawan','leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);

        if (!in_array($cuti->status, ['menunggu_atasan', 'menunggu_hr'])) {
            return redirect()->route('cuti.read')->with('error', 'Cuti yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'leave_type_id' => 'nullable|exists:leave_types,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:255',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalBerakhir = Carbon::parse($validated['tanggal_berakhir']);

        // Hitung jumlah hari kerja (melewati akhir pekan dan hari libur nasional)
        $hakDiambil = 0;
        for ($date = $tanggalMulai->copy(); $date->lte($tanggalBerakhir); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }
            if (Holiday::whereDate('tanggal', $date)->exists()) {
                continue;
            }
            $hakDiambil++;
        }

        if ($hakDiambil <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Rentang tanggal yang dipilih hanya berisi hari libur atau akhir pekan.');
        }

        $tahun = $tanggalMulai->year;
        $leaveType = LeaveType::find($validated['leave_type_id']);
        $karyawan = Karyawan::findOrFail($validated['karyawan_id']);

        $this->leaveBalance->ensureBalancesForYear($karyawan, $tahun);
        $saldoAwal = $leaveType?->kuota_hari ?? 0;

        if ($leaveType && $validated['leave_type_id']) {
            $balance = \App\Models\EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
                ->where('leave_type_id', $leaveType->id)
                ->where('tahun', $tahun)
                ->first();

            if (!$balance || $balance->sisa < $hakDiambil) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Saldo cuti tidak mencukupi untuk jenis ' . $leaveType->nama . '. Sisa saldo: ' . ($balance->sisa ?? 0) . ' hari, Pengajuan: ' . $hakDiambil . ' hari kerja.');
            }
            $saldoAwal = $balance->kuota;
        }

        $totalDiambilTahunIni = Cuti::where('karyawan_id', $validated['karyawan_id'])
            ->where('leave_type_id', $validated['leave_type_id'])
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'disetujui')
            ->where('id', '!=', $cuti->id)
            ->sum('hak_diambil');

        $saldoSisa = max($saldoAwal - ($totalDiambilTahunIni + $hakDiambil), 0);

        $cuti->update([
            'karyawan_id' => $validated['karyawan_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'keterangan' => $validated['keterangan'],
            'jenis_cuti' => optional(LeaveType::find($validated['leave_type_id']))->nama ?? $cuti->jenis_cuti,
            'saldo_awal' => $saldoAwal,
            'hak_diambil' => $hakDiambil,
            'saldo_sisa' => $saldoSisa,
        ]);

        return redirect()->route('cuti.read')->with('success', 'Pengajuan cuti berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        Cuti::destroy($id);
        return redirect()->route('cuti.read')->with('success', 'Cuti berhasil dihapus');
    }

    public function approveSupervisor($id)
    {
        $cuti = Cuti::findOrFail($id);
        if ($cuti->status !== 'menunggu_atasan') {
            return back()->with('error', 'Status cuti tidak valid untuk persetujuan.');
        }

        $cuti->update([
            'status' => 'disetujui',
            'approved_by_supervisor_id' => Auth::id(),
            'approved_at_supervisor' => now(),
        ]);

        $cuti->load('karyawan');
        if ($cuti->leave_type_id && $cuti->karyawan) {
            $this->leaveBalance->recalculateUsed(
                $cuti->karyawan,
                $cuti->leave_type_id,
                (int) Carbon::parse($cuti->tanggal_mulai)->year
            );
        }

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    public function approveHr($id)
    {
        return back()->with('error', 'Persetujuan cukup dilakukan oleh Atasan/Manajer.');
    }

    public function reject(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);

        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        $cuti->update([
            'status' => 'ditolak',
            'rejected_by_id' => Auth::id(),
            'rejected_reason' => $request->input('alasan'),
        ]);

        return back()->with('success', 'Cuti ditolak.');
    }
}
