<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMonthlyPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'periode',
        'hari_pagi',
        'hari_siang',
        'hari_malam',
        'hari_telat_pulang_awal',
        'is_koordinator',
        'nominal_on_call',
        'nominal_cuti_besar',
        'nominal_rapel',
        'nominal_insentif_hari_raya',
        'nominal_pendapatan_lain',
        'nominal_pph21',
        'potongan_infaq',
        'potongan_angsuran_pinjaman',
        'potongan_bank_syariah',
        'potongan_koperasi',
        'potongan_farmasi',
        'potongan_bsi',
        'potongan_perumahan',
        'potongan_bsm',
        'potongan_lain',
        'catatan',
        'status',
        'disiapkan_oleh',
        'difinalkan_pada',
    ];

    protected $casts = [
        'is_koordinator' => 'boolean',
        'difinalkan_pada' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penyusun()
    {
        return $this->belongsTo(User::class, 'disiapkan_oleh');
    }

    /**
     * Nama bulan dalam bahasa Indonesia untuk periode YYYY-MM.
     */
    public function labelPeriode(): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        [$tahun, $angkaBulan] = array_pad(explode('-', (string) $this->periode), 2, '01');

        return ($bulan[(int) $angkaBulan] ?? $angkaBulan) . ' ' . $tahun;
    }
}
