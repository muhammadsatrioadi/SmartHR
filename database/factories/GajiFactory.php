<?php

namespace Database\Factories;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class GajiFactory extends Factory
{
    protected $model = Gaji::class;

    public function definition()
    {
        // Buat karyawan dengan relasi ke jabatan
        $karyawan = Karyawan::factory()->create();
        $jabatan = $karyawan->jabatan;

        return [
            'karyawan_id' => $karyawan->id,
            'total' => $this->faker->numberBetween(3000000, 7000000),
            'potongan' => $jabatan->potongan,
            'tunjangan' => $jabatan->tunjangan,
        ];
    }
}
