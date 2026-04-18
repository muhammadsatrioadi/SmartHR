<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'shift_group_id',
        'work_shift_id',
        'tanggal',
        'is_libur',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function shiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class);
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }
}
