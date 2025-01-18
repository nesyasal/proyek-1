<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Log;

class AdminTest extends TestCase
{
	use RefreshDatabase;
	public function test_add_pengguna_successfully()
	{
		$admin = User::factory()->create([
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'admin',
		]);

		// Melakukan login sebagai admin
		$response = $this->actingAs($admin);

		// Data yang akan dikirimkan dalam request
		$data = [
			'name' => 'Test User',
			'email' => 'testuser@example.com',
			'username' => 'testuser',
			'tanggal_lahir' => '1990-01-01',
			'jenis_kelamin' => 'Laki-laki',
			'alamat' => 'Jl. Test No.1',
			'no_telepon' => '081234567890',
			'tipe_pengguna' => 'Admin',
			'password' => 'password123',
			'password_confirmation' => 'password123',
		];

		// Kirimkan request POST untuk menambah pengguna
		$response = $this->post(route('addPengguna'), $data);

		// Pastikan redireksi ke dashboard admin dengan pesan sukses
		$response->assertRedirect(route('admin.dashboard'));
		$response->assertSessionHas('success', 'Pengguna berhasil ditambahkan');

		// Pastikan pengguna baru telah berhasil ditambahkan ke database
		$this->assertDatabaseHas('users', [
			'email' => 'testuser@example.com',
			'username' => 'testuser',
		]);
	}

	public function test_update_pengguna_successfully()
	{
		// Create an admin user
		$admin = User::factory()->create([
			'name' => 'Admin Name',
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Admin', // pastikan tipe pengguna sesuai dengan middleware
		]);

		// Authenticate as admin
		$this->actingAs($admin);

		// Create another user that we will update
		$user = User::factory()->create([
			'name' => 'Old Name',
			'email' => 'oldemail@example.com',
			'password' => bcrypt('password123'),
		]);

		// Simulate updating the name only
		$updatedData = [
			'name' => 'New Name',
			'email' => 'newemail@example.com', // Required field for validation
			'username' => 'newusername', // Add other required fields if necessary
			'tanggal_lahir' => '2000-01-01', // Add required field for birthdate
			'jenis_kelamin' => 'Laki-Laki', // Add required gender field
			'alamat' => 'New Address', // Add required address field
			'no_telepon' => '123456789', // Add required phone number field
			'tipe_pengguna' => 'Pasien', // Add required user type field
		];

		// Perform the PUT request to update the user's name
		$response = $this->put(route('updatePengguna', ['id' => $user->id]), $updatedData);

		// Assert that the response redirects with a success message
		$response->assertRedirect(route('admin.dashboard'));
		$response->assertSessionHas('success', 'Data pengguna berhasil diperbarui');

		// Assert that the user's name has been updated in the database
		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'name' => 'New Name',
			'email' => 'newemail@example.com',
		]);

		// Optionally, check if other fields are not changed
		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'email' => 'newemail@example.com',
		]);
	}

	public function test_delete_user_successfully()
	{
		// Membuat admin untuk autentikasi
		$admin = User::factory()->create([
			'name' => 'Admin Name',
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Admin', // memastikan admin memiliki akses
		]);

		// Autentikasi sebagai admin
		$this->actingAs($admin);

		// Membuat user yang akan dihapus
		$user = User::factory()->create([
			'name' => 'User to Delete',
			'email' => 'usertodelete@example.com',
			'password' => bcrypt('password123'),
		]);

		// Melakukan request untuk menghapus user
		$response = $this->delete(route('deletePengguna', ['id' => $user->id]));

		// Memastikan pengalihan ke halaman dashboard admin
		$response->assertRedirect(route('admin.dashboard'));

		// Memastikan pesan sukses ada di session
		$response->assertSessionHas('success', 'Data pengguna berhasil dihapus');

		// Memastikan pengguna benar-benar dihapus dari database
		$this->assertDatabaseMissing('users', [
			'id' => $user->id,
			'email' => 'usertodelete@example.com',
		]);
	}

	public function test_add_keluhan_successfully()
	{
		// Membuat pasien dan dokter untuk keperluan tes
		$pasien = User::factory()->create([
			'name' => 'Pasien Name',
			'email' => 'pasien@example.com',
			'password' => bcrypt('password123'),
		]);

		$doctor = User::factory()->create([
			'name' => 'Doctor Name',
			'email' => 'doctor@example.com',
			'password' => bcrypt('password123'),
		]);

		// Asumsikan pasien dan dokter sudah terdaftar di tabel 'pasien' dan 'doctors'
		DB::table('pasien')->insert([
			'user_id' => $pasien->id,
			'pasien_id' => 1, // Asumsikan pasien_id sudah ada
			'riwayat_medis' => 'Sakit',
			'asuransi' => 'BPJS'
		]);

		DB::table('doctors')->insert([
			'user_id' => $doctor->id,
			'doctor_id' => 1, // Asumsikan doctor_id sudah ada
			'spesialisasi' => 'Jantung',
			'kualifikasi' => 'S3',
			'pengalaman' => '10 Tahun'
		]);

		// Simulasi login sebagai admin
		$admin = User::factory()->create([
			'name' => 'Admin Name',
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Admin',
		]);

		$this->actingAs($admin);

		// Data yang akan dikirimkan dalam request
		$data = [
			'pasien_id' => $pasien->id,
			'tanggal_konsultasi' => '2025-01-11',
			'doctor_id' => $doctor->id,
			'keluhan_pasien' => 'Saya merasa sakit kepala dan pusing',
		];

		// Melakukan request POST untuk menambahkan keluhan pasien
		$response = $this->post(route('addKeluhan'), $data);

		// Memastikan pengalihan ke halaman dashboard keluhan
		$response->assertRedirect(route('admin.dashboard-keluhan'));

		// Memastikan pesan sukses ada di session
		$response->assertSessionHas('success', 'Keluhan Pasien berhasil ditambahkan');

		// Memastikan data konsultasi sudah masuk ke dalam tabel 'konsultasi'
		$this->assertDatabaseHas('konsultasi', [
			'pasien_id' => 1, // ID pasien yang valid
			'doctor_id' => 1, // ID dokter yang valid
			'tanggal_konsultasi' => '2025-01-11',
			'keluhan_pasien' => 'Saya merasa sakit kepala dan pusing',
			'status' => 'belum dijawab',
			'balasan_dokter' => null,
		]);
	}

	public function test_get_keluhan_successfully()
	{
		// Simulasi login sebagai admin
		$admin = User::factory()->create([
			'name' => 'Admin Name',
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Admin',
		]);

		$this->actingAs($admin);

		// Membuat data pasien dan dokter
		$userPasien = User::factory()->create([
			'name' => 'Pasien Test',
			'email' => 'pasien@example.com',
		]);
		$userDokter = User::factory()->create([
			'name' => 'Dokter Test',
			'email' => 'dokter@example.com',
		]);

		$pasien = Pasien::create([
			'user_id' => $userPasien->id,
			'pasien_id' => 1, // ID pasien
			'riwayat_medis' => 'Sakit',
			'asuransi' => 'BPJS'
		]);

		$dokter = Dokter::create([
			'user_id' => $userDokter->id,
			'doctor_id' => 1, // ID dokter
			'spesialisasi' => 'Jantung',
			'kualifikasi' => 'S3',
			'pengalaman' => '10 Tahun'
		]);

		// Insert data konsultasi
		$konsultasi = Konsultasi::create([
			'pasien_id' => $pasien->pasien_id,
			'doctor_id' => $dokter->doctor_id,
			'tanggal_konsultasi' => now(),
			'status' => 'terjawab',
			'keluhan_pasien' => 'Batuk dan demam',
			'balasan_dokter' => null,
		]);

		// Melakukan request ke route getKeluhan
		$response = $this->get(route('admin.dashboard-keluhan'));

		// Assert response status is OK (200)
		$response->assertStatus(200);

		// Assert bahwa data konsultasi muncul dalam response
		$response->assertViewHas('consultations', function ($consultations) use ($konsultasi) {
			return $consultations->contains(function ($consultation) use ($konsultasi) {
				return $consultation->konsultasi_id == $konsultasi->konsultasi_id
					&& $consultation->nama_pasien == 'Pasien Test'
					&& $consultation->nama_dokter == 'Dokter Test'
					&& $consultation->keluhan_pasien == 'Batuk dan demam';
			});
		});
	}

	public function testApproveUserUpdatesStatusToApproved()
    {
        // Membuat user dengan status 'pending'
        $user = User::factory()->create(['status' => 'pending']);

        // Login sebagai admin
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        // Kirim permintaan untuk menyetujui user
        $response = $this->get(route('admin.approveUser', ['id' => $user->id]));

        // Periksa respons dan redirect
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Pengguna berhasil disetujui');

        // Periksa apakah status user berubah menjadi 'approved'
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'approved',
        ]);
    }

    public function testRejectUserUpdatesStatusToRejected()
    {
        // Membuat user dengan status 'pending'
        $user = User::factory()->create(['status' => 'pending']);

        // Login sebagai admin
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        // Kirim permintaan untuk menolak user
        $response = $this->get(route('admin.rejectUser', ['id' => $user->id]));

        // Periksa respons dan redirect
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Pengguna berhasil ditolak');

        // Periksa apakah status user berubah menjadi 'rejected'
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'rejected',
        ]);
    }

	public function testHomeDisplaysCorrectData()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
        User::factory()->count(3)->create();
        User::factory()->create(['tipe_pengguna' => 'Dokter']);
        User::factory()->create(['tipe_pengguna' => 'Pasien']);
        Konsultasi::factory()->count(2)->create(['status' => 'terjawab']);
        Konsultasi::factory()->create(['status' => 'reviewed']);
        Konsultasi::factory()->create(['status' => 'belum dijawab']);

        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewHasAll([
            'pengguna', 'dokter', 'pasien', 'keluhanterjawab', 'keluhanbelumdijawab', 'keluhanrated'
        ]);
    }

    public function testDashboardDisplaysAllUsers()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
		
        User::factory()->count(5)->create();

        $response = $this->get(route('admin.dashboard'));

		$response->dump();
		
        $response->assertStatus(200);
        $response->assertViewHas('users', function ($users) {
			Log::info('Users in view: ', $users->toArray()); // Debug log
            return $users->count() === 5;
        });
    }

    public function testShowAddPenggunaFormDisplaysForm()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
        $response = $this->get(route('admin.tambah-pengguna'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tambah-pengguna');
    }

    public function testAddDokterStoresData()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
        $data = [
            'Nama' => 'Dr. Test',
            'Email' => 'drtest@example.com',
            'Password' => 'password',
            'password_confirmation' => 'password',
            'Spesialisasi' => 'Kardiologi',
            'Kualifikasi' => 'Sarjana Kedokteran',
            'Pengalaman' => '5 tahun',
        ];

        $response = $this->post(route('addDokter'), $data);

        $response->assertRedirect(route('admin.dashboard-dokter'));
        $response->assertSessionHas('success', 'Dokter berhasil ditambahkan');

        $this->assertDatabaseHas('users', ['email' => 'drtest@example.com']);
        $this->assertDatabaseHas('doctors', ['spesialisasi' => 'Kardiologi']);
    }

    public function testShowEditPenggunaFormDisplaysForm()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
        $user = User::factory()->create();

        $response = $this->get(route('admin.edit-pengguna', ['id' => $user->id]));

        $response->assertStatus(200);
        $response->assertViewHas('users', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id;
        });
    }

    public function testShowTambahKeluhanFormDisplaysCorrectData()
    {
		$admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
		$this->actingAs($admin);
        $pasien = Pasien::factory()->count(2)->create();
        Dokter::factory()->count(2)->create();

        $response = $this->get(route('admin.tambah-keluhan'));

        $response->assertStatus(200);
        $response->assertViewHas('pasien');
        $response->assertViewHas('doctors');
    }
}

?>