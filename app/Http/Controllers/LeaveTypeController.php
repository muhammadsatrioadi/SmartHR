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
            'kode' => 'required|alpha_num|max:20|unique:leave_types,kode',
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:150',
            'grup' => 'nullable|regex:/^[A-Za-z0-9 ]+$/|max:50',
            'pakai_periode' => 'nullable|boolean',
            'satuan_periode' => 'nullable|in:hari,bulan,tahun',
            'max_expired' => 'nullable|integer|min:0',
            'satuan_expired' => 'nullable|in:hari,bulan,tahun',
            'min_masa_kerja' => 'nullable|integer|min:0',
            'satuan_masa_kerja' => 'nullable|in:hari,bulan,tahun',
            'max_backdate' => 'nullable|integer|min:0',
            'rekap' => 'nullable|boolean',
            'kuota_hari' => 'nullable|integer|min:0|max:365',
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
            'grup.regex' => 'Grup hanya boleh berisi huruf, angka, dan spasi.',
            'max_expired.integer' => 'Max Expired harus berupa bilangan bulat (tanpa desimal).',
            'min_masa_kerja.integer' => 'Masa Kerja harus berupa bilangan bulat (tanpa desimal).',
            'max_backdate.integer' => 'Max BackDate harus berupa bilangan bulat (tanpa desimal).',
            'kuota_hari.integer' => 'Kuota hari harus berupa bilangan bulat (tanpa desimal).',
        ]);
        $validated['pakai_periode'] = (bool)($request->input('pakai_periode', false));
        $validated['rekap'] = (bool)($request->input('rekap', true));
        $validated['kuota_hari'] = $validated['kuota_hari'] ?? 0;

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
            'kode' => 'required|alpha_num|max:20|unique:leave_types,kode,' . $item->id,
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:150',
            'grup' => 'nullable|regex:/^[A-Za-z0-9 ]+$/|max:50',
            'pakai_periode' => 'nullable|boolean',
            'satuan_periode' => 'nullable|in:hari,bulan,tahun',
            'max_expired' => 'nullable|integer|min:0',
            'satuan_expired' => 'nullable|in:hari,bulan,tahun',
            'min_masa_kerja' => 'nullable|integer|min:0',
            'satuan_masa_kerja' => 'nullable|in:hari,bulan,tahun',
            'max_backdate' => 'nullable|integer|min:0',
            'rekap' => 'nullable|boolean',
            'kuota_hari' => 'nullable|integer|min:0|max:365',
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
            'grup.regex' => 'Grup hanya boleh berisi huruf, angka, dan spasi.',
            'max_expired.integer' => 'Max Expired harus berupa bilangan bulat (tanpa desimal).',
            'min_masa_kerja.integer' => 'Masa Kerja harus berupa bilangan bulat (tanpa desimal).',
            'max_backdate.integer' => 'Max BackDate harus berupa bilangan bulat (tanpa desimal).',
            'kuota_hari.integer' => 'Kuota hari harus berupa bilangan bulat (tanpa desimal).',
        ]);
        $validated['pakai_periode'] = (bool)($request->input('pakai_periode', false));
        $validated['rekap'] = (bool)($request->input('rekap', true));
        $validated['kuota_hari'] = $validated['kuota_hari'] ?? 0;

        $item->update($validated);
        return redirect()->route('leaveType.index')->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy($id)
    {
        LeaveType::whereKey($id)->delete();
        return redirect()->route('leaveType.index')->with('success', 'Jenis cuti berhasil dihapus.');
    }
}
