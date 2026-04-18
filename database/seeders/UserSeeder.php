<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin HR (akses penuh semua modul)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin HR',
                'password' => Hash::make('admin123'),
                'imgProfile' => 'user.jpg',
                'role' => 'admin_hr',
            ],
        );

        // Atasan (fokus approval cuti & lembur)
        User::updateOrCreate(
            ['email' => 'atasan@gmail.com'],
            [
                'name' => 'Atasan Satu',
                'password' => Hash::make('atasan123'),
                'imgProfile' => 'user.jpg',
                'role' => 'atasan',
            ],
        );

        // Karyawan biasa (hanya ajukan cuti/lembur dan lihat data sendiri)
        User::updateOrCreate(
            ['email' => 'karyawan@gmail.com'],
            [
                'name' => 'Karyawan Satu',
                'password' => Hash::make('karyawan123'),
                'imgProfile' => 'user.jpg',
                'role' => 'karyawan',
            ],
        );
    }
}
