<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'grup',
        'pakai_periode',
        'satuan_periode',
        'max_expired',
        'satuan_expired',
        'min_masa_kerja',
        'satuan_masa_kerja',
        'max_backdate',
        'rekap',
    ];
}
