<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'doctors';

	protected $primaryKey = 'doctor_id';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'spesialisasi',
		'kualifikasi',
        'pengalaman',
	];

	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'doctor_id');
    }
}
