<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'status_absen',
        'keterangan',
        'tanggal_absensi',
        'time',
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class);
    }
}
