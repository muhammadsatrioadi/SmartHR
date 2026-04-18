<?php

namespace Database\Seeders;

use App\Models\Cuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class cutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cutis = [
            [
                'karyawan_id' => 2,
                'tanggal_mulai' => '2024-07-01',
                'tanggal_berakhir' => '2024-07-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 2,
                'tanggal_mulai' => '2024-06-10',
                'tanggal_berakhir' => '2024-06-15',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 3,
                'tanggal_mulai' => '2024-08-01',
                'tanggal_berakhir' => '2024-08-07',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 4,
                'tanggal_mulai' => '2024-09-05',
                'tanggal_berakhir' => '2024-09-10',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 5,
                'tanggal_mulai' => '2024-10-15',
                'tanggal_berakhir' => '2024-10-20',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 6,
                'tanggal_mulai' => '2024-11-01',
                'tanggal_berakhir' => '2024-11-05',
                'keterangan' => 'Cuti sakit',
                'jenis_cuti' => 'Sakit',
            ],
            [
                'karyawan_id' => 7,
                'tanggal_mulai' => '2024-12-01',
                'tanggal_berakhir' => '2024-12-07',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 8,
                'tanggal_mulai' => '2024-01-01',
                'tanggal_berakhir' => '2024-01-05',
                'keterangan' => 'Cuti sakit',
                'jenis_cuti' => 'Sakit',
            ],
            [
                'karyawan_id' => 9,
                'tanggal_mulai' => '2024-02-01',
                'tanggal_berakhir' => '2024-02-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 10,
                'tanggal_mulai' => '2024-03-01',
                'tanggal_berakhir' => '2024-03-05',
                'keterangan' => 'Cuti menikah',
                'jenis_cuti' => 'Menikah',
            ],
            [
                'karyawan_id' => 11,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_berakhir' => '2024-04-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 12,
                'tanggal_mulai' => '2024-05-01',
                'tanggal_berakhir' => '2024-05-05',
                'keterangan' => 'Cuti sakit',
                'jenis_cuti' => 'Sakit',
            ],
            [
                'karyawan_id' => 13,
                'tanggal_mulai' => '2024-06-01',
                'tanggal_berakhir' => '2024-06-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 14,
                'tanggal_mulai' => '2024-07-01',
                'tanggal_berakhir' => '2024-07-07',
                'keterangan' => 'Cuti menikah',
                'jenis_cuti' => 'Menikah',
            ],
            [
                'karyawan_id' => 15,
                'tanggal_mulai' => '2024-08-01',
                'tanggal_berakhir' => '2024-08-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 16,
                'tanggal_mulai' => '2024-09-01',
                'tanggal_berakhir' => '2024-09-05',
                'keterangan' => 'Cuti sakit',
                'jenis_cuti' => 'Sakit',
            ],
            [
                'karyawan_id' => 17,
                'tanggal_mulai' => '2024-10-01',
                'tanggal_berakhir' => '2024-10-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 18,
                'tanggal_mulai' => '2024-11-01',
                'tanggal_berakhir' => '2024-11-05',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
            [
                'karyawan_id' => 19,
                'tanggal_mulai' => '2024-12-01',
                'tanggal_berakhir' => '2024-12-05',
                'keterangan' => 'Cuti sakit',
                'jenis_cuti' => 'Sakit',
            ],
            [
                'karyawan_id' => 20,
                'tanggal_mulai' => '2024-01-10',
                'tanggal_berakhir' => '2024-01-15',
                'keterangan' => 'Cuti tahunan',
                'jenis_cuti' => 'Tahunan',
            ],
        ];

        foreach( $cutis as $cuti){
            Cuti::create($cuti);
        }
    }
}
