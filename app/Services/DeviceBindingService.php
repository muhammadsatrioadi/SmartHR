<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class DeviceBindingService
{
    public function registerOrValidate(User $user, string $fingerprint, Request $request): array
    {
        $existing = UserDevice::where('user_id', $user->id)->first();

        if (!$existing) {
            $device = UserDevice::create([
                'user_id' => $user->id,
                'device_fingerprint' => $fingerprint,
                'device_label' => $request->input('device_label'),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'platform' => $request->input('platform'),
                'registered_at' => now(),
                'last_used_at' => now(),
            ]);

            return ['ok' => true, 'registered' => true, 'device' => $device];
        }

        if ($existing->device_fingerprint !== $fingerprint) {
            return [
                'ok' => false,
                'message' => 'Akun ini terdaftar di perangkat lain. Satu email hanya untuk satu perangkat.',
            ];
        }

        $existing->update([
            'last_used_at' => now(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return ['ok' => true, 'registered' => false, 'device' => $existing];
    }

    public function isBound(User $user): bool
    {
        return UserDevice::where('user_id', $user->id)->exists();
    }
}
