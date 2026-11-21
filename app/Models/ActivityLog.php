<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Jejak audit perubahan data sensitif (khususnya gaji) agar setiap
 * perubahan dapat ditelusuri ke pengguna, waktu, dan nilai sebelum/sesudah.
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modul',
        'aksi',
        'referensi',
        'karyawan_id',
        'keterangan',
        'perubahan',
        'ip',
    ];

    protected $casts = [
        'perubahan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
