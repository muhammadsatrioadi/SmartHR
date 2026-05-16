<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->decimal('kuota_hari', 5, 1)->default(0)->after('rekap');
        });

        Schema::create('attendance_locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedSmallInteger('radius_meter')->default(5);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->unsignedSmallInteger('tahun');
            $table->decimal('kuota', 5, 1)->default(0);
            $table->decimal('terpakai', 5, 1)->default(0);
            $table->decimal('sisa', 5, 1)->default(0);
            $table->timestamps();
            $table->unique(['karyawan_id', 'leave_type_id', 'tahun']);
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('device_fingerprint', 128)->unique();
            $table->string('device_label', 255)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('platform', 80)->nullable();
            $table->timestamp('registered_at');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis', ['perjanjian_absensi', 'task_list_flowchart']);
            $table->boolean('disetujui')->default(false);
            $table->timestamp('disetujui_pada')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'jenis']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->enum('tipe_absen', ['masuk', 'pulang'])->nullable()->after('status_absen');
            $table->decimal('latitude', 10, 7)->nullable()->after('time');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->unsignedSmallInteger('jarak_meter')->nullable();
            $table->foreignId('attendance_location_id')->nullable()->constrained('attendance_locations')->nullOnDelete();
            $table->string('device_fingerprint', 128)->nullable();
            $table->string('biometric_credential_id', 255)->nullable();
            $table->boolean('biometric_verified')->default(false);
            $table->boolean('lokasi_dinas')->default(false);
            $table->text('catatan')->nullable();
            $table->string('user_agent', 500)->nullable();
        });

        Schema::table('karyawans', function (Blueprint $table) {
            $table->foreignId('attendance_location_id')->nullable()->after('lokasi_tugas')
                ->constrained('attendance_locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('attendance_location_id');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('attendance_location_id');
            $table->dropColumn([
                'tipe_absen', 'latitude', 'longitude', 'accuracy', 'jarak_meter',
                'device_fingerprint', 'biometric_credential_id', 'biometric_verified',
                'lokasi_dinas', 'catatan', 'user_agent',
            ]);
        });

        Schema::dropIfExists('attendance_consents');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('employee_leave_balances');
        Schema::dropIfExists('attendance_locations');

        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn('kuota_hari');
        });
    }
};
