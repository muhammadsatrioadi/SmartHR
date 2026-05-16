<?php

namespace Database\Seeders;

use App\Models\AttendanceLocation;
use App\Models\Karyawan;
use App\Models\LeaveType;
use App\Services\LeaveBalanceService;
use Illuminate\Database\Seeder;

class PortalSeeder extends Seeder
{
    public function run(): void
    {
        $location = AttendanceLocation::updateOrCreate(
            ['nama' => 'Kantor Pusat'],
            [
                'latitude' => -7.815265,
                'longitude' => 110.328759,
                'radius_meter' => 5,
                'is_aktif' => true,
            ]
        );

        LeaveType::updateOrCreate(
            ['kode' => 'CT'],
            ['nama' => 'Cuti Tahunan', 'grup' => 'Tahunan', 'kuota_hari' => 12, 'rekap' => true]
        );
        LeaveType::updateOrCreate(
            ['kode' => 'CD'],
            ['nama' => 'Cuti Duka', 'grup' => 'Khusus', 'kuota_hari' => 3, 'rekap' => true]
        );

        $emails = ['karyawan@gmail.com', 'atasan@gmail.com'];
        $service = app(LeaveBalanceService::class);
        $tahun = (int) date('Y');

        foreach ($emails as $email) {
            $k = Karyawan::where('email', $email)->first();
            if ($k) {
                $k->update(['attendance_location_id' => $location->id]);
                $service->ensureBalancesForYear($k, $tahun);
            }
        }

        if (!Karyawan::where('email', 'karyawan@gmail.com')->exists()) {
            $first = Karyawan::first();
            if ($first) {
                $first->update([
                    'email' => 'karyawan@gmail.com',
                    'attendance_location_id' => $location->id,
                ]);
                $service->ensureBalancesForYear($first, $tahun);
            }
        }
    }
}
