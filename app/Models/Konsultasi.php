<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

	protected $table = 'konsultasi';
	protected $primaryKey = 'konsultasi_id';
	public $timestamps = false;

	protected $fillable = [
		'pasien_id',
		'doctor_id',
		'tanggal_konsultasi',
		'status',
		'keluhan_pasien',
		'balasan_dokter',
	];

	public function doctors()
	{
		return $this->belongsTo(Dokter::class, 'doctor_id');
	}

	public function pasiens()
	{
		return $this->belongsTo(Pasien::class, 'pasien_id');
	}

	public function chatRoom()
	{
		return $this->hasOne(ChatRoom::class, 'konsultasi_id', 'konsultasi_id');
	}
}