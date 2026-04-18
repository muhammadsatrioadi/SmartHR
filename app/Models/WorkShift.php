<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_group_id',
        'kode',
        'nama',
        'jam_masuk',
        'jam_pulang',
        'jam_mulai_telat',
        'jam_masuk_cepat',
        'toleransi_telat_menit',
        'is_aktif',
    ];

    public function shiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class);
    }
}
