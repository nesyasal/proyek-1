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
        DB::table('konsultasi')->insert([
            'konsultasi_id' => 1,
            'pasien_id' => 1, // Sesuaikan dengan data pasien
            'doctor_id' => 1, // Sesuaikan dengan data dokter
            'tanggal_konsultasi' => now(),
            'status' => 'belum dijawab',
            'keluhan_pasien' => 'Keluhan dummy pasien',
            'balasan_dokter' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $room = DB::table('chat_rooms')->insertGetId([
            'id' => Str::orderedUuid(),
            'name' => 'Test Room',
            'konsultasi_id' => 1, // Pastikan nilai ini valid
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->get(route('chat.room', ['room' => $room]));

        $response->assertStatus(200);
        $response->assertViewIs('chat');
        $response->assertViewHas(['room', 'users']);
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
