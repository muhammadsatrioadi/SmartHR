<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $data = LeaveType::orderBy('kode')->paginate(10);
        return view('master.leave_type.index', compact('data'));
    }

    public function create()
    {
        return view('master.leave_type.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:leave_types,kode',
            'nama' => 'required|string|max:150',
            'grup' => 'nullable|string|max:50',
            'pakai_periode' => 'nullable|boolean',
            'satuan_periode' => 'nullable|in:hari,bulan,tahun',
            'max_expired' => 'nullable|integer|min:0',
            'satuan_expired' => 'nullable|in:hari,bulan,tahun',
            'min_masa_kerja' => 'nullable|integer|min:0',
            'satuan_masa_kerja' => 'nullable|in:hari,bulan,tahun',
            'max_backdate' => 'nullable|integer|min:0',
            'rekap' => 'nullable|boolean',
        ]);
        $validated['pakai_periode'] = (bool)($request->input('pakai_periode', false));
        $validated['rekap'] = (bool)($request->input('rekap', true));

        LeaveType::create($validated);
        return redirect()->route('leaveType.index')->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = LeaveType::findOrFail($id);
        return view('master.leave_type.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = LeaveType::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:leave_types,kode,' . $item->id,
            'nama' => 'required|string|max:150',
            'grup' => 'nullable|string|max:50',
            'pakai_periode' => 'nullable|boolean',
            'satuan_periode' => 'nullable|in:hari,bulan,tahun',
            'max_expired' => 'nullable|integer|min:0',
            'satuan_expired' => 'nullable|in:hari,bulan,tahun',
            'min_masa_kerja' => 'nullable|integer|min:0',
            'satuan_masa_kerja' => 'nullable|in:hari,bulan,tahun',
            'max_backdate' => 'nullable|integer|min:0',
            'rekap' => 'nullable|boolean',
        ]);
        $validated['pakai_periode'] = (bool)($request->input('pakai_periode', false));
        $validated['rekap'] = (bool)($request->input('rekap', true));

        $item->update($validated);
        return redirect()->route('leaveType.index')->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy($id)
    {
        LeaveType::whereKey($id)->delete();
        return redirect()->route('leaveType.index')->with('success', 'Jenis cuti berhasil dihapus.');
    }
}
