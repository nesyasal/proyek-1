<?php

namespace Tests\Feature;

use App\Models\Konsultasi;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_shows_review_form_for_valid_consultation()
    {
        // Buat user dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Buat konsultasi dengan status bukan 'reviewed'
        $konsultasi = Konsultasi::factory()->create(['status' => 'terjawab']);

        // Akses form review
        $response = $this->get(route('review.create', ['konsultasiId' => $konsultasi->konsultasi_id]));

        // Verifikasi
        $response->assertStatus(200);
        $response->assertViewIs('review');
        $response->assertViewHas('konsultasi', function ($viewKonsultasi) use ($konsultasi) {
            return $viewKonsultasi->konsultasi_id === $konsultasi->konsultasi_id;
        });
    }

    public function test_cannot_create_review_if_already_exists()
    {
        // Buat user dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Buat konsultasi dan review
        $konsultasi = Konsultasi::factory()->create(['status' => 'terjawab']);
        Review::factory()->create(['konsultasi_id' => $konsultasi->konsultasi_id]);

        // Kirim permintaan review
        $response = $this->post(route('tambahReview'), [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'rating' => '5',
            'pesan' => 'Layanan sangat baik.',
        ]);

        // Verifikasi
        $response->assertRedirect();
        $response->assertSessionHasErrors(['error' => 'Review sudah pernah dibuat untuk konsultasi ini.']);
    }

    public function test_tambah_review_stores_review_and_updates_consultation_status()
    {
        // Buat user dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Buat konsultasi
        $konsultasi = Konsultasi::factory()->create(['status' => 'belum dijawab']);

        // Data untuk review
        $data = [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'rating' => '5',
            'pesan' => 'Pelayanan sangat baik.',
        ];

        // Kirim permintaan untuk menyimpan review
        $response = $this->post(route('review.store'), $data);

        // Verifikasi redirect dan pesan sukses
        $response->assertRedirect(route('pasien.dashboard'));
        $response->assertSessionHas('success', 'Review berhasil disimpan.');

        // Verifikasi data tersimpan di database
        $this->assertDatabaseHas('review', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'rating' => '5',
            'pesan' => 'Pelayanan sangat baik.',
        ]);

        // Verifikasi status konsultasi diperbarui
        $this->assertDatabaseHas('konsultasi', [
            'konsultasi_id' => $konsultasi->konsultasi_id,
            'status' => 'reviewed',
        ]);
    }
}
