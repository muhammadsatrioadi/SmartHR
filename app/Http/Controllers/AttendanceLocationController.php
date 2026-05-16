<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLocation;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AttendanceLocationController extends Controller
{
    public function index()
    {
        $data = AttendanceLocation::orderBy('nama')->paginate(10);

        return view('master.attendance_location.index', compact('data'));
    }

    public function create()
    {
        return view('master.attendance_location.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:1|max:100',
            'is_aktif' => 'nullable|boolean',
        ]);
        $validated['is_aktif'] = (bool) $request->input('is_aktif', true);

        AttendanceLocation::create($validated);

        return redirect()->route('attendanceLocation.index')
            ->with('success', 'Titik lokasi absensi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = AttendanceLocation::findOrFail($id);

        return view('master.attendance_location.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = AttendanceLocation::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:1|max:100',
            'is_aktif' => 'nullable|boolean',
        ]);
        $validated['is_aktif'] = (bool) $request->input('is_aktif', true);

        $item->update($validated);

        return redirect()->route('attendanceLocation.index')
            ->with('success', 'Titik lokasi absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AttendanceLocation::whereKey($id)->delete();

        return redirect()->route('attendanceLocation.index')
            ->with('success', 'Titik lokasi absensi dihapus.');
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'attendance_location_id' => 'nullable|exists:attendance_locations,id',
        ]);

        Karyawan::whereKey($validated['karyawan_id'])->update([
            'attendance_location_id' => $validated['attendance_location_id'],
        ]);

        return back()->with('success', 'Lokasi absensi pegawai diperbarui.');
    }
}
