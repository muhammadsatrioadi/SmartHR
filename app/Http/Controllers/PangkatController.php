<?php

namespace App\Http\Controllers;

use App\Models\Pangkat;
use Illuminate\Http\Request;

class PangkatController extends Controller
{
    public function index()
    {
        $data = Pangkat::orderBy('kode')->paginate(10);
        return view('master.pangkat.index', compact('data'));
    }

    public function create()
    {
        return view('master.pangkat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:pangkats,kode',
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        Pangkat::create($validated);
        return redirect()->route('pangkat.index')->with('success', 'Pangkat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Pangkat::findOrFail($id);
        return view('master.pangkat.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pangkat::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:pangkats,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $item->update($validated);
        return redirect()->route('pangkat.index')->with('success', 'Pangkat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pangkat::whereKey($id)->delete();
        return redirect()->route('pangkat.index')->with('success', 'Pangkat berhasil dihapus.');
    }
}
