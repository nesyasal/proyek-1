<?php

namespace Tests\Unit;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalDokterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_doctor()
    {
        // Buat data dokter
        $dokter = Dokter::factory()->create();

        // Buat data jadwal dokter yang terkait dengan dokter
        $jadwal = JadwalDokter::factory()->create([
            'doctor_id' => $dokter->doctor_id,
        ]);

        // Verifikasi relasi "doctor"
        $this->assertInstanceOf(Dokter::class, $jadwal->doctor);
        $this->assertEquals($dokter->doctor_id, $jadwal->doctor->doctor_id);
    }
}
