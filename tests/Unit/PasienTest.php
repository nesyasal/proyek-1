<?php

namespace Tests\Unit;

use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasienTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_konsultasi()
    {
        // Buat pasien
        $pasien = Pasien::factory()->create();

        // Buat beberapa konsultasi terkait dengan pasien
        $konsultasi1 = Konsultasi::factory()->create(['pasien_id' => $pasien->pasien_id]);
        $konsultasi2 = Konsultasi::factory()->create(['pasien_id' => $pasien->pasien_id]);

        // Verifikasi hubungan konsultasi
        $this->assertTrue($pasien->konsultasi->contains($konsultasi1));
        $this->assertTrue($pasien->konsultasi->contains($konsultasi2));
        $this->assertEquals(2, $pasien->konsultasi->count());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat pasien terkait dengan user
        $pasien = Pasien::factory()->create(['user_id' => $user->id]);

        // Verifikasi hubungan user
        $this->assertInstanceOf(User::class, $pasien->user);
        $this->assertEquals($user->id, $pasien->user->id);
    }
}
