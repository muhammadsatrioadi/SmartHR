<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menyelaraskan role akun manajer departemen agar tampilan portal
     * (yang kini sepenuhnya mengikuti role akun) tetap konsisten.
     */
    public function up(): void
    {
        $managerIds = DB::table('departments')
            ->whereNotNull('manager_karyawan_id')
            ->pluck('manager_karyawan_id')
            ->unique();

        if ($managerIds->isEmpty()) {
            return;
        }

        $emails = DB::table('karyawans')
            ->whereIn('id', $managerIds)
            ->whereNotNull('email')
            ->pluck('email');

        if ($emails->isEmpty()) {
            return;
        }

        DB::table('users')
            ->whereIn('email', $emails)
            ->whereNotIn('role', ['admin_hr'])
            ->update(['role' => 'manajer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data tersinkron tidak dikembalikan (tidak ada jejak role sebelumnya).
    }
};
