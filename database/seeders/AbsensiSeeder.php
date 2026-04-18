<?php

namespace Database\Seeders;

use App\Models\Absensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $absensis = [
            ['karyawan_id' => 2, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(1), 'time' => '08:00:00'],
            ['karyawan_id' => 2, 'status_absen' => 'alpha', 'keterangan' => 'Sick', 'tanggal_absensi' => Carbon::today()->subDays(2), 'time' => '08:00:00'],
            ['karyawan_id' => 3, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(3), 'time' => '08:00:00'],
            ['karyawan_id' => 4, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(4), 'time' => '08:00:00'],
            ['karyawan_id' => 5, 'status_absen' => 'alpha', 'keterangan' => 'Personal reasons', 'tanggal_absensi' => Carbon::today()->subDays(5), 'time' => '08:00:00'],
            ['karyawan_id' => 6, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(6), 'time' => '08:00:00'],
            ['karyawan_id' => 7, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(7), 'time' => '08:00:00'],
            ['karyawan_id' => 8, 'status_absen' => 'alpha', 'keterangan' => 'Family emergency', 'tanggal_absensi' => Carbon::today()->subDays(8), 'time' => '08:00:00'],
            ['karyawan_id' => 9, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(9), 'time' => '08:00:00'],
            ['karyawan_id' => 10, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(10), 'time' => '08:00:00'],
            ['karyawan_id' => 2, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(11), 'time' => '08:00:00'],
            ['karyawan_id' => 2, 'status_absen' => 'alpha', 'keterangan' => 'Sick', 'tanggal_absensi' => Carbon::today()->subDays(12), 'time' => '08:00:00'],
            ['karyawan_id' => 3, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(13), 'time' => '08:00:00'],
            ['karyawan_id' => 4, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(14), 'time' => '08:00:00'],
            ['karyawan_id' => 5, 'status_absen' => 'alpha', 'keterangan' => 'Personal reasons', 'tanggal_absensi' => Carbon::today()->subDays(15), 'time' => '08:00:00'],
            ['karyawan_id' => 6, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(16), 'time' => '08:00:00'],
            ['karyawan_id' => 7, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(17), 'time' => '08:00:00'],
            ['karyawan_id' => 8, 'status_absen' => 'alpha', 'keterangan' => 'Family emergency', 'tanggal_absensi' => Carbon::today()->subDays(18), 'time' => '08:00:00'],
            ['karyawan_id' => 9, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(19), 'time' => '08:00:00'],
            ['karyawan_id' => 10, 'status_absen' => 'hadir', 'keterangan' => null, 'tanggal_absensi' => Carbon::today()->subDays(20), 'time' => '08:00:00'],
        ];

        foreach ($absensis as $absensi) {
            Absensi::create($absensi);
        }
    }
}
