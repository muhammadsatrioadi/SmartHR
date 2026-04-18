<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'salary_step_id',
        'gaji_pokok',
        'tunjangan_tetap',
        'tunjangan_lain',
        'hari_kerja_per_minggu',
        'tanggal_berlaku',
        'alasan',
        'disetujui_oleh',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function salaryStep()
    {
        return $this->belongsTo(SalaryStep::class);
    }
}
