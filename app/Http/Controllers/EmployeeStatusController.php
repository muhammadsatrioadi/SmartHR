<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStatus;
use Illuminate\Http\Request;

class EmployeeStatusController extends Controller
{
    public function index()
    {
        $data = EmployeeStatus::orderBy('kode')->paginate(10);
        return view('master.employee_status.index', compact('data'));
    }

    public function create()
    {
        return view('master.employee_status.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:employee_statuses,kode',
            'nama' => 'required|string|max:150',
            'is_payroll' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_payroll'] = (bool)($request->input('is_payroll', true));
        $validated['is_active'] = (bool)($request->input('is_active', true));

        EmployeeStatus::create($validated);
        return redirect()->route('employeeStatus.index')->with('success', 'Status pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = EmployeeStatus::findOrFail($id);
        return view('master.employee_status.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = EmployeeStatus::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:employee_statuses,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'is_payroll' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_payroll'] = (bool)($request->input('is_payroll', true));
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $item->update($validated);
        return redirect()->route('employeeStatus.index')->with('success', 'Status pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        EmployeeStatus::whereKey($id)->delete();
        return redirect()->route('employeeStatus.index')->with('success', 'Status pegawai berhasil dihapus.');
    }
}
