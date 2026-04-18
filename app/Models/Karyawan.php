<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'nama_lengkap',
        'email',
        'jenis_kelamin',
        'telephone',
        'handphone',
        'status',
        'jabatan_id',
        'ktp',
        'NPWP',
        'total_kontak',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'alamat',
        'alamat_tinggal',
        'provinsi',
        'kota',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'golongan_id',
        'pangkat_id',
        'employee_status_id',
        'department_id',
        'work_unit_id',
        'shift_group_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'cara_keluar',
        'bank',
        'no_rekening',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'nbm',
        'is_aktif_dinas',
        'hub_dengan_nik',
        'nik_utama',
        'pegawai_text',
        'anak_isteri_ke',
        'ditanggung',
        'no_identitas',
        'tgl_ed_id',
        'nama_ayah',
        'nama_ibu',
        'ayah_mertua',
        'ibu_mertua',
        'tgl_menikah',
        'tgl_cerai',
        'pekerjaan_suami_istri',
        'golongan_darah',
        'no_kartu_pegawai',
        'no_sk_penempatan',
        'divisi',
        'bagian',
        'tgl_unit_kerja',
        'tgl_orientasi_mulai',
        'tgl_orientasi_akhir',
        'tgl_kontrak_1_mulai',
        'tgl_kontrak_1_akhir',
        'tgl_kontrak_2_mulai',
        'tgl_kontrak_2_akhir',
        'tgl_pegawai_tetap',
        'no_sk_tetap',
        'arsip',
        'jaminan_polis',
        'nik_atasan',
        'lokasi_tugas',
        'gelar',
        'jenis_pegawai',
        'grup_kinerja',
        'is_full_time',
        'no_surat_registrasi',
        'tgl_register_mulai',
        'tgl_register_akhir',
        'sk_ijin_kerja',
        'no_sk_ijin_praktek',
        'tgl_sk_ijin_praktek',
        'tgl_sk_ijin_praktek_berlaku_sd',
        'tgl_sk_ijin_kerja',
        'tgl_sk_ijin_kerja_berlaku_sd',
        'hak_kelas_ranap',
        'no_amplop',
        'hak_plafon',
        'nama_account',
        'jenis_profesi',
        'keterangan_jabatan',
        'tgl_daftar_npwp',
        'tanggungan_pajak',
        'ptkp_tak_penuh',
        'status_kawin_tax',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'tgl_ed_id' => 'date',
        'tgl_menikah' => 'date',
        'tgl_cerai' => 'date',
        'tgl_unit_kerja' => 'date',
        'tgl_orientasi_mulai' => 'date',
        'tgl_orientasi_akhir' => 'date',
        'tgl_kontrak_1_mulai' => 'date',
        'tgl_kontrak_1_akhir' => 'date',
        'tgl_kontrak_2_mulai' => 'date',
        'tgl_kontrak_2_akhir' => 'date',
        'tgl_pegawai_tetap' => 'date',
        'tgl_register_mulai' => 'date',
        'tgl_register_akhir' => 'date',
        'tgl_sk_ijin_praktek' => 'date',
        'tgl_sk_ijin_praktek_berlaku_sd' => 'date',
        'tgl_sk_ijin_kerja' => 'date',
        'tgl_sk_ijin_kerja_berlaku_sd' => 'date',
        'tgl_daftar_npwp' => 'date',
        'is_aktif_dinas' => 'boolean',
        'ditanggung' => 'boolean',
        'is_full_time' => 'boolean',
        'ptkp_tak_penuh' => 'boolean',
    ];

    // Relasi dengan Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class);
    }

    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeStatus::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function shiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class);
    }

    public function employmentHistories()
    {
        return $this->hasMany(EmployeeEmploymentHistory::class, 'karyawan_id');
    }

    // Relasi dengan Absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Relasi dengan Cuti
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'karyawan_id');
    }

    // Relasi dengan Gaji
    public function gajis()
    {
        return $this->hasMany(Gaji::class);
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    public function salaryHistories()
    {
        return $this->hasMany(EmployeeSalaryHistory::class);
    }
}
