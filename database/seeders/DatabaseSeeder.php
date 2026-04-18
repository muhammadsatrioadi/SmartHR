<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JabatanSeeder::class,
            KaryawanSeeder::class,
            GajiSeeder::class,
            AbsensiSeeder::class,
            UserSeeder::class,
        ]);
    }
}
