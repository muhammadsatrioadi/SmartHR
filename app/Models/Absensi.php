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
        'tipe_absen',
        'keterangan',
        'tanggal_absensi',
        'time',
        'latitude',
        'longitude',
        'accuracy',
        'jarak_meter',
        'attendance_location_id',
        'device_fingerprint',
        'biometric_credential_id',
        'biometric_verified',
        'lokasi_dinas',
        'catatan',
        'user_agent',
    ];

    protected $casts = [
        'biometric_verified' => 'boolean',
        'lokasi_dinas' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'tanggal_absensi' => 'date',
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class);
    }
}
