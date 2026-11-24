<?php

namespace App\Support;

class UiLabels
{
    public static function status(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        $label = match ($status) {
            'menunggu_approval', 'menunggu_persetujuan' => 'Menunggu Approval',
            'menunggu_atasan', 'menunggu_supervisor' => 'Menunggu Atasan',
            'menunggu_hr', 'menunggu_admin_hr' => 'Menunggu HR',
            'disetujui', 'approved' => 'Disetujui',
            'ditolak', 'rejected' => 'Ditolak',
            'hadir', 'present' => 'Hadir',
            'alpha', 'absent' => 'Alpha',
            'cuti', 'leave' => 'Cuti',
            'aktif', 'active' => 'Aktif',
            'nonaktif', 'tidak_aktif', 'inactive' => 'Tidak Aktif',
            default => ucfirst(str_replace('_', ' ', $status)),
        };

        return __($label);
    }
}
