<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'konsultasi_id',
    ];

    public function Konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id', 'konsultasi_id');
    }

    public function users()
    {
        return $this->hasMany(ChatRoomUser::class, 'chat_room_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'chat_room_id', 'id');
    }
}
