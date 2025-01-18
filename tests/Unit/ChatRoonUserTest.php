<?php

namespace Tests\Unit;

use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRoomUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_chat_room()
    {
        // Buat chat room
        $chatRoom = ChatRoom::factory()->create();

        // Buat chat room user terkait
        $chatRoomUser = ChatRoomUser::factory()->create([
            'chat_room_id' => $chatRoom->id,
        ]);

        // Verifikasi hubungan chatRoom
        $this->assertInstanceOf(ChatRoom::class, $chatRoomUser->chatRoom);
        $this->assertEquals($chatRoom->id, $chatRoomUser->chatRoom->id);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat chat room user terkait
        $chatRoomUser = ChatRoomUser::factory()->create([
            'user_id' => $user->id,
        ]);

        // Verifikasi hubungan user
        $this->assertInstanceOf(User::class, $chatRoomUser->user);
        $this->assertEquals($user->id, $chatRoomUser->user->id);
    }
}
