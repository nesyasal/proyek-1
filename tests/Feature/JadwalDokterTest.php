<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class JadwalDokterTest extends TestCase
{
	public function test_get_data_jadwal_dokter()
	{
		// Membuat user dengan tipe 'Dokter'
		$doctorUser = User::factory()->create([
			'name' => 'Dokter A',
			'email' => 'doktera@example.com',
			'password' => Hash::make('password'),
			'alamat' => 'Jl. Dokter No. 1',
			'no_telepon' => '08123456789',
			'username' => 'doktera',
			'tanggal_lahir' => '1990-01-01',
			'jenis_kelamin' => 'Laki-Laki',
			'tipe_pengguna' => 'Dokter',
		]);

		// Membuat data dokter terkait
		$dokter = Dokter::create([
			'user_id' => $doctorUser->id,
			'spesialisasi' => 'Spesialis Jantung',
			'kualifikasi' => 'Lulus Kedokteran Universitas XYZ',
			'pengalaman' => '5 tahun',
		]);

		// Membuat jadwal dokter
		DB::table('jadwal_dokter')->insert([
			'doctor_id' => $dokter->doctor_id, // Menggunakan $dokter->id
			'hari' => 'senin',
			'jam_mulai' => '08:00:00',
			'jam_selesai' => '12:00:00',
		]);

		// Autentikasi sebagai admin (atau role yang sesuai)
		$adminUser = User::factory()->create([
			'email' => 'admina@example.com',
			'password' => Hash::make('adminpassword'),
			'tipe_pengguna' => 'Admin', // Pastikan tipe ini sesuai
		]);

		$this->actingAs($adminUser); // Login sebagai admin

		// Mengirim request untuk memanggil fungsi getDataJadwalDokter
		$response = $this->get(route('admin.dashboard-jadwal'));

		// Log untuk debugging jika terjadi redirect
		if ($response->status() === 302) {
			\Log::error('Redirection terjadi', [
				'location' => $response->headers->get('Location'),
			]);
		}

		// Verifikasi bahwa response berhasil dan memuat jadwal dokter
		$response->assertStatus(200);
		$response->assertViewHas('jadwals', function ($jadwals) use ($doctorUser) {
			return $jadwals->contains('doctor_name', $doctorUser->name)
				&& $jadwals->contains('spesialisasi', 'Spesialis Jantung')
				&& $jadwals->contains('hari', 'senin') // Perhatikan huruf kecil.
				&& $jadwals->contains('jam_mulai', '08:00:00')
				&& $jadwals->contains('jam_selesai', '12:00:00');
		});

		// Verifikasi bahwa jadwal dokter ada di database
		$this->assertDatabaseHas('jadwal_dokter', [
			'doctor_id' => $dokter->doctor_id, // Menggunakan $dokter->id
			'hari' => 'Senin',
			'jam_mulai' => '08:00:00',
			'jam_selesai' => '12:00:00',
		]);
	}

	public function test_add_jadwal_dokter()
	{
		// Membuat user dengan tipe 'Dokter'
		$doctorUser = User::factory()->create([
			'name' => 'Dokter B',
			'email' => 'dokterb@example.com',
			'password' => Hash::make('password'),
			'tipe_pengguna' => 'Dokter',
		]);

		// Membuat data dokter terkait
		$dokter = Dokter::create([
			'user_id' => $doctorUser->id,
			'spesialisasi' => 'Spesialis Anak',
			'kualifikasi' => 'Lulus Kedokteran Universitas ABC',
			'pengalaman' => '3 tahun',
		]);

		// Data input untuk request
		$requestData = [
			'doctor_id' => $dokter->doctor_id,
			'hari' => 'Selasa',
			'jam_mulai' => '10:00:00',
			'jam_selesai' => '14:00:00',
		];

		// Autentikasi sebagai admin (atau role yang sesuai)
		$adminUser = User::factory()->create([
			'email' => 'adminb@example.com',
			'password' => Hash::make('adminpassword'),
			'tipe_pengguna' => 'Admin', // Pastikan tipe ini sesuai
		]);

		$this->actingAs($adminUser); // Login sebagai admin

		// Mengirim POST request ke route untuk menambah jadwal dokter
		$response = $this->post(route('addJadwal'), $requestData);

		// Verifikasi bahwa redirect berhasil ke route yang sesuai
		$response->assertRedirect(route('admin.dashboard-jadwal'));
		$response->assertSessionHas('success', 'jadwal dokter berhasil ditambahkan');

		// Verifikasi bahwa jadwal dokter telah tersimpan di database
		$this->assertDatabaseHas('jadwal_dokter', [
			'doctor_id' => $dokter->doctor_id,
			'hari' => 'Selasa',
			'jam_mulai' => '10:00:00',
			'jam_selesai' => '14:00:00',
		]);
	}

	public function test_update_jadwal_dokter()
	{
		// Membuat user dengan tipe 'Dokter'
		$doctorUser = User::factory()->create([
			'name' => 'Dokter C',
			'email' => 'dokterc@example.com',
			'password' => Hash::make('password'),
			'tipe_pengguna' => 'Dokter',
		]);

		// Membuat data dokter terkait
		$dokter = Dokter::create([
			'user_id' => $doctorUser->id,
			'spesialisasi' => 'Spesialis Mata',
			'kualifikasi' => 'Lulus Kedokteran Universitas DEF',
			'pengalaman' => '8 tahun',
		]);

		// Membuat jadwal dokter
		$jadwal = JadwalDokter::create([
			'doctor_id' => $dokter->doctor_id,
			'hari' => 'Rabu',
			'jam_mulai' => '09:00:00',
			'jam_selesai' => '13:00:00',
		]);

		// Data input untuk update
		$updateData = [
			'hari' => 'kamis',
			'jam_mulai' => '10:00:00',
			'jam_selesai' => '14:00:00',
		];

		// Autentikasi sebagai admin
		$adminUser = User::factory()->create([
			'email' => 'adminc@example.com',
			'password' => Hash::make('adminpassword'),
			'tipe_pengguna' => 'Admin', // Pastikan tipe ini sesuai
		]);

		$this->actingAs($adminUser); // Login sebagai admin

		// Mengirim PATCH request ke route untuk mengupdate jadwal dokter
		$response = $this->put(route('updateJadwal', $jadwal->jadwal_id), $updateData);

		$response->assertRedirect(route('admin.dashboard-jadwal')); // Mengarahkan ke halaman dashboard setelah update
		$response->assertSessionHas(
			'success',
			'Jadwal dokter berhasil diperbarui'
		);

		// Verifikasi bahwa data jadwal dokter telah diperbarui di database
		$this->assertDatabaseHas('jadwal_dokter', [
			'jadwal_id' => $jadwal->jadwal_id,
			'hari' => 'Kamis',
			'jam_mulai' => '10:00:00',
			'jam_selesai' => '14:00:00',
		]);
	}
}
