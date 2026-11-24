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
            'kode' => 'required|alpha_num|max:20|unique:shift_groups,kode',
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:100',
            'tipe_absen' => 'nullable|string|max:50',
            'istirahat_menit' => 'required|integer|min:1',
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
            'istirahat_menit.min' => 'Istirahat wajib diisi dan tidak boleh 0.',
            'istirahat_menit.required' => 'Istirahat wajib diisi dan tidak boleh 0.',
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
        // Kode tidak dapat diubah saat edit.
        $validated = $request->validate([
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:100',
            'tipe_absen' => 'nullable|string|max:50',
            'istirahat_menit' => 'required|integer|min:1',
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
            'istirahat_menit.min' => 'Istirahat wajib diisi dan tidak boleh 0.',
            'istirahat_menit.required' => 'Istirahat wajib diisi dan tidak boleh 0.',
        ]);
        $validated['kode'] = $item->kode;

        $item->update($validated);
        return redirect()->route('shiftGroup.index')->with('success', 'Group shift berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ShiftGroup::whereKey($id)->delete();
        return redirect()->route('shiftGroup.index')->with('success', 'Group shift berhasil dihapus.');
    }
}
