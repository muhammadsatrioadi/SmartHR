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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('jumlah_jam', 5, 2)->default(0);
            $table->enum('jenis_hari', ['hari_kerja', 'hari_libur', 'hari_raya'])->default('hari_kerja');
            $table->string('keterangan_pekerjaan', 255);
            $table->string('status', 30)->default('menunggu_approval');
            $table->foreignId('approved_by_supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_supervisor')->nullable();
            $table->foreignId('approved_by_hr_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_hr')->nullable();
            $table->foreignId('rejected_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('rejected_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
