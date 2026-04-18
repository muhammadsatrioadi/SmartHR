<?php

namespace App\Http\Controllers;

use App\Models\ShiftGroup;
use App\Models\WorkShift;
use Illuminate\Http\Request;

class WorkShiftController extends Controller
{
    public function index()
    {
        $data = WorkShift::with('shiftGroup')->orderBy('kode')->paginate(10);
        return view('master.work_shift.index', compact('data'));
    }

    public function create()
    {
        $shiftGroups = ShiftGroup::orderBy('kode')->get();
        return view('master.work_shift.create', compact('shiftGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'kode' => 'required|string|max:20|unique:work_shifts,kode',
            'nama' => 'required|string|max:100',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'jam_mulai_telat' => 'nullable|date_format:H:i',
            'jam_masuk_cepat' => 'nullable|date_format:H:i',
            'toleransi_telat_menit' => 'nullable|integer|min:0',
            'is_aktif' => 'nullable|boolean',
        ]);
        $validated['is_aktif'] = (bool)($request->input('is_aktif', true));

        WorkShift::create($validated);
        return redirect()->route('workShift.index')->with('success', 'Shift kerja berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = WorkShift::findOrFail($id);
        $shiftGroups = ShiftGroup::orderBy('kode')->get();
        return view('master.work_shift.edit', compact('item', 'shiftGroups'));
    }

    public function update(Request $request, $id)
    {
        $item = WorkShift::findOrFail($id);
        $validated = $request->validate([
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'kode' => 'required|string|max:20|unique:work_shifts,kode,' . $item->id,
            'nama' => 'required|string|max:100',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'jam_mulai_telat' => 'nullable|date_format:H:i',
            'jam_masuk_cepat' => 'nullable|date_format:H:i',
            'toleransi_telat_menit' => 'nullable|integer|min:0',
            'is_aktif' => 'nullable|boolean',
        ]);
        $validated['is_aktif'] = (bool)($request->input('is_aktif', true));

        $item->update($validated);
        return redirect()->route('workShift.index')->with('success', 'Shift kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        WorkShift::whereKey($id)->delete();
        return redirect()->route('workShift.index')->with('success', 'Shift kerja berhasil dihapus.');
    }
}
