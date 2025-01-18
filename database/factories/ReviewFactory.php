<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\Konsultasi;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'konsultasi_id' => Konsultasi::factory()->create()->id,
            'rating' => $this->faker->numberBetween(1, 5), // Rating antara 1 hingga 5
            'pesan' => $this->faker->sentence(), // Pesan review
        ];
    }
}
