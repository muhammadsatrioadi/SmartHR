<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'deskripsi',
        'gaji_pokok',
        'tunjangan_tetap',
        'hari_kerja_per_minggu',
        'masa_kerja_min',
        'masa_kerja_maks',
    ];

    public function histories()
    {
        return $this->hasMany(EmployeeSalaryHistory::class);
    }
}
