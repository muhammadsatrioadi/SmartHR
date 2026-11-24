<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Department;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $today = Carbon::today('Asia/Jakarta');
        $request->mergeIfMissing([
            'date_from' => $today->copy()->startOfMonth()->toDateString(),
            'date_to' => $today->toDateString(),
            'status' => '',
        ]);

        $filters = $request->validate([
            'department_id' => 'nullable|integer|exists:departments,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'status' => 'nullable|in:hadir,alpha',
        ]);

        $query = Absensi::with('karyawan.department')
            ->whereIn('status_absen', ['hadir', 'alpha'])
            ->whereBetween('tanggal_absensi', [$filters['date_from'], $filters['date_to']]);

        if (!empty($filters['department_id'])) {
            $query->whereHas('karyawan', function ($karyawanQuery) use ($filters) {
                $karyawanQuery->where('department_id', $filters['department_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status_absen', $filters['status']);
        }

        $absensis = $query->orderByDesc('tanggal_absensi')
            ->orderByDesc('time')
            ->paginate(25)
            ->withQueryString();
        $departments = Department::orderBy('nama')->get();
        $departmentId = $filters['department_id'] ?? '';
        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];
        $status = $filters['status'] ?? '';

        return view('absensi.read', compact(
            'absensis',
            'departments',
            'departmentId',
            'dateFrom',
            'dateTo',
            'status'
        ));
    }

    public function create()
    {
        // $karyawan = Karyawan::all();

        // return view('absensi.create', compact('karyawan'));
    }


    public function store(Request $request)
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $deadline = Carbon::today('Asia/Jakarta')->setHour(8)->setMinute(0)->setSecond(0);

        // dd($currentTime->toDateString()); 
        // dd($currentTime->toTimeString());
        // dd($deadline);

        // if ($currentTime->greaterThan($deadline)) {
        //     return redirect()->route('presensi.read')->with('success', 'Absensi Sudah Tidak bisa Dilakukan');
        // }

        $validateData = $request->validate([
            'karyawan_id' => 'required',
            'status_absen' => 'required|in:hadir,alpha',
            'keterangan' => 'nullable',
        ]);

        $validateData['tanggal_absensi'] = $currentTime->toDateString();
        $validateData['time'] = $currentTime->toTimeString();

        Absensi::create($validateData);
        return redirect()->route('presensi.read')->with('success', 'Data Absensi Berhasil dibuat');
    }


    public function show(Absensi $absensi, $id)
    {
        // $absensi = $absensi::findOrFail($id);

        // return view('absensi.show', compact('absensi'));
    }


    public function edit(Absensi $absensi, $id)
    {
        // $absensi = Absensi::findOrFail($id);
        // $karyawan = Karyawan::all();

        // return view('edit.absensi', compact('absensi', 'karyawan'));
    }


    public function update(Request $request, Absensi $absensi, $id)
    {
        // $absensi = $absensi::findOrFail($id);

        // $validateData = $request->validate([
        //     'id_karyawan' => 'required|string',
        //     'status_absen' => 'required|string',
        //     'keterangan' => 'nullable',
        //     'tanggal_absensi' => 'required|date',
        // ]);

        // $absensi->update($validateData);

        // return redirect()->route('absensi.index')->with('Data Absensi berhasil diubah');
    }


    public function destroy(Absensi $absensi, $id)
    {
        // $absensi = $absensi::findOrFail($id);

        // $absensi->delete();

        // return redirect()->route('absensi.index')->with('Data Absensi berhasil dihapus');
    }


    public function getHadir()
    {
        return redirect()->route('presensi.read', ['status' => 'hadir']);
    }

    public function getAlpha()
    {
        return redirect()->route('presensi.read', ['status' => 'alpha']);
    }
}
