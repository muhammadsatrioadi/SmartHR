<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'leave_type_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'keterangan',
        'jenis_cuti',
        'status',
        'saldo_awal',
        'hak_diambil',
        'saldo_sisa',
        'approved_by_supervisor_id',
        'approved_at_supervisor',
        'approved_by_hr_id',
        'approved_at_hr',
        'rejected_by_id',
        'rejected_reason',
    ];

    // Relasi ke karyawans
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
