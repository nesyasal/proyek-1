<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Dokter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dokter>
 */
class DokterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Dokter::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Membuat user dummy menggunakan factory User
            'spesialisasi' => $this->faker->word(), // Spesialisasi dokter (contoh: "kardiologi")
            'kualifikasi' => $this->faker->words(3, true), // Kualifikasi (contoh: "Sarjana Kedokteran Umum")
            'pengalaman' => $this->faker->sentence(), // Pengalaman (contoh: "5 tahun sebagai dokter umum")
        ];
    }
}
