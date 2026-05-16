<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceConsent extends Model
{
    protected $fillable = [
        'user_id',
        'jenis',
        'disetujui',
        'disetujui_pada',
        'ip_address',
        'catatan',
    ];

    protected $casts = [
        'disetujui' => 'boolean',
        'disetujui_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
