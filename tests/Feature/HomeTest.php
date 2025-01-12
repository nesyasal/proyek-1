<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeTest extends TestCase
{
	use RefreshDatabase;
	public function test_index_home_page()
	{
		// Membuat user bertipe Dokter
		$dokterUser = User::factory()->create([
			'name' => 'Dr. Alice Smith',
			'email' => 'dr.alice@example.com',
			'tipe_pengguna' => 'Dokter', // Tipe pengguna sebagai Dokter
		]);

		// Membuat entri dokter di tabel 'doctors' dan mengaitkan dengan user_id
		DB::table('doctors')->insert([
			'user_id' => $dokterUser->id, // Hubungkan dokter dengan user
			'spesialisasi' => 'Dokter Gigi', // Contoh spesialisasi
			'kualifikasi' => 'Spesialis Gigi',
			'pengalaman' => '10 Tahun',
		]);

		// Melakukan request ke route yang memanggil method index
		$response = $this->get(route('home')); // Pastikan route sesuai

		// Verifikasi bahwa response berhasil
		$response->assertStatus(200);

		// Verifikasi bahwa view yang dituju adalah 'index'
		$response->assertViewIs('index');

		// Verifikasi bahwa data dokter ada dalam view
		$response->assertViewHas('doctors', function ($doctors) use ($dokterUser) {
			return $doctors->contains(function ($doctor) use ($dokterUser) {
				return $doctor->name == $dokterUser->name &&
					$doctor->email == $dokterUser->email &&
					$doctor->spesialisasi == 'Dokter Gigi' &&
					$doctor->kualifikasi == 'Spesialis Gigi' &&
					$doctor->pengalaman == '10 Tahun';
			});
		});
	}
}
