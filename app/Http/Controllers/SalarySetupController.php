<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class SalarySetupController extends Controller
{
    /**
     * Komponen yang dikelola pada Setup Gaji (mengikuti panduan komponen gaji).
     */
    public const KOMPONEN = [
        'tunjangan_jabatan',
        'tunjangan_fungsional',
        'tunjangan_profesi',
        'tunjangan_makan',
        'tunjangan_pulsa',
        'tkp',
    ];

    /**
     * Tampilkan halaman setup komponen gaji per jabatan.
     */
    public function index()
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('gaji.setup', compact('jabatans'));
    }

    /**
     * Simpan komponen gaji awal untuk tiap jabatan. Nilai ini otomatis
     * dipakai saat Admin HR membuat karyawan baru.
     */
    public function update(Request $request)
    {
        $request->validate([
            'gaji_pokok' => 'required|array',
            'gaji_pokok.*' => 'nullable|numeric|min:0',
            'tunjangan_jabatan.*' => 'nullable|numeric|min:0',
            'tunjangan_fungsional.*' => 'nullable|numeric|min:0',
            'tunjangan_profesi.*' => 'nullable|numeric|min:0',
            'tunjangan_makan.*' => 'nullable|numeric|min:0',
            'tunjangan_pulsa.*' => 'nullable|numeric|min:0',
            'tkp.*' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu.*' => 'nullable|in:5,6',
        ]);

        $perubahan = [];

        foreach ($request->input('gaji_pokok', []) as $id => $value) {
            $jabatan = Jabatan::find($id);
            if (!$jabatan) {
                continue;
            }

            $data = [
                'gaji_pokok' => (int) ($value ?? 0),
                'hari_kerja_per_minggu' => (int) ($request->input("hari_kerja_per_minggu.$id") ?? 5),
            ];

            $totalKomponen = 0;
            foreach (self::KOMPONEN as $komponen) {
                $data[$komponen] = (int) ($request->input("$komponen.$id") ?? 0);
                $totalKomponen += $data[$komponen];
            }

            // Kolom tunjangan lama diisi jumlah komponen rinci agar modul
            // lembur & estimasi lama tetap mendapat angka yang benar.
            $data['tunjangan'] = $totalKomponen;

            $sebelum = $jabatan->only(['gaji_pokok', ...self::KOMPONEN, 'hari_kerja_per_minggu']);
            $jabatan->update($data);

            if ($sebelum != $data) {
                $perubahan[$jabatan->nama_jabatan] = ['dari' => $sebelum, 'menjadi' => $data];
            }
        }

        if ($perubahan) {
            ActivityLogger::catat('setup_gaji', 'perbarui', [
                'referensi' => 'Setup Gaji per jabatan',
                'keterangan' => 'Perubahan komponen gaji pada ' . count($perubahan) . ' jabatan',
                'perubahan' => $perubahan,
            ]);
        }

        return redirect()->route('gaji.setup')->with('success', 'Setup gaji berhasil disimpan.');
    }
}
