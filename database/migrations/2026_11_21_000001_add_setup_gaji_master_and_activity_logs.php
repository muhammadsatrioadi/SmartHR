<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menjadikan Setup Gaji (per jabatan) sebagai sumber komponen awal karyawan,
 * menandai status kepegawaian yang berhak atas tunjangan keluarga/dana pensiun,
 * dan menyediakan log audit untuk perubahan data gaji.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0)->after('gaji_pokok');
            $table->decimal('tunjangan_fungsional', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('tunjangan_profesi', 15, 2)->default(0)->after('tunjangan_fungsional');
            $table->decimal('tunjangan_makan', 15, 2)->default(0)->after('tunjangan_profesi');
            $table->decimal('tunjangan_pulsa', 15, 2)->default(0)->after('tunjangan_makan');
            $table->decimal('tkp', 15, 2)->default(0)->after('tunjangan_pulsa');
            $table->unsignedTinyInteger('hari_kerja_per_minggu')->default(5)->after('tkp');
        });

        Schema::table('employee_statuses', function (Blueprint $table) {
            $table->boolean('dapat_tunjangan_keluarga')->default(true)->after('is_payroll');
            $table->boolean('dapat_dana_pensiun')->default(true)->after('dapat_tunjangan_keluarga');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('modul', 50);
            $table->string('aksi', 50);
            $table->string('referensi', 150)->nullable();
            $table->unsignedBigInteger('karyawan_id')->nullable()->index();
            $table->text('keterangan')->nullable();
            $table->json('perubahan')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');

        Schema::table('employee_statuses', function (Blueprint $table) {
            $table->dropColumn(['dapat_tunjangan_keluarga', 'dapat_dana_pensiun']);
        });

        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn([
                'tunjangan_jabatan',
                'tunjangan_fungsional',
                'tunjangan_profesi',
                'tunjangan_makan',
                'tunjangan_pulsa',
                'tkp',
                'hari_kerja_per_minggu',
            ]);
        });
    }
};
