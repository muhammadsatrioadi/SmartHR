<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Hubungkan departemen dengan satu-satunya akun manajer/atasan
     * yang tercatat sebagai karyawan di departemen tersebut.
     */
    public function up(): void
    {
        $managersByDepartment = DB::table('karyawans as k')
            ->join('users as u', 'u.email', '=', 'k.email')
            ->whereIn('u.role', ['manajer', 'atasan'])
            ->whereNotNull('k.department_id')
            ->select('k.department_id')
            ->selectRaw('MIN(k.id) as manager_karyawan_id')
            ->groupBy('k.department_id')
            ->havingRaw('COUNT(DISTINCT k.id) = 1')
            ->get();

        foreach ($managersByDepartment as $manager) {
            DB::table('departments')
                ->where('id', $manager->department_id)
                ->whereNull('manager_karyawan_id')
                ->update(['manager_karyawan_id' => $manager->manager_karyawan_id]);
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Penunjukan manajer tidak dihapus karena bisa saja sudah diedit manual.
    }
};
