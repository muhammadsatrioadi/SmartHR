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
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_group_id')->nullable()->constrained('shift_groups')->nullOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('jam_mulai_telat')->nullable();
            $table->time('jam_masuk_cepat')->nullable();
            $table->integer('toleransi_telat_menit')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
    }
};
