<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Komponen tetap pada "data gaji dasar" dipecah mengikuti panduan komponen gaji:
 * tunjangan jabatan, fungsional, profesi, makan, TKP, dan pulsa.
 * Status keluarga disimpan eksplisit agar perhitungan tunjangan keluarga
 * (pasangan & anak) dan dasar perhitungan BPJS/infaq bersifat pasti.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_salary_histories', function (Blueprint $table) {
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0)->after('tunjangan_lain');
            $table->decimal('tunjangan_fungsional', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('tunjangan_profesi', 15, 2)->default(0)->after('tunjangan_fungsional');
            $table->decimal('tunjangan_makan', 15, 2)->default(0)->after('tunjangan_profesi');
            $table->decimal('tunjangan_pulsa', 15, 2)->default(0)->after('tunjangan_makan');
            $table->decimal('tkp', 15, 2)->default(0)->after('tunjangan_pulsa');
            $table->boolean('pasangan_ditanggung')->default(false)->after('tkp');
            $table->unsignedTinyInteger('jumlah_anak_ditanggung')->default(0)->after('pasangan_ditanggung');
        });
    }

    public function down(): void
    {
        Schema::table('employee_salary_histories', function (Blueprint $table) {
            $table->dropColumn([
                'tunjangan_jabatan',
                'tunjangan_fungsional',
                'tunjangan_profesi',
                'tunjangan_makan',
                'tunjangan_pulsa',
                'tkp',
                'pasangan_ditanggung',
                'jumlah_anak_ditanggung',
            ]);
        });
    }
};
