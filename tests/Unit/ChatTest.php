<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_chat_room()
    {
        // Buat chat room
        $chatRoom = ChatRoom::factory()->create();

        // Buat chat terkait dengan chat room
        $chat = Chat::factory()->create([
            'chat_room_id' => $chatRoom->id,
        ]);

        // Verifikasi hubungan chat room
        $this->assertInstanceOf(ChatRoom::class, $chat->chatRoom);
        $this->assertEquals($chatRoom->id, $chat->chatRoom->id);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat chat terkait dengan user
        $chat = Chat::factory()->create([
            'user_id' => $user->id,
        ]);

        // Verifikasi hubungan user
        $this->assertInstanceOf(User::class, $chat->user);
        $this->assertEquals($user->id, $chat->user->id);
    }
}
