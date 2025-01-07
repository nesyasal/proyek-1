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
        $room = DB::table('chat_rooms')->where('id', $room)->firstOrFail();

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
            ->where('konsultasi_id', $konsultasi->konsultasi_id)
            ->first();

        // Jika room belum ada, buat room baru
        if (!$room) {
            $uuid = Str::orderedUuid();
            $room = DB::table('chat_rooms')->insert([
                'id' => $uuid,
                'name' => 'Konsultasi: ' . $konsultasi->keluhan_pasien,
                'konsultasi_id' => $konsultasi->konsultasi_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $room = DB::table('chat_rooms')->where('id', $uuid)->first();
        }

        // Pastikan room ditemukan
        if (!$room) {
            abort(500, 'Gagal membuat atau mengambil chat room.');
        }

        // Ambil pengguna yang terhubung dengan room ini
        $users = DB::table('chat_room_users')
            ->where('chat_room_id', $room->id)
            ->get();

        // Kirim data ke view
        return view('chat', compact('konsultasi', 'room', 'users'));
    }
}