<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_steps', function (Blueprint $table) {
            $table->unsignedTinyInteger('hari_kerja_per_minggu')->default(5)->after('tunjangan_tetap');
        });

        Schema::table('employee_salary_histories', function (Blueprint $table) {
            $table->unsignedTinyInteger('hari_kerja_per_minggu')->default(5)->after('tunjangan_lain');
        });

        Schema::table('overtimes', function (Blueprint $table) {
            $table->unsignedTinyInteger('hari_kerja_per_minggu')->default(5)->after('jenis_hari');
            $table->boolean('is_hari_kerja_terpendek')->default(false)->after('hari_kerja_per_minggu');
            $table->decimal('dasar_upah_lembur', 15, 2)->default(0)->after('is_hari_kerja_terpendek');
            $table->decimal('upah_per_jam', 15, 2)->default(0)->after('dasar_upah_lembur');
            $table->decimal('nominal_lembur', 15, 2)->default(0)->after('upah_per_jam');
        });
    }

    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn([
                'hari_kerja_per_minggu',
                'is_hari_kerja_terpendek',
                'dasar_upah_lembur',
                'upah_per_jam',
                'nominal_lembur',
            ]);
        });

        Schema::table('employee_salary_histories', function (Blueprint $table) {
            $table->dropColumn('hari_kerja_per_minggu');
        });

        Schema::table('salary_steps', function (Blueprint $table) {
            $table->dropColumn('hari_kerja_per_minggu');
        });
    }
};
