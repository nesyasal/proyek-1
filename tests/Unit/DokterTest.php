<?php

namespace Tests\Unit;

use App\Models\Dokter;
use App\Models\User;
use App\Models\Konsultasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DokterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_konsultasi()
    {
        // Buat dokter
        $dokter = Dokter::factory()->create();

        // Buat beberapa konsultasi terkait dengan dokter
        $konsultasi1 = Konsultasi::factory()->create(['doctor_id' => $dokter->doctor_id]);
        $konsultasi2 = Konsultasi::factory()->create(['doctor_id' => $dokter->doctor_id]);

        // Verifikasi hubungan konsultasi
        $this->assertTrue($dokter->konsultasi->contains($konsultasi1));
        $this->assertTrue($dokter->konsultasi->contains($konsultasi2));
        $this->assertEquals(2, $dokter->konsultasi->count());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat dokter terkait dengan user
        $dokter = Dokter::factory()->create(['user_id' => $user->id]);

        // Verifikasi hubungan user
        $this->assertInstanceOf(User::class, $dokter->user);
        $this->assertEquals($user->id, $dokter->user->id);
    }
}
