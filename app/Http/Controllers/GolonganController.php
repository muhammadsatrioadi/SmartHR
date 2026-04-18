<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index()
    {
        $data = Golongan::orderBy('kode')->paginate(10);
        return view('master.golongan.index', compact('data'));
    }

    public function create()
    {
        return view('master.golongan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:golongans,kode',
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = (bool)($request->input('is_active', true));

        Golongan::create($validated);
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Golongan::findOrFail($id);
        return view('master.golongan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Golongan::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:golongans,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $item->update($validated);
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Golongan::whereKey($id)->delete();
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil dihapus.');
    }
}
