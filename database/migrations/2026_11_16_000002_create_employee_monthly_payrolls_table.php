<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rekap bulanan (kelompok 3 pada panduan komponen gaji): kehadiran, on call,
 * pendapatan lain, PPh 21, dan potongan lain yang disepakati.
 * Uang lembur tidak disimpan di sini karena diambil otomatis dari modul Lembur.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_monthly_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->char('periode', 7); // YYYY-MM

            // Kehadiran untuk tunjangan kehadiran (THD)
            $table->unsignedSmallInteger('hari_pagi')->default(0);
            $table->unsignedSmallInteger('hari_siang')->default(0);
            $table->unsignedSmallInteger('hari_malam')->default(0);
            $table->unsignedSmallInteger('hari_telat_pulang_awal')->default(0);
            $table->boolean('is_koordinator')->default(false);

            // Pendapatan yang berubah bulanan
            $table->decimal('nominal_on_call', 15, 2)->default(0);
            $table->decimal('nominal_cuti_besar', 15, 2)->default(0);
            $table->decimal('nominal_rapel', 15, 2)->default(0);
            $table->decimal('nominal_insentif_hari_raya', 15, 2)->default(0);
            $table->decimal('nominal_pendapatan_lain', 15, 2)->default(0);

            // Potongan resmi (PPh 21 diisi admin HR)
            $table->decimal('nominal_pph21', 15, 2)->default(0);

            // Potongan lain (D). Infaq dikosongkan = dihitung 2,5% x dasar perhitungan
            $table->decimal('potongan_infaq', 15, 2)->nullable();
            $table->decimal('potongan_angsuran_pinjaman', 15, 2)->default(0);
            $table->decimal('potongan_bank_syariah', 15, 2)->default(0);
            $table->decimal('potongan_koperasi', 15, 2)->default(0);
            $table->decimal('potongan_farmasi', 15, 2)->default(0);
            $table->decimal('potongan_bsi', 15, 2)->default(0);
            $table->decimal('potongan_perumahan', 15, 2)->default(0);
            $table->decimal('potongan_bsm', 15, 2)->default(0);
            $table->decimal('potongan_lain', 15, 2)->default(0);

            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->foreignId('disiapkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('difinalkan_pada')->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_monthly_payrolls');
    }
};
