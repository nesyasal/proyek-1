<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Konsultasi;

class KonsultasiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Jika ada seeder untuk data dasar
    }

    public function testDashboardDisplaysCorrectData()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $pasien = Pasien::factory()->create(['user_id' => $user->id]);

        Konsultasi::factory()->count(2)->create(['pasien_id' => $pasien->pasien_id]);

        $this->actingAs($user);

        $response = $this->get(route('pasien.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('consultations');
        $response->assertViewHas('pasien');
    }

    public function testTambahKeluhanStoresData()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $pasien = Pasien::factory()->create(['user_id' => $user->id]);
        $dokter = Dokter::factory()->create();

        $this->actingAs($user);

        $data = [
            'date' => now()->toDateString(),
            'doctor' => $dokter->doctor_id,
            'message' => 'Keluhan baru pasien.',
        ];

        $response = $this->post(route('tambahKeluhan'), $data);

        $response->assertRedirect(route('pasien.dashboard'));
        $response->assertSessionHas('success', 'Permintaan konsultasi berhasil dikirim.');

        $this->assertDatabaseHas('konsultasi', [
            'pasien_id' => $pasien->pasien_id,
            'doctor_id' => $dokter->doctor_id,
            'keluhan_pasien' => 'Keluhan baru pasien.',
        ]);
    }

    public function testFormEditKeluhanDisplaysCorrectData()
    {
         /** @var \App\Models\User $user */
        $user = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $pasien = Pasien::factory()->create();
        $dokter = Dokter::factory()->create();
        $konsultasi = Konsultasi::factory()->create([
            'pasien_id' => $pasien->pasien_id,
            'doctor_id' => $dokter->doctor_id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('admin.edit-keluhan', ['konsultasi_id' => $konsultasi->konsultasi_id]));

        $response->assertStatus(200);
        $response->assertViewHas('konsultasi');
        $response->assertViewHas('doctors');
    }

    public function testUpdateKeluhanUpdatesData()
    {
         /** @var \App\Models\User $user */
         $user = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $dokter1 = Dokter::factory()->create();
        $dokter2 = Dokter::factory()->create();
        $konsultasi = Konsultasi::factory()->create([
            'doctor_id' => $dokter1->doctor_id,
        ]);

        $this->actingAs($user);

        $data = [
            'tanggal_konsultasi' => now()->addDays(3)->toDateString(),
            'doctor_id' => $dokter2->doctor_id,
            'keluhan_pasien' => 'Keluhan diperbarui.',
        ];

        $response = $this->put(route('updateKeluhan', ['konsultasi_id' => $konsultasi->konsultasi_id]), $data);

        $response->assertRedirect(route('admin.dashboard-keluhan'));
        $response->assertSessionHas('success', 'Konsultasi berhasil diperbarui.');

        $this->assertDatabaseHas('konsultasi', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'doctor_id' => $dokter2->doctor_id,
            'keluhan_pasien' => 'Keluhan diperbarui.',
        ]);
    }

    public function testDeleteKeluhanDeletesData()
    {
         /** @var \App\Models\User $user */
        $user = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($user);

        $konsultasi = Konsultasi::factory()->create();

        $response = $this->delete(route('deleteKeluhan', ['KonsultasiId' => $konsultasi->konsultasi_id]));

        $response->assertRedirect(route('admin.dashboard-keluhan'));
        $response->assertSessionHas('success', 'Data Keluhan Pasien berhasil dihapus');

        $this->assertDatabaseMissing('konsultasi', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
        ]);
    }

    public function testTerimaKonsultasiUpdatesStatusAndCreatesChatRoom()
    {
        $pasien = Pasien::factory()->create();
        $dokter = Dokter::factory()->create();
        $konsultasi = Konsultasi::factory()->create([
            'pasien_id' => $pasien->pasien_id,
            'doctor_id' => $dokter->doctor_id,
            'status' => 'belum dijawab',
        ]);

        $response = $this->post(route('konsultasi.terima', ['KonsultasiId' => $konsultasi->konsultasi_id]));

        $response->assertRedirect(route('chat', ['konsultasiId' => $konsultasi->konsultasi_id]));
        $response->assertSessionHas('success', 'Konsultasi diterima dan chat room dibuat.');

        $this->assertDatabaseHas('konsultasi', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'status' => 'terjawab',
        ]);

        $this->assertDatabaseHas('chat_rooms', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
        ]);
    }

    public function testDashboardKeluhanDisplaysCorrectData()
    {
         /** @var \App\Models\User $user */
        // Membuat user dengan tipe pengguna "Pasien"
        $user = User::factory()->create(['tipe_pengguna' => 'Pasien']);

        // Membuat pasien yang terkait dengan user
        $pasien = Pasien::factory()->create(['user_id' => $user->id]);

        // Membuat beberapa dokter untuk data "Pilih Dokter"
        $dokter1 = Dokter::factory()->create();
        $dokter2 = Dokter::factory()->create();

        // Login sebagai user pasien
        $this->actingAs($user);

        // Hit endpoint untuk dashboard keluhan
        $response = $this->get(route('pasien.dashboard-keluhan'));

        // Memastikan halaman dimuat dengan sukses
        $response->assertStatus(200);

        // Memastikan data pasien dikirim ke view
        $response->assertViewHas('pasien', function ($viewPasien) use ($pasien) {
            return $viewPasien->pasien_id === $pasien->pasien_id;
        });

        // Memastikan data dokter dikirim ke view
        $response->assertViewHas('doctors', function ($viewDoctors) use ($dokter1, $dokter2) {
            return $viewDoctors->contains('doctor_id', $dokter1->doctor_id) &&
                   $viewDoctors->contains('doctor_id', $dokter2->doctor_id);
        });
    }
}
