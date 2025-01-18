<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Konsultasi;
use App\Models\Pasien;
use App\Models\Dokter;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Konsultasi>
 */
class KonsultasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pasien_id' => Pasien::factory(), // Menggunakan factory untuk pasien
            'doctor_id' => Dokter::factory(), // Menggunakan factory untuk dokter
            'tanggal_konsultasi' => $this->faker->dateTimeBetween('-1 year', 'now'), // Tanggal acak dalam satu tahun terakhir
            'status' => $this->faker->randomElement(['terjawab', 'belum dijawab', 'reviewed']), // Status acak
            'keluhan_pasien' => $this->faker->paragraph(), // Keluhan pasien acak
            'balasan_dokter' => $this->faker->optional()->paragraph(), // Balasan dokter (opsional)
        ];
    }
}
