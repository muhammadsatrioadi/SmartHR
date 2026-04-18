<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $addStr = function (Blueprint $table, $col, $len = 255) {
            if (!Schema::hasColumn('karyawans', $col)) {
                $table->string($col, $len)->nullable();
            }
        };
        $addDate = function (Blueprint $table, $col) {
            if (!Schema::hasColumn('karyawans', $col)) {
                $table->date($col)->nullable();
            }
        };
        $addBool = function (Blueprint $table, $col) {
            if (!Schema::hasColumn('karyawans', $col)) {
                $table->boolean($col)->default(false);
            }
        };
        $addText = function (Blueprint $table, $col) {
            if (!Schema::hasColumn('karyawans', $col)) {
                $table->text($col)->nullable();
            }
        };

        Schema::table('karyawans', function (Blueprint $table) use ($addStr, $addDate, $addBool, $addText) {
            // Data Umum
            $addStr($table, 'nbm', 50);
            $addBool($table, 'is_aktif_dinas');
            $addStr($table, 'hub_dengan_nik', 50);
            $addStr($table, 'nik_utama', 50);
            $addStr($table, 'pegawai_text', 100);
            $addStr($table, 'anak_isteri_ke', 20);
            $addBool($table, 'ditanggung');
            $addStr($table, 'handphone', 30);
            if (!Schema::hasColumn('karyawans', 'shift_group_id')) {
                $table->foreignId('shift_group_id')->nullable()->constrained('shift_groups')->nullOnDelete();
            }
            $addStr($table, 'no_identitas', 50);
            $addDate($table, 'tgl_ed_id');
            $addStr($table, 'nama_ayah', 150);
            $addStr($table, 'nama_ibu', 150);
            $addStr($table, 'ayah_mertua', 150);
            $addStr($table, 'ibu_mertua', 150);
            $addText($table, 'alamat_tinggal');
            $addDate($table, 'tgl_menikah');
            $addDate($table, 'tgl_cerai');
            $addStr($table, 'pekerjaan_suami_istri', 150);
            $addStr($table, 'golongan_darah', 10);
            $addStr($table, 'kabupaten', 150);

            // Data Kepegawaian
            $addStr($table, 'no_kartu_pegawai', 50);
            $addStr($table, 'no_sk_penempatan', 100);
            $addStr($table, 'divisi', 100);
            $addStr($table, 'bagian', 100);
            $addDate($table, 'tgl_unit_kerja');
            $addDate($table, 'tgl_orientasi_mulai');
            $addDate($table, 'tgl_orientasi_akhir');
            $addDate($table, 'tgl_kontrak_1_mulai');
            $addDate($table, 'tgl_kontrak_1_akhir');
            $addDate($table, 'tgl_kontrak_2_mulai');
            $addDate($table, 'tgl_kontrak_2_akhir');
            $addDate($table, 'tgl_pegawai_tetap');
            $addStr($table, 'no_sk_tetap', 100);
            $addStr($table, 'arsip', 100);
            $addStr($table, 'jaminan_polis', 100);
            $addStr($table, 'nik_atasan', 50);
            $addStr($table, 'lokasi_tugas', 150);
            $addStr($table, 'gelar', 50);
            $addStr($table, 'jenis_pegawai', 50);
            $addStr($table, 'grup_kinerja', 50);
            $addBool($table, 'is_full_time');
            $addStr($table, 'no_surat_registrasi', 100);
            $addDate($table, 'tgl_register_mulai');
            $addDate($table, 'tgl_register_akhir');
            $addStr($table, 'sk_ijin_kerja', 100);
            $addStr($table, 'no_sk_ijin_praktek', 100);
            $addDate($table, 'tgl_sk_ijin_praktek');
            $addDate($table, 'tgl_sk_ijin_praktek_berlaku_sd');
            $addDate($table, 'tgl_sk_ijin_kerja');
            $addDate($table, 'tgl_sk_ijin_kerja_berlaku_sd');
            $addStr($table, 'hak_kelas_ranap', 50);
            $addStr($table, 'no_amplop', 50);

            // PayRoll & TAX
            $addStr($table, 'hak_plafon', 50);
            $addStr($table, 'nama_account', 100);
            $addStr($table, 'jenis_profesi', 100);
            $addStr($table, 'keterangan_jabatan', 255);
            $addDate($table, 'tgl_daftar_npwp');
            $addStr($table, 'tanggungan_pajak', 50);
            $addBool($table, 'ptkp_tak_penuh');
            $addStr($table, 'status_kawin_tax', 50);

            // Riwayat Keluar
            $addStr($table, 'cara_keluar', 100);
        });
    }

    public function down(): void
    {
        $cols = [
            'nbm', 'is_aktif_dinas', 'hub_dengan_nik', 'nik_utama', 'pegawai_text', 'anak_isteri_ke', 'ditanggung',
            'handphone', 'no_identitas', 'tgl_ed_id', 'nama_ayah', 'nama_ibu', 'ayah_mertua', 'ibu_mertua',
            'alamat_tinggal', 'tgl_menikah', 'tgl_cerai', 'pekerjaan_suami_istri', 'golongan_darah', 'kabupaten',
            'no_kartu_pegawai', 'no_sk_penempatan', 'divisi', 'bagian', 'tgl_unit_kerja', 'tgl_orientasi_mulai',
            'tgl_orientasi_akhir', 'tgl_kontrak_1_mulai', 'tgl_kontrak_1_akhir', 'tgl_kontrak_2_mulai', 'tgl_kontrak_2_akhir',
            'tgl_pegawai_tetap', 'no_sk_tetap', 'arsip', 'jaminan_polis', 'nik_atasan', 'lokasi_tugas', 'gelar',
            'jenis_pegawai', 'grup_kinerja', 'is_full_time', 'no_surat_registrasi', 'tgl_register_mulai', 'tgl_register_akhir',
            'sk_ijin_kerja', 'no_sk_ijin_praktek', 'tgl_sk_ijin_praktek', 'tgl_sk_ijin_praktek_berlaku_sd',
            'tgl_sk_ijin_kerja', 'tgl_sk_ijin_kerja_berlaku_sd', 'hak_kelas_ranap', 'no_amplop',
            'hak_plafon', 'nama_account', 'jenis_profesi', 'keterangan_jabatan', 'tgl_daftar_npwp',
            'tanggungan_pajak', 'ptkp_tak_penuh', 'status_kawin_tax', 'cara_keluar', 'shift_group_id'
        ];
        $toDrop = array_filter($cols, fn ($c) => Schema::hasColumn('karyawans', $c));
        if (!empty($toDrop)) {
            Schema::table('karyawans', function (Blueprint $table) use ($toDrop) {
                if (in_array('shift_group_id', $toDrop)) {
                    $table->dropForeign(['shift_group_id']);
                }
                $table->dropColumn($toDrop);
            });
        }
    }
};
