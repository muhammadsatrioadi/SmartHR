<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_jam',
        'jenis_hari',
        'hari_kerja_per_minggu',
        'is_hari_kerja_terpendek',
        'dasar_upah_lembur',
        'upah_per_jam',
        'nominal_lembur',
        'keterangan_pekerjaan',
        'pegawai_telah_menyetujui',
        'referensi_persetujuan_pegawai',
        'status',
        'approved_by_supervisor_id',
        'approved_at_supervisor',
        'approved_by_hr_id',
        'approved_at_hr',
        'rejected_by_id',
        'rejected_reason',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_jam' => 'decimal:2',
        'pegawai_telah_menyetujui' => 'boolean',
        'is_hari_kerja_terpendek' => 'boolean',
        'dasar_upah_lembur' => 'decimal:2',
        'upah_per_jam' => 'decimal:2',
        'nominal_lembur' => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
