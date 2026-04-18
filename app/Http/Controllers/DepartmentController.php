<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $data = Department::orderBy('kode')->paginate(10);
        return view('master.department.index', compact('data'));
    }

    public function create()
    {
        return view('master.department.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:departments,kode',
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        Department::create($validated);
        return redirect()->route('department.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Department::findOrFail($id);
        return view('master.department.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Department::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:departments,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $item->update($validated);
        return redirect()->route('department.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Department::whereKey($id)->delete();
        return redirect()->route('department.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
