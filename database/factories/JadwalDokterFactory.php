<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dokter;
use App\Models\JadwalDokter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalDokter>
 */
class JadwalDokterFactory extends Factory
{
    protected $model = JadwalDokter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Dokter::factory(), // Membuat dokter terkait secara otomatis
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            'jam_mulai' => $this->faker->time('H:i:s'),
            'jam_selesai' => $this->faker->time('H:i:s'),
        ];
    }
}
