<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pasien;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pasien>
 */
class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Pasien::class;

    public function definition(): array
    {

        return [
            'user_id' => User::factory(), // Menggunakan factory untuk model User
            'riwayat_medis' => $this->faker->paragraphs(3, true), // Riwayat medis berupa 3 paragraf teks
            'asuransi' => $this->faker->company(), // Nama perusahaan asuransi
        ];
    }
}
