<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Cuti::with(['karyawan', 'leaveType'])->orderByDesc('created_at')->simplePaginate(6);
        $current = $data->currentPage();
        return view('cuti/read', compact('data','current'));
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

        $jumlahHari = $tanggalMulai->diffInDays($tanggalBerakhir) + 1;

        // hitung saldo sederhana untuk cuti tahunan
        $saldoAwal = 12;
        $tahun = $tanggalMulai->year;
        $totalDiambilTahunIni = Cuti::where('karyawan_id', $validated['karyawan_id'])
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'disetujui')
            ->sum('hak_diambil');

        $hakDiambil = $jumlahHari;
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
        $jumlahHari = $tanggalMulai->diffInDays($tanggalBerakhir) + 1;

        $saldoAwal = 12;
        $tahun = $tanggalMulai->year;
        $totalDiambilTahunIni = Cuti::where('karyawan_id', $validated['karyawan_id'])
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'disetujui')
            ->where('id', '!=', $cuti->id)
            ->sum('hak_diambil');

        $hakDiambil = $jumlahHari;
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
            return back()->with('error', 'Status cuti tidak valid untuk persetujuan atasan.');
        }

        $cuti->update([
            'status' => 'menunggu_hr',
            'approved_by_supervisor_id' => Auth::id(),
            'approved_at_supervisor' => now(),
        ]);

        return back()->with('success', 'Cuti disetujui atasan, menunggu HR.');
    }

    public function approveHr($id)
    {
        $cuti = Cuti::findOrFail($id);
        if ($cuti->status !== 'menunggu_hr') {
            return back()->with('error', 'Status cuti tidak valid untuk persetujuan HR.');
        }

        $cuti->update([
            'status' => 'disetujui',
            'approved_by_hr_id' => Auth::id(),
            'approved_at_hr' => now(),
        ]);

        return back()->with('success', 'Cuti disetujui HR.');
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
