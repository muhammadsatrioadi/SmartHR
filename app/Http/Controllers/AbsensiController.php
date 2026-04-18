<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{

    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');

        $karyawanBelumAbsen = Karyawan::whereDoesntHave('absensis', function ($query) use ($today) {
            $query->whereDate('tanggal_absensi', $today);
        })->get();


        return view('absensi.read', compact('karyawanBelumAbsen'));
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
        //     return redirect()->route('absensi.read')->with('success', 'Absensi Sudah Tidak bisa Dilakukan');
        // }

        $validateData = $request->validate([
            'karyawan_id' => 'required',
            'status_absen' => 'required|in:hadir,alpha',
            'keterangan' => 'nullable',
        ]);

        $validateData['tanggal_absensi'] = $currentTime->toDateString();
        $validateData['time'] = $currentTime->toTimeString();

        Absensi::create($validateData);
        return redirect()->route('absensi.read')->with('success', 'Data Absensi Berhasil dibuat');
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
        $absensis = Absensi::where('status_absen', 'hadir')->get();
        return view('absensi.absensiHadir', compact('absensis'));
    }

    public function getAlpha()
    {
        $absensis = Absensi::where('status_absen', 'alpha')->get();
        return view('absensi.absensiAlpha', compact('absensis'));
    }
}
