<?php

namespace App\Http\Controllers;

use App\Models\ShiftGroup;
use Illuminate\Http\Request;

class ShiftGroupController extends Controller
{
    public function index()
    {
        $data = ShiftGroup::orderBy('kode')->paginate(10);
        return view('master.shift_group.index', compact('data'));
    }

    public function create()
    {
        return view('master.shift_group.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:shift_groups,kode',
            'nama' => 'required|string|max:100',
            'tipe_absen' => 'nullable|string|max:50',
            'istirahat_menit' => 'nullable|integer|min:0',
        ]);

        ShiftGroup::create($validated);
        return redirect()->route('shiftGroup.index')->with('success', 'Group shift berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = ShiftGroup::findOrFail($id);
        return view('master.shift_group.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ShiftGroup::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:shift_groups,kode,' . $item->id,
            'nama' => 'required|string|max:100',
            'tipe_absen' => 'nullable|string|max:50',
            'istirahat_menit' => 'nullable|integer|min:0',
        ]);

        $item->update($validated);
        return redirect()->route('shiftGroup.index')->with('success', 'Group shift berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ShiftGroup::whereKey($id)->delete();
        return redirect()->route('shiftGroup.index')->with('success', 'Group shift berhasil dihapus.');
    }
}
