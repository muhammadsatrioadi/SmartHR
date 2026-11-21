<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'is_payroll',
        'is_active',
        'dapat_tunjangan_keluarga',
        'dapat_dana_pensiun',
    ];

    protected $casts = [
        'is_payroll' => 'boolean',
        'is_active' => 'boolean',
        'dapat_tunjangan_keluarga' => 'boolean',
        'dapat_dana_pensiun' => 'boolean',
    ];
}
