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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('tipe')->default('nota'); // nota, dll
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['pending', 'disetujui_atasan', 'disetujui', 'ditolak'])->default('pending');
            
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
        Schema::dropIfExists('reimbursements');
    }
};
