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
        Schema::table('overtimes', function (Blueprint $table) {
            $table->string('pilihan_pembayaran')->nullable()->after('status'); // bulan_ini, bulan_depan
            $table->string('bukti_screenshot')->nullable()->after('pilihan_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn(['pilihan_pembayaran', 'bukti_screenshot']);
        });
    }
};
