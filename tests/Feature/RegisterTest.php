<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pasien;

class RegisterTest extends TestCase
{
	use RefreshDatabase; // Membuat database tetap bersih

	// Pengujian Registrasi Pengguna dengan Data Valid
	public function test_register_user_with_valid_data()
	{
		// Mulai transaksi untuk memastikan rollback setelah tes selesai
		DB::beginTransaction();

		try {
			// Data yang valid untuk registrasi
			$data = [
				'name' => 'John Doe',
				'email' => 'johndoe@example.com',
				'username' => 'johndoe',
				'password' => 'password123',
				'password_confirmation' => 'password123',
				'jenis_kelamin' => 'Laki-Laki',  // Pastikan ini
				'tanggal_lahir' => '1990-01-01',
				'alamat' => 'Jl. Contoh No. 123',
				'no_telepon' => '08123456789',
				'riwayat_medis' => 'Sehat',
				'asuransi' => 'BPJS',
			];

			// Kirim POST request ke endpoint register
			$response = $this->post(route('register'), $data);

			// Periksa apakah berhasil melakukan redirect ke login
			$response->assertRedirect(route('login'));

			// Verifikasi data ada di tabel users
			$this->assertDatabaseHas('users',
				[
					'email' => 'johndoe@example.com',
					'username' => 'johndoe',
					'jenis_kelamin' => 'Laki-Laki',  // Pastikan ini
				]
			);

			// Verifikasi data ada di tabel pasien
			$this->assertDatabaseHas('pasien', [
				'riwayat_medis' => 'Sehat',
				'asuransi' => 'BPJS',
			]);
		} finally {
			// Rollback transaksi untuk menghapus semua perubahan pada database
			DB::rollBack();
		}
	}

	// Pengujian Registrasi Pengguna dengan Data Tidak Valid
	public function test_register_user_with_invalid_data()
	{
		// Mulai transaksi untuk memastikan rollback setelah tes selesai
		DB::beginTransaction();

		try {
			// Data tidak valid (misalnya tidak ada konfirmasi password)
			$data = [
				'name' => 'John Doe',
				'email' => 'johndoe@example.com',
				'username' => 'johndoe',
				'password' => 'password123',
				'jenis_kelamin' => 'Laki-Laki',
				'tanggal_lahir' => '1990-01-01',
				'alamat' => 'Jl. Contoh No. 123',
				'no_telepon' => '08123456789',
				'riwayat_medis' => 'Sehat',
				'asuransi' => 'BPJS',
			];

			// Kirim POST request ke endpoint register
			$response = $this->post(route('register'), $data);

			// Verifikasi error pada field password_confirmation
			$response->assertSessionHasErrors();
		} finally {
			// Rollback transaksi untuk menghapus semua perubahan pada database
			DB::rollBack();
		}
	}

	// Pengujian Registrasi Pengguna dengan Email Duplikat
	public function test_register_user_with_duplicate_email()
	{
		// Mulai transaksi untuk memastikan rollback setelah tes selesai
		DB::beginTransaction();

		try {
			// Buat user dengan email yang sama terlebih dahulu
			User::create([
				'name' => 'Existing User',
				'email' => 'johndoe@example.com',
				'username' => 'johndoe',
				'password' => 'password123',
				'jenis_kelamin' => 'Laki-Laki',
				'tanggal_lahir' => '1990-01-01',
				'alamat' => 'Jl. Contoh No. 123',
				'no_telepon' => '08123456789',
				'riwayat_medis' => 'Sehat',
				'asuransi' => 'BPJS',
			]);

			// Data register dengan email duplikat
			$data = [
				'name' => 'Jane Doe',
				'email' => 'johndoe@example.com', // Email duplikat
				'username' => 'janedoe',
				'password' => 'password123',
				'password_confirmation' => 'password123',
				'jenis_kelamin' => 'Perempuan',
				'tanggal_lahir' => '1995-05-05',
				'alamat' => 'Jl. Contoh No. 456',
				'no_telepon' => '08123456789',
				'riwayat_medis' => 'Sehat',
				'asuransi' => 'BPJS',
			];

			// Kirim POST request
			$response = $this->post(route('register'), $data);

			// Verifikasi error pada email
			$response->assertSessionHasErrors(['email']);
		} finally {
			// Rollback transaksi untuk menghapus semua perubahan pada database
			DB::rollBack();
		}
	}
}
