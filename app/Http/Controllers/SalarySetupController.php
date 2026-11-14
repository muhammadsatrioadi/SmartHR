<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class SalarySetupController extends Controller
{
    /**
     * Tampilkan halaman setup komponen gaji per jabatan.
     */
    public function index()
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('gaji.setup', compact('jabatans'));
    }

    /**
     * Simpan komponen gaji (gaji pokok, tunjangan, potongan) untuk tiap jabatan.
     */
    public function update(Request $request)
    {
        $request->validate([
            'gaji_pokok' => 'required|array',
            'gaji_pokok.*' => 'nullable|numeric|min:0',
            'tunjangan' => 'required|array',
            'tunjangan.*' => 'nullable|numeric|min:0',
            'potongan' => 'required|array',
            'potongan.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->input('gaji_pokok', []) as $id => $value) {
            $jabatan = Jabatan::find($id);
            if (!$jabatan) {
                continue;
            }

            $jabatan->update([
                'gaji_pokok' => (int) ($value ?? 0),
                'tunjangan' => (int) ($request->input("tunjangan.$id") ?? 0),
                'potongan' => (int) ($request->input("potongan.$id") ?? 0),
            ]);
        }

        return redirect()->route('gaji.setup')->with('success', 'Setup gaji berhasil disimpan.');
    }
}
