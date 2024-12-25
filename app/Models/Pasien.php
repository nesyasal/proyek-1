<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

	protected $table = 'pasien';

	protected $primaryKey = 'pasien_id';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'riwayat_medis',
		'asuransi',
	];

	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'pasien_id');
    }
}
