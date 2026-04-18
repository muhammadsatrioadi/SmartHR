<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            // Helper kecil
            $addStr = function (string $name, int $len = 255) use ($table) {
                if (!Schema::hasColumn('karyawans', $name)) {
                    $table->string($name, $len)->nullable();
                }
            };
            $addText = function (string $name) use ($table) {
                if (!Schema::hasColumn('karyawans', $name)) {
                    $table->text($name)->nullable();
                }
            };
            $addDate = function (string $name) use ($table) {
                if (!Schema::hasColumn('karyawans', $name)) {
                    $table->date($name)->nullable();
                }
            };
            $addBool = function (string $name) use ($table) {
                if (!Schema::hasColumn('karyawans', $name)) {
                    $table->boolean($name)->default(false);
                }
            };
            $addInt = function (string $name) use ($table) {
                if (!Schema::hasColumn('karyawans', $name)) {
                    $table->integer($name)->nullable();
                }
            };

            // Sudah ada di migration lama, tapi kita pastikan:
            $addStr('nik', 50);
            $addStr('nama_lengkap', 255);
            $addStr('alamat', 255);
            $addStr('kota', 150);

            // Tambahan Master Pegawai (Data Umum)
            $addStr('handphone', 30);
            $addText('alamat_tinggal');
            $addStr('provinsi', 150);
            $addStr('kabupaten', 150);
            $addStr('kecamatan', 150);
            $addStr('kelurahan', 150);
            $addStr('kode_pos', 10);

            $addStr('nbm', 50);
            $addBool('is_aktif_dinas');
            $addStr('hub_dengan_nik', 50);
            $addStr('nik_utama', 50);
            $addStr('pegawai_text', 100);
            $addStr('anak_isteri_ke', 20);
            $addBool('ditanggung');
            $addStr('no_identitas', 50);
            $addDate('tgl_ed_id');
            $addStr('nama_ayah', 150);
            $addStr('nama_ibu', 150);
            $addStr('ayah_mertua', 150);
            $addStr('ibu_mertua', 150);
            $addDate('tgl_menikah');
            $addDate('tgl_cerai');
            $addStr('pekerjaan_suami_istri', 150);
            $addStr('golongan_darah', 10);

            // Relasi master
            if (!Schema::hasColumn('karyawans', 'golongan_id')) {
                $table->foreignId('golongan_id')->nullable()->constrained('golongans')->nullOnDelete();
            }
            if (!Schema::hasColumn('karyawans', 'pangkat_id')) {
                $table->foreignId('pangkat_id')->nullable()->constrained('pangkats')->nullOnDelete();
            }
            if (!Schema::hasColumn('karyawans', 'employee_status_id')) {
                $table->foreignId('employee_status_id')->nullable()->constrained('employee_statuses')->nullOnDelete();
            }
            if (!Schema::hasColumn('karyawans', 'department_id')) {
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            }
            if (!Schema::hasColumn('karyawans', 'work_unit_id')) {
                $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->nullOnDelete();
            }
            if (!Schema::hasColumn('karyawans', 'shift_group_id')) {
                $table->foreignId('shift_group_id')->nullable()->constrained('shift_groups')->nullOnDelete();
            }

            // Data Kepegawaian
            $addDate('tanggal_masuk');
            $addDate('tanggal_keluar');
            $addText('alasan_keluar');
            $addStr('cara_keluar', 100);

            $addStr('no_kartu_pegawai', 50);
            $addStr('no_sk_penempatan', 100);
            $addStr('divisi', 100);
            $addStr('bagian', 100);
            $addDate('tgl_unit_kerja');
            $addDate('tgl_orientasi_mulai');
            $addDate('tgl_orientasi_akhir');
            $addDate('tgl_kontrak_1_mulai');
            $addDate('tgl_kontrak_1_akhir');
            $addDate('tgl_kontrak_2_mulai');
            $addDate('tgl_kontrak_2_akhir');
            $addDate('tgl_pegawai_tetap');
            $addStr('no_sk_tetap', 100);
            $addStr('arsip', 100);
            $addStr('jaminan_polis', 100);
            $addStr('nik_atasan', 50);
            $addStr('lokasi_tugas', 150);
            $addStr('gelar', 50);
            $addStr('jenis_pegawai', 50);
            $addStr('grup_kinerja', 50);
            $addBool('is_full_time');
            $addStr('no_surat_registrasi', 100);
            $addDate('tgl_register_mulai');
            $addDate('tgl_register_akhir');
            $addStr('sk_ijin_kerja', 100);
            $addStr('no_sk_ijin_praktek', 100);
            $addDate('tgl_sk_ijin_praktek');
            $addDate('tgl_sk_ijin_praktek_berlaku_sd');
            $addDate('tgl_sk_ijin_kerja');
            $addDate('tgl_sk_ijin_kerja_berlaku_sd');
            $addStr('hak_kelas_ranap', 50);
            $addStr('no_amplop', 50);

            // PayRoll & Tax
            $addStr('bank', 100);
            $addStr('no_rekening', 100);
            $addStr('bpjs_kesehatan', 100);
            $addStr('bpjs_ketenagakerjaan', 100);
            $addStr('hak_plafon', 50);
            $addStr('nama_account', 100);
            $addStr('jenis_profesi', 100);
            $addStr('keterangan_jabatan', 255);
            $addDate('tgl_daftar_npwp');
            $addStr('tanggungan_pajak', 50);
            $addBool('ptkp_tak_penuh');
            $addStr('status_kawin_tax', 50);
        });
    }

    public function down(): void
    {
        // Tidak perlu rollback detail satu-satu, cukup dibiarkan
    }
};