<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Konsultasi;
use Illuminate\Support\Str;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database or set up necessary test data
        $this->seed();
    }

    public function testRoomViewIsReturnedWithValidRoom()
    {
        // Membuat user
        $user = User::factory()->create();

        // Membuat room
        $roomId = (string) Str::uuid();
        DB::table('chat_rooms')->insert([
            'id' => $roomId,
            'name' => 'Test Room',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Menambahkan user ke room
        DB::table('chat_room_users')->insert([
            'chat_room_id' => $roomId,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verifikasi data tersedia di database
        $this->assertDatabaseHas('chat_rooms', ['id' => $roomId]);
        $this->assertDatabaseHas('chat_room_users', [
            'chat_room_id' => $roomId,
            'user_id' => $user->id,
        ]);

        $user = User::factory()->create(['tipe_pengguna' => 'Dokter']);

        // Aktifkan user
        $this->actingAs($user);

        // Panggil endpoint
        $response = $this->get(route('chat.room', ['room' => $roomId]));

        // Debug responsenya
        $response->dump();

        // Verifikasi response
        $response->assertStatus(200);
        $response->assertViewIs('chat');
        $response->assertViewHas('room', function ($viewRoom) use ($roomId) {
            return $viewRoom->id === $roomId;
        });

        $response->assertViewHas('users', function ($viewUsers) use ($user) {
            return $viewUsers->contains('id', $user->id);
        });
    }

    public function testGetChatReturnsChatData()
    {
        $room = DB::table('chat_rooms')->insertGetId([
            'id' => Str::orderedUuid(),
            'name' => 'Test Room',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('chats')->insert([
            'chat_room_id' => $room,
            'user_id' => 1,
            'message' => 'Test Message',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->get(route('chat.get', ['room' => $room]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'chat_room_id', 'user_id', 'message', 'is_read', 'created_at', 'updated_at', 'user_name']
        ]);
    }

    public function testSendChatStoresMessageAndTriggersEvent()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $room = DB::table('chat_rooms')->insertGetId([
            'id' => Str::orderedUuid(),
            'name' => 'Test Room',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->post(route('chat.send'), [
            'room' => $room,
            'message' => 'Hello, World!'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chats', [
            'chat_room_id' => $room,
            'user_id' => $user->id,
            'message' => 'Hello, World!'
        ]);
    }

    public function testChatCreatesRoomIfNotExists()
    {
        $konsultasi = Konsultasi::factory()->create();

        $response = $this->get(route('pasien.chat', ['konsultasiId' => $konsultasi->id]));

        $response->assertStatus(200);
        $response->assertViewIs('chat');
        $response->assertViewHas(['konsultasi', 'room', 'users']);

        $this->assertDatabaseHas('chat_rooms', [
            'konsultasi_id' => $konsultasi->id
        ]);
    }
}
