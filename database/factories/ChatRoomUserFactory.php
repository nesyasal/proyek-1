<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoomUser>
 */
class ChatRoomUserFactory extends Factory
{
    protected $model = ChatRoomUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_room_id' => ChatRoom::factory(),
            'user_id' => User::factory(),
        ];
    }
}
