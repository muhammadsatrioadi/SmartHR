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
        'tunjangan_jabatan',
        'tunjangan_fungsional',
        'tunjangan_profesi',
        'tunjangan_makan',
        'tunjangan_pulsa',
        'tkp',
        'pasangan_ditanggung',
        'jumlah_anak_ditanggung',
        'hari_kerja_per_minggu',
        'tanggal_berlaku',
        'alasan',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'pasangan_ditanggung' => 'boolean',
    ];

    /**
     * Jumlah tunjangan tetap: komponen rinci bila sudah diisi,
     * atau kolom tunjangan_tetap lama agar data lama tetap terpakai.
     */
    public function tunjanganTetapTotal(): float
    {
        $rinci = (float) $this->tunjangan_jabatan
            + (float) $this->tunjangan_fungsional
            + (float) $this->tunjangan_profesi
            + (float) $this->tunjangan_makan
            + (float) $this->tunjangan_pulsa
            + (float) $this->tkp;

        return $rinci > 0 ? $rinci : (float) $this->tunjangan_tetap;
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function salaryStep()
    {
        return $this->belongsTo(SalaryStep::class);
    }
}
