<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'kode',
        'nama',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
