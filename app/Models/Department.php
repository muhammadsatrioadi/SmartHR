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
        'is_active',
    ];

    public function workUnits()
    {
        return $this->hasMany(WorkUnit::class);
    }
}
