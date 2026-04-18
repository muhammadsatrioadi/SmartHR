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
        Schema::table('cutis', function (Blueprint $table) {
            $table->foreignId('leave_type_id')->nullable()->after('karyawan_id')->constrained('leave_types')->nullOnDelete();
            $table->string('status', 30)->default('menunggu_atasan')->after('jenis_cuti');

            $table->unsignedInteger('saldo_awal')->default(0)->after('status');
            $table->unsignedInteger('hak_diambil')->default(0)->after('saldo_awal');
            $table->unsignedInteger('saldo_sisa')->default(0)->after('hak_diambil');

            $table->foreignId('approved_by_supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_supervisor')->nullable();
            $table->foreignId('approved_by_hr_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_hr')->nullable();

            $table->foreignId('rejected_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('rejected_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cutis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('leave_type_id');
            $table->dropColumn([
                'status',
                'saldo_awal',
                'hak_diambil',
                'saldo_sisa',
                'approved_at_supervisor',
                'approved_at_hr',
                'rejected_reason',
            ]);
            $table->dropConstrainedForeignId('approved_by_supervisor_id');
            $table->dropConstrainedForeignId('approved_by_hr_id');
            $table->dropConstrainedForeignId('rejected_by_id');
        });
    }
};
