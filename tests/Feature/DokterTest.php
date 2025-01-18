<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Log;

class DokterTest extends TestCase
{
	public function test_dashboard()
	{
		// Membuat user dengan tipe pengguna Dokter
		$doctorUser = User::factory()->create([
			'tipe_pengguna' => 'Dokter'
		]);

		// Membuat record dokter terkait
		$doctor = Dokter::create([
			'user_id' => $doctorUser->id,
			'spesialisasi' => 'Spesialis Jantung',
			'kualifikasi' => 'Spesialis Jantung',
			'pengalaman' => '10 Tahun',
		]);

		// Melakukan login dengan user dokter
		$this->actingAs($doctorUser);

		// Melakukan request ke route dashboard
		$response = $this->get(route('dokter.dashboard'));

		// Verifikasi jika response berhasil
		$response->assertStatus(200);

		// Verifikasi jika data yang diambil sesuai dengan yang diharapkan
		$response->assertViewHas('doctor');
		$response->assertViewHas('konsultasi');
	}

	public function test_update_profile_successfully()
	{
		// Membuat user dummy yang sudah login
		$user = User::factory()->create([
			'name' => 'Old Name',
			'email' => 'oldemail@example.com',
			'username' => 'oldusername',
			'jenis_kelamin' => 'Laki-laki',
			'tanggal_lahir' => '1990-01-01',
			'alamat' => 'Old Address',
			'no_telepon' => '1234567890',
		]);

		// Melakukan login sebagai user
		$this->actingAs($user);

		// Data baru yang akan diperbarui
		$newData = [
			'name' => 'New Name',
			'email' => 'newemail@example.com',
			'username' => 'newusername',
			'jenis_kelamin' => 'Perempuan',
			'tanggal_lahir' => '1995-01-01',
			'alamat' => 'New Address',
			'no_telepon' => '0987654321',
		];

		// Melakukan request update profile
		$response = $this->put(route('dokter.profile.edit'), $newData);

		// Verifikasi apakah data yang ada di database sudah diperbarui
		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'name' => 'New Name',
			'email' => 'newemail@example.com',
			'username' => 'newusername',
			'jenis_kelamin' => 'Perempuan',
			'tanggal_lahir' => '1995-01-01',
			'alamat' => 'New Address',
			'no_telepon' => '0987654321',
		]);

		// Pastikan bahwa respon redirect ke halaman yang sesuai dengan pesan sukses
		$response->assertRedirect()->assertSessionHas('success', 'Profile updated successfully.');
	}

	public function test_update_password_successfully()
	{
		// Membuat pengguna baru dengan password lama
		$user = User::factory()->create([
			'name' => 'Old Name',
			'email' => 'oldemail@example.com',
			'password' => Hash::make('oldpassword123'), // Password lama yang telah di-hash
			'username' => 'oldusername',
			'jenis_kelamin' => 'Laki-laki',
			'tanggal_lahir' => '1990-01-01',
			'alamat' => 'Old Address',
			'no_telepon' => '1234567890',
		]);

		// Menyetel pengguna yang sedang login
		$this->actingAs($user);

		// Data untuk perubahan password
		$newPasswordData = [
			'password' => 'oldpassword123', // Password lama
			'newpassword' => 'newpassword123', // Password baru
			'renewpassword' => 'newpassword123', // Konfirmasi password baru
		];

		// Melakukan request untuk update password
		$response = $this->post(route('dokter.reset-password'), $newPasswordData);

		// Segarkan data pengguna setelah perubahan
		$user->refresh();

		// Verifikasi bahwa password pengguna sudah diperbarui
		$this->assertTrue(Hash::check('newpassword123', $user->password)); // Memeriksa password baru yang telah di-hash

		// Memastikan halaman mengarah ke halaman yang benar dengan pesan sukses
		$response->assertRedirect()->assertSessionHas('success', 'Kata sandi berhasil diubah.');

		// Memastikan database telah diperbarui dengan password yang baru
		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'password' => $user->password, // Memastikan password yang baru disimpan
		]);
	}

	public function test_respon_dokter_melihat_konsultasi()
	{
		// Membuat user dokter
		$dokter = User::factory()->create([
			'name' => 'Dokter A',
			'email' => 'dokter@example.com',
			'tipe_pengguna' => 'Dokter', // Tipe pengguna sebagai Dokter
		]);

		// Membuat user pasien
		$pasien = User::factory()->create([
			'name' => 'Pasien A',
			'email' => 'pasien@example.com',
			'tipe_pengguna' => 'Pasien', // Tipe pengguna sebagai Pasien
		]);

		// Membuat entri pasien di tabel 'pasien' dan mengaitkan dengan user_id
		$pasienRecord = DB::table('pasien')->insertGetId([
			'user_id' => $pasien->id, // Hubungkan pasien dengan user
			'asuransi' => 'Asuransi A',
			'riwayat_medis' => 'Riwayat Medis A',
		]);

		// Membuat entri dokter di tabel 'doctors' dan mengaitkan dengan user_id
		$dokterRecord = DB::table('doctors')->insertGetId([
			'user_id' => $dokter->id, // Hubungkan dokter dengan user
			'spesialisasi' => 'Spesialis A', // Contoh data spesialisasi dokter
			'kualifikasi' => 'Kualifikasi A', // Contoh data kualifikasi dokter
			'pengalaman' => 'Pengalaman A', // Contoh data pengalaman dokter
		]);

		// Membuat data konsultasi yang mengaitkan pasien_id dan dokter_id
		$konsultasi = DB::table('konsultasi')->insertGetId([
			'pasien_id' => $pasienRecord, // Gunakan pasien_id yang valid
			'doctor_id' => $dokterRecord, // Gunakan dokter_id yang valid
			'tanggal_konsultasi' => now(),
			'status' => 'terjawab',
			'keluhan_pasien' => 'Sakit kepala',
		]);

		// Memastikan dokter login
		$this->actingAs($dokter);

		// Melakukan request untuk melihat respon konsultasi
		$response = $this->get(route('dokter.respon', ['konsultasi_id' => $konsultasi]));

		// Verifikasi bahwa response berhasil dan data konsultan ditampilkan
		$response->assertStatus(200);
		$response->assertViewIs('dokter.respon');
		$response->assertViewHas('respon');
		$response->assertSee('Sakit kepala');
	}

	public function test_dokter_profile()
	{
		// Membuat user bertipe dokter
		$dokterUser = User::factory()->create([
			'name' => 'Dr. John Doe',
			'email' => 'dokterjohn@example.com',
			'tipe_pengguna' => 'Dokter', // Tipe pengguna sebagai Dokter
		]);

		// Membuat entri dokter di tabel 'doctors' dan mengaitkan dengan user_id
		$dokterRecord = DB::table('doctors')->insertGetId([
			'user_id' => $dokterUser->id, // Hubungkan dokter dengan user
			'spesialisasi' => 'Dokter Umum', // Contoh spesialisasi
			'kualifikasi' => 'S1', // Contoh kualifikasi
			'pengalaman' => '5 tahun', // Contoh pengalaman
		]);

		// Melakukan autentikasi sebagai dokter yang sudah dibuat
		$this->actingAs($dokterUser);

		// Melakukan request ke route yang memanggil method profile
		$response = $this->get(route('dokter.profile'));

		// Verifikasi bahwa response berhasil
		$response->assertStatus(200);

		// Verifikasi bahwa view yang dituju adalah 'dokter.profile'
		$response->assertViewIs('dokter.profile');

		// Verifikasi bahwa data dokter ada dalam view
		$response->assertViewHas('doctor', function ($doctor) use ($dokterUser, $dokterRecord) {
			return $doctor->doctor_id == $dokterRecord &&
				$doctor->nama_dokter == $dokterUser->name &&
				$doctor->email == $dokterUser->email;
		});

		// Verifikasi bahwa data user juga ada di view
		$response->assertViewHas('user', function ($user) use ($dokterUser) {
			return $user->id == $dokterUser->id &&
				$user->name == $dokterUser->name &&
				$user->email == $dokterUser->email;
		});
	}

	public function test_dashboard_dokter()
	{
		// Membuat user bertipe Dokter
		$dokterUser = User::factory()->create([
			'name' => 'Dr. John Doe',
			'email' => 'doktersiapa@example.com',
			'tipe_pengguna' => 'Dokter', // Tipe pengguna sebagai Dokter
		]);

		// Membuat entri dokter di tabel 'doctors' dan mengaitkan dengan user_id
		$dokterRecord = DB::table('doctors')->insertGetId([
			'user_id' => $dokterUser->id, // Hubungkan dokter dengan user
			'spesialisasi' => 'Dokter Umum', // Contoh spesialisasi
			'kualifikasi' => 'Spesialis Umum',
			'pengalaman' => '5 Tahun',
		]);

		// Simulasikan request sebagai admin (admin harus memiliki akses untuk melihat dashboard dokter)
		$adminUser = User::factory()->create([
			'name' => 'Admin User',
			'email' => 'adminsiapa@example.com',
			'tipe_pengguna' => 'Admin', // Tipe pengguna sebagai Admin
		]);

		$this->actingAs($adminUser);

		// Melakukan request ke route yang memanggil method dashboardDokter
		$response = $this->get(route('admin.dashboard-dokter'));

		// Verifikasi bahwa response berhasil
		$response->assertStatus(200);

		// Verifikasi bahwa view yang dituju adalah 'admin.dashboard-dokter'
		$response->assertViewIs('admin.dashboard-dokter');

		// Verifikasi bahwa data dokter ada dalam view
		$response->assertViewHas('doctors', function ($doctors) use ($dokterUser, $dokterRecord) {
			return $doctors->contains(function ($doctor) use ($dokterUser, $dokterRecord) {
				return $doctor->name == $dokterUser->name &&
					$doctor->email == $dokterUser->email &&
					$doctor->spesialisasi == 'Dokter Umum' &&
					$doctor->kualifikasi == 'Spesialis Umum' &&
					$doctor->pengalaman == '5 Tahun';
			});
		});
	}

	public function test_delete_dokter()
	{
		// Buat user admin dan login
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        // Buat dokter
        $dokter = Dokter::factory()->create();

        // Kirim permintaan untuk menghapus dokter
        $response = $this->delete(route('deleteDokter', ['doctor_id' => $dokter->doctor_id]));

        // Verifikasi redirect dan pesan sukses
        $response->assertRedirect(route('admin.dashboard-dokter'));
        $response->assertSessionHas('success', 'Data dokter berhasil dihapus');

        // Pastikan dokter telah dihapus dari database
        $this->assertDatabaseMissing('doctors', ['doctor_id' => $dokter->doctor_id]);

        // Coba hapus dokter yang tidak ada
        $response = $this->delete(route('deleteDokter', ['doctor_id' => 999]));

        // Verifikasi status 404 untuk dokter yang tidak ditemukan
        $response->assertStatus(404);
	}
}
