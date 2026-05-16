<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceLocation extends Model
{
    protected $fillable = [
        'nama',
        'latitude',
        'longitude',
        'radius_meter',
        'is_aktif',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_aktif' => 'boolean',
    ];

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
