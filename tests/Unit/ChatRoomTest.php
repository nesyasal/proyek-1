<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\Konsultasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRoomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_konsultasi()
    {
        // Buat konsultasi
        $konsultasi = Konsultasi::factory()->create();

        // Buat chat room terkait dengan konsultasi
        $chatRoom = ChatRoom::factory()->create([
            'konsultasi_id' => $konsultasi->konsultasi_id,
        ]);

        // Verifikasi hubungan konsultasi
        $this->assertInstanceOf(Konsultasi::class, $chatRoom->Konsultasi);
        $this->assertEquals($konsultasi->konsultasi_id, $chatRoom->Konsultasi->konsultasi_id);
    }

    /** @test */
    public function it_has_many_users()
    {
        // Buat chat room
        $chatRoom = ChatRoom::factory()->create();

        // Buat beberapa pengguna terkait dengan chat room
        $users = ChatRoomUser::factory()->count(3)->create([
            'chat_room_id' => $chatRoom->id,
        ]);

        // Verifikasi hubungan users
        $this->assertCount(3, $chatRoom->users);
        $this->assertTrue($chatRoom->users->contains($users->first()));
    }

    /** @test */
    public function it_has_many_chats()
    {
        // Buat chat room
        $chatRoom = ChatRoom::factory()->create();

        // Buat beberapa chat terkait dengan chat room
        $chats = Chat::factory()->count(5)->create([
            'chat_room_id' => $chatRoom->id,
        ]);

        // Verifikasi hubungan chats
        $this->assertCount(5, $chatRoom->chats);
        $this->assertTrue($chatRoom->chats->contains($chats->first()));
    }
}
