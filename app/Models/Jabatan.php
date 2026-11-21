<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jabatan',
        'jam_mulai_kerja',
        'jam_selesai_kerja',
        'note_pekerjaan',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'tunjangan_jabatan',
        'tunjangan_fungsional',
        'tunjangan_profesi',
        'tunjangan_makan',
        'tunjangan_pulsa',
        'tkp',
        'hari_kerja_per_minggu',
    ];

    protected $casts = [
        'gaji_pokok' => 'float',
        'tunjangan_jabatan' => 'float',
        'tunjangan_fungsional' => 'float',
        'tunjangan_profesi' => 'float',
        'tunjangan_makan' => 'float',
        'tunjangan_pulsa' => 'float',
        'tkp' => 'float',
    ];

    //  Relasi ke karyawans
    public function karyawans(){
        return $this->hasMany(Karyawan::class);
    }
}
