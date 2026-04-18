<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kode_libur',
        'keterangan',
        'is_tetap',
        'is_hari_raya',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_tetap' => 'boolean',
        'is_hari_raya' => 'boolean',
    ];
}
