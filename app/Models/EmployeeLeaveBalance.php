<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLeaveBalance extends Model
{
    protected $fillable = [
        'karyawan_id',
        'leave_type_id',
        'tahun',
        'kuota',
        'terpakai',
        'sisa',
    ];

    protected $casts = [
        'kuota' => 'float',
        'terpakai' => 'float',
        'sisa' => 'float',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
