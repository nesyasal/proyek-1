<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    protected $model = Chat::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_room_id' => \App\Models\ChatRoom::factory(),
            'user_id' => \App\Models\User::factory(),
            'message' => $this->faker->sentence,
            'is_read' => $this->faker->boolean,
        ];
    }
}
