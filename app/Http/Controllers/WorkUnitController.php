<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    public function index()
    {
        $data = WorkUnit::with('department')->orderBy('kode')->paginate(10);
        return view('master.work_unit.index', compact('data'));
    }

    public function create()
    {
        $departments = Department::orderBy('kode')->get();
        return view('master.work_unit.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'kode' => 'required|string|max:20|unique:work_units,kode',
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        WorkUnit::create($validated);
        return redirect()->route('workUnit.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = WorkUnit::findOrFail($id);
        $departments = Department::orderBy('kode')->get();
        return view('master.work_unit.edit', compact('item', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $item = WorkUnit::findOrFail($id);
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'kode' => 'required|string|max:20|unique:work_units,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $item->update($validated);
        return redirect()->route('workUnit.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        WorkUnit::whereKey($id)->delete();
        return redirect()->route('workUnit.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
