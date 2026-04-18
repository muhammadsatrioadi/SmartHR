<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (!Schema::hasColumn('karyawans', 'nik')) {
                $table->string('nik', 50)->nullable()->unique()->after('id');
            }

            // Data Umum
            $table->string('nama_lengkap')->nullable()->after('name');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos', 10)->nullable();

            // Relasi Master
            $table->foreignId('golongan_id')->nullable()->constrained('golongans')->nullOnDelete();
            $table->foreignId('pangkat_id')->nullable()->constrained('pangkats')->nullOnDelete();
            $table->foreignId('employee_status_id')->nullable()->constrained('employee_statuses')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->nullOnDelete();

            // Data Kepegawaian ringkas (detail bisa dibuat tabel riwayat nanti)
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();

            // Payroll & Tax ringkas
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('golongan_id');
            $table->dropConstrainedForeignId('pangkat_id');
            $table->dropConstrainedForeignId('employee_status_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('work_unit_id');

            $table->dropColumn([
                'nik',
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'status_perkawinan',
                'alamat',
                'provinsi',
                'kota',
                'kecamatan',
                'kelurahan',
                'kode_pos',
                'tanggal_masuk',
                'tanggal_keluar',
                'alasan_keluar',
                'bank',
                'no_rekening',
                'bpjs_kesehatan',
                'bpjs_ketenagakerjaan',
            ]);
        });
    }
};
