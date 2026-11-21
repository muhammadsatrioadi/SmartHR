<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Pencatat jejak audit sederhana. Dipakai pada aksi yang menyentuh data gaji
 * (setup jabatan, data gaji dasar, rekap bulanan, finalisasi slip).
 */
class ActivityLogger
{
    /**
     * @param  array{referensi?:string,karyawan_id?:int,keterangan?:string,perubahan?:array}  $konteks
     */
    public static function catat(string $modul, string $aksi, array $konteks = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'modul' => $modul,
            'aksi' => $aksi,
            'referensi' => $konteks['referensi'] ?? null,
            'karyawan_id' => $konteks['karyawan_id'] ?? null,
            'keterangan' => $konteks['keterangan'] ?? null,
            'perubahan' => $konteks['perubahan'] ?? null,
            'ip' => request()->ip(),
        ]);
    }
}
