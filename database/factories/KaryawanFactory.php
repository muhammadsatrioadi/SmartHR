<?php

namespace Database\Factories;

use App\Models\Karyawan;
use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaryawanFactory extends Factory
{
    protected $model = Karyawan::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'telephone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['Aktif', 'Non-aktif']),
            'jabatan_id' => Jabatan::factory(), // Ensure you have a Jabatan factory
            'ktp' => $this->faker->numerify('################'),
            'NPWP' => $this->faker->numerify('################'),
            'total_kontak' => $this->faker->numberBetween(1, 12),
        ];
    }
}
