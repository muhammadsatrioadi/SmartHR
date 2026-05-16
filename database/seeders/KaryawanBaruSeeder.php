<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil jabatan pertama atau buat jika tidak ada
        $jabatan = Jabatan::first() ?? Jabatan::create(['nama_jabatan' => 'Staff']);

        $data = [
            [
                'name' => 'Satrio',
                'email' => 'satrio@gmail.com',
                'nik' => 'NIK-' . rand(1000, 9999),
            ],
            [
                'name' => 'Iskandar',
                'email' => 'iskandar@gmail.com',
                'nik' => 'NIK-' . rand(1000, 9999),
            ],
        ];

        foreach ($data as $item) {
            // 1. Buat Akun Login
            User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => Hash::make('333333'),
                    'role' => 'karyawan',
                    'imgProfile' => 'user.jpg',
                ]
            );

            // 2. Buat Data Karyawan (ESS)
            Karyawan::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'nik' => $item['nik'],
                    'status' => 'Aktif',
                    'jenis_kelamin' => 'Laki-laki',
                    'telephone' => '08123456789',
                    'jabatan_id' => $jabatan->id,
                    'ktp' => '1234567890',
                    'NPWP' => '000000000',
                    'total_kontak' => 0,
                ]
            );
        }

        $this->command->info('Karyawan Satrio & Iskandar berhasil dibuat dengan password: 333333');
    }
}
