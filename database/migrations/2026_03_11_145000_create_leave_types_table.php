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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->string('grup', 50)->nullable();
            $table->boolean('pakai_periode')->default(false);
            $table->enum('satuan_periode', ['hari', 'bulan', 'tahun'])->nullable();
            $table->integer('max_expired')->nullable();
            $table->enum('satuan_expired', ['hari', 'bulan', 'tahun'])->nullable();
            $table->integer('min_masa_kerja')->nullable();
            $table->enum('satuan_masa_kerja', ['hari', 'bulan', 'tahun'])->nullable();
            $table->integer('max_backdate')->nullable();
            $table->boolean('rekap')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
