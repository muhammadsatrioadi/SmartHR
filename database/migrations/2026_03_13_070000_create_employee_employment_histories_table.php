<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_employment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->string('pangkat', 100)->nullable();
            $table->string('golongan', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->date('sk_tmt')->nullable();
            $table->date('tgl_kontrak_mulai')->nullable();
            $table->date('tgl_kontrak_akhir')->nullable();
            $table->unsignedTinyInteger('masa_kerja_tahun')->nullable();
            $table->unsignedTinyInteger('masa_kerja_bulan')->nullable();
            $table->string('pejabat', 150)->nullable();
            $table->string('no_sk', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_employment_histories');
    }
};
