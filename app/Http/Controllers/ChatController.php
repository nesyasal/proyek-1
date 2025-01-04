<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\ChatEvent;
use App\Models\Konsultasi;

class ChatController extends Controller
{
    public function room($room) {
        // Get room
        $room = DB::table('chat_rooms')->where('id', $room)->first();

        // Get users
        $users = DB::table('chat_room_users')->where('chat_room_id', $room->id)->get();

        return view('chat', compact('room', 'users'));
    }

    public function getChat($room) {
        // Join with user
        $chats = DB::table('chats')
            ->join('users', 'users.id', '=', 'chats.user_id')
            ->where('chat_room_id', $room)
            ->select('chats.*', 'users.name as user_name')
            ->get();

        return response()->json($chats);
    }

    // Send chat
    public function sendChat(Request $request) {
        $chat = DB::table('chats')->insert([
            'chat_room_id' => $request->room,
            'user_id' => auth()->user()->id,
            'message' => $request->message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Trigger event
        broadcast(new ChatEvent($request->room, $request->message, auth()->user()->id));

        return response()->json($chat);
    }

    public function terimaKonsultasi($konsultasiId)
	{
		$konsultasi = Konsultasi::findOrFail($konsultasiId);

		if ($konsultasi->status === 'belum dijawab') {
			// Update status konsultasi
			$konsultasi->status = 'diterima';
			$konsultasi->save();

			// Buat chat room
			$uuid = Str::orderedUuid();
			DB::table('chat_rooms')->insert([
				'id' => $uuid,
				'name' => 'Konsultasi: ' . $konsultasi->keluhan_pasien,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			// Tambahkan pengguna ke chat room
			DB::table('chat_room_users')->insert([
				[
					'chat_room_id' => $uuid,
					'user_id' => $konsultasi->pasien->user_id,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'chat_room_id' => $uuid,
					'user_id' => $konsultasi->doctor->user_id,
					'created_at' => now(),
					'updated_at' => now(),
				],
			]);

			return redirect()->route('chat.room', ['room' => $uuid])->with('success', 'Konsultasi diterima dan chat room dibuat.');
		}

		return redirect()->back()->with('error', 'Konsultasi tidak valid.');
	}

    public function chat($user) {
        $my_id = auth()->user()->id;
        $target_id = $user;
    
        // Cari room yang sudah ada
        $shared_room = DB::table('chat_room_users as cru1')
        ->join('chat_room_users as cru2', 'cru1.chat_room_id', '=', 'cru2.chat_room_id')
        ->where('cru1.user_id', $my_id)
        ->where('cru2.user_id', $target_id)
        ->pluck('cru1.chat_room_id')
        ->first();

        // Jika room sudah ada, arahkan ke room tersebut
        if ($shared_room) {
            return redirect()->route('chat.room', ['room' => $shared_room]);
        }

        // Jika room tidak ada, buat room baru
        $uuid = Str::orderedUuid();

        // Tambahkan ke tabel chat_rooms
        DB::table('chat_rooms')->insert([
            'id' => $uuid,
            'name' => 'Generated by system',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tambahkan pengguna ke chat_room_users
        DB::table('chat_room_users')->insert([
            [
                'chat_room_id' => $uuid, // ID ruang obrolan
                'user_id' => $my_id,    // ID pasien/dokter (pengguna login)
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chat_room_id' => $uuid, // ID ruang obrolan
                'user_id' => $target_id, // ID pasien/dokter (target pengguna)
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return redirect()->route('chat.room', ['room' => $uuid]);
    }
}