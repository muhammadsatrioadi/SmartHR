<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('jenis_kelamin');
            $table->string('telephone');
            $table->string('status');
            $table->foreignId('jabatan_id')->constrained('jabatans');
            $table->string('ktp');
            $table->string('NPWP');
            $table->integer('total_kontak');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawans');
    }
};
