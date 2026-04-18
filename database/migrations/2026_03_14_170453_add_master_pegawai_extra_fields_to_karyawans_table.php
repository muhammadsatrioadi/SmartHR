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
            if (!Schema::hasColumn('karyawans', 'handphone')) {
                $table->string('handphone', 30)->nullable()->after('telephone');
            }
 
            // tambahkan kolom lain yang belum ada tapi dipakai di form/controller:
            // contoh:
            // if (!Schema::hasColumn('karyawans', 'alamat_tinggal')) {
            //     $table->text('alamat_tinggal')->nullable()->after('alamat');
            // }
        });
    }
 
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn([
                'handphone',
                // dan kolom lain yang Anda tambahkan di up()
            ]);
        });
    }
};
