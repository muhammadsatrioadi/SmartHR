<?php

namespace App\Support;

use App\Models\Karyawan;
use App\Models\User;

class KaryawanResolver
{
    public static function fromUser(?User $user): ?Karyawan
    {
        if (!$user) {
            return null;
        }

        return Karyawan::where('email', $user->email)->first();
    }
}
