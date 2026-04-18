<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmploymentHistory extends Model
{
    use HasFactory;

    protected $table = 'employee_employment_histories';

    protected $fillable = [
        'karyawan_id',
        'pangkat',
        'golongan',
        'status',
        'keterangan',
        'sk_tmt',
        'tgl_kontrak_mulai',
        'tgl_kontrak_akhir',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'pejabat',
        'no_sk',
    ];

    protected $casts = [
        'sk_tmt' => 'date',
        'tgl_kontrak_mulai' => 'date',
        'tgl_kontrak_akhir' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
