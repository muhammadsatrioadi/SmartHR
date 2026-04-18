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
        Schema::create('salary_steps', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('deskripsi', 150)->nullable();
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan_tetap', 15, 2)->default(0);
            $table->unsignedInteger('masa_kerja_min')->default(0);
            $table->unsignedInteger('masa_kerja_maks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_steps');
    }
};
