<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'tipe',
        'nominal',
        'keterangan',
        'lampiran',
        'status',
        'approved_by_supervisor_id',
        'approved_at_supervisor',
        'approved_by_hr_id',
        'approved_at_hr',
        'rejected_by_id',
        'rejected_reason',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'approved_at_supervisor' => 'datetime',
        'approved_at_hr' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'approved_by_supervisor_id');
    }

    public function hr()
    {
        return $this->belongsTo(User::class, 'approved_by_hr_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by_id');
    }
}
