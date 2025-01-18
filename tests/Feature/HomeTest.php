<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Konsultasi;
use App\Models\Pasien;
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

	 /** @test */
	public function dashboard_laporan_displays_correct_data()
    {
        // Arrange: Buat data pasien, dokter, dan konsultasi
        $userPasien = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $pasien = Pasien::factory()->create(['user_id' => $userPasien->id]);

        $userDokter = User::factory()->create(['tipe_pengguna' => 'Dokter']);
        $dokter = Dokter::factory()->create(['user_id' => $userDokter->id]);

        Konsultasi::factory()->create(["pasien_id" => $pasien->pasien_id, "doctor_id" => $dokter->doctor_id, "status" => "terjawab"]);
        Konsultasi::factory()->create(["pasien_id" => $pasien->pasien_id, "doctor_id" => $dokter->doctor_id, "status" => "belum dijawab"]);

		$this->actingAs($userPasien);
        // Act: Akses route dashboard_laporan
        $response = $this->get(route('admin.dashboard-laporan'));

        // Assert: Periksa data yang dikirim ke view
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'sum_pasien',
            'total_appoiment',
            'total_pesan_blm_dijawab',
            'total_dokter',
            'consultations'
        ]);
    }

    /** @test */
    public function dashboard_laporan_dokter_displays_correct_data()
    {
        // Arrange: Buat data pasien, dokter, dan konsultasi
        $userPasien = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $pasien = Pasien::factory()->create(['user_id' => $userPasien->id]);

        $userDokter = User::factory()->create(['tipe_pengguna' => 'Dokter']);
        $dokter = Dokter::factory()->create(['user_id' => $userDokter->id]);

        Konsultasi::factory()->create(["pasien_id" => $pasien->pasien_id, "doctor_id" => $dokter->doctor_id, "status" => "terjawab"]);

        $this->actingAs($userDokter); // Set user Dokter sebagai pengguna yang sedang login

        // Act: Akses route dashboard_laporan_dokter
        $response = $this->get(route('laporan.dashboard-laporan'));

        // Assert: Periksa data yang dikirim ke view
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'sum_pasien',
            'total_appoiment',
            'total_pesan_blm_dijawab',
            'total_dokter',
            'consultations'
        ]);
    }
}
