<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JabatanFactory extends Factory
{
    protected $model = Jabatan::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        $jabatanIT = [
            'Software Engineer',
            'Backend Developer',
            'Frontend Developer',
            'Full Stack Developer',
            'System Analyst',
            'Database Administrator',
            'DevOps Engineer',
            'Mobile App Developer',
            'IT Support Specialist',
            'Data Scientist',
            'Cyber Security Specialist',
            'Network Engineer',
            'IT Project Manager',
            'UI/UX Designer',
            'Quality Assurance Engineer',
            'Cloud Engineer',
        ];

        return [
            'nama_jabatan' => $faker->randomElement($jabatanIT),
            'jam_mulai_kerja' => $faker->dateTimeBetween('08:00:00', '10:00:00')->format('H:i:s'),
            'jam_selesai_kerja' => $faker->dateTimeBetween('12:00:00', '16:00:00')->format('H:i:s'),
            'note_pekerjaan' => $faker->sentence(),
            'gaji_pokok' => $faker->numberBetween(3000000, 7000000),
            'tunjangan' => $faker->numberBetween(500000, 2000000),
            'potongan' => $faker->numberBetween(100000, 1000000),
        ];
    }
}
