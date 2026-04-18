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
        Schema::create('employee_salary_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('salary_step_id')->nullable()->constrained('salary_steps')->nullOnDelete();
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan_tetap', 15, 2)->default(0);
            $table->decimal('tunjangan_lain', 15, 2)->default(0);
            $table->date('tanggal_berlaku');
            $table->string('alasan', 150)->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_histories');
    }
};
