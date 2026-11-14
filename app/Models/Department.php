<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'manager_karyawan_id',
        'is_active',
    ];

    // Manajer departemen (diatur oleh Admin HR)
    public function manager()
    {
        return $this->belongsTo(Karyawan::class, 'manager_karyawan_id');
    }

    public function workUnits()
    {
        return $this->hasMany(WorkUnit::class);
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
