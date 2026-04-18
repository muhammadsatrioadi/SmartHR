<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (!Schema::hasColumn('karyawans', 'shift_group_id')) {
                $table->foreignId('shift_group_id')
                    ->nullable()
                    ->after('work_unit_id')
                    ->constrained('shift_groups')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (Schema::hasColumn('karyawans', 'shift_group_id')) {
                $table->dropForeign(['shift_group_id']);
                $table->dropColumn('shift_group_id');
            }
        });
    }
};
