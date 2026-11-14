<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Selaraskan penunjukan manajer dan NIK atasan dengan role akun terbaru.
     */
    public function up(): void
    {
        foreach (DB::table('departments')->get() as $department) {
            $managerCandidates = DB::table('karyawans as k')
                ->join('users as u', 'u.email', '=', 'k.email')
                ->where('k.department_id', $department->id)
                ->whereIn('u.role', ['atasan', 'manajer'])
                ->select('k.id', 'k.nik')
                ->distinct()
                ->get();

            $currentManagerIsEligible = $department->manager_karyawan_id
                && DB::table('karyawans as k')
                    ->join('users as u', 'u.email', '=', 'k.email')
                    ->where('k.id', $department->manager_karyawan_id)
                    ->whereIn('u.role', ['atasan', 'manajer'])
                    ->exists();

            $managerId = $department->manager_karyawan_id;
            $managerNik = null;

            if (!$currentManagerIsEligible) {
                $manager = $managerCandidates->count() === 1
                    ? $managerCandidates->first()
                    : null;
                $managerId = $manager?->id;
                $managerNik = $manager?->nik;

                DB::table('departments')
                    ->where('id', $department->id)
                    ->update(['manager_karyawan_id' => $managerId]);
            } elseif ($managerId) {
                $managerNik = DB::table('karyawans')
                    ->where('id', $managerId)
                    ->value('nik');
            }

            DB::table('karyawans')
                ->where('department_id', $department->id)
                ->when($managerId, fn ($query) => $query->where('id', '!=', $managerId))
                ->update(['nik_atasan' => $managerNik]);

            if ($managerId) {
                DB::table('karyawans')
                    ->where('id', $managerId)
                    ->where('department_id', $department->id)
                    ->update(['nik_atasan' => null]);
            }
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Role dan atasan mungkin sudah diperbarui manual setelah migrasi.
    }
};
