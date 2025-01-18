<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChatRoom;
use App\Models\Konsultasi;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    protected $model = ChatRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->sentence(3), // Nama room (opsional)
            'konsultasi_id' => Konsultasi::factory(), // Relasi dengan Konsultasi
        ];
    }
}
