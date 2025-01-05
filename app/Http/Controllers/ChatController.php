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

    public function chat($konsultasiId) {
            // Cari konsultasi berdasarkan ID
        $konsultasi = Konsultasi::with(['doctors', 'pasiens']) // Pastikan relasi 'dokter' dan 'pasien' sudah didefinisikan di model Konsultasi
        ->where('konsultasi_id', $konsultasiId)
        ->firstOrFail();

        // Dapatkan room terkait (Anda bisa menggunakan name atau ID untuk mencari room)
        $room = DB::table('chat_rooms')
            ->where('name', 'Konsultasi: ' . $konsultasi->keluhan_pasien)
            ->first();

        // Jika room belum ada, buat room baru
        if (!$room) {
            $uuid = Str::orderedUuid();
            $room = DB::table('chat_rooms')->insert([
                'id' => $uuid,
                'name' => 'Konsultasi: ' . $konsultasi->keluhan_pasien,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $roomId = $uuid;
        } else {
            $roomId = $room->id;
        }

        // Ambil pengguna yang terhubung dengan room ini
        $users = DB::table('chat_room_users')
            ->where('chat_room_id', $roomId)
            ->get();

        // Kirim data ke view
        return view('chat', compact('konsultasi', 'room', 'users'));
    }
}