<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gaji;

class GajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gaji::factory()->count(50)->create();
    }
}
