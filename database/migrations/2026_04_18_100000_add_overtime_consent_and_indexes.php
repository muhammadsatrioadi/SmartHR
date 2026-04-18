<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->boolean('pegawai_telah_menyetujui')->default(false)->after('keterangan_pekerjaan');
            $table->string('referensi_persetujuan_pegawai', 255)->nullable()->after('pegawai_telah_menyetujui');
            $table->index(['karyawan_id', 'tanggal']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropIndex(['karyawan_id', 'tanggal']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'pegawai_telah_menyetujui',
                'referensi_persetujuan_pegawai',
            ]);
        });
    }
};
