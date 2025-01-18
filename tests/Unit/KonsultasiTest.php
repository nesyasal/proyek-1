<?php

namespace Tests\Unit;

use App\Models\Konsultasi;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\ChatRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KonsultasiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_doctors()
    {
        // Buat dokter
        $doctor = Dokter::factory()->create();

        // Buat konsultasi terkait dengan dokter
        $konsultasi = Konsultasi::factory()->create(['doctor_id' => $doctor->doctor_id]);

        // Verifikasi hubungan dengan dokter
        $this->assertInstanceOf(Dokter::class, $konsultasi->doctors);
        $this->assertEquals($doctor->doctor_id, $konsultasi->doctors->doctor_id);
    }

    /** @test */
    public function it_belongs_to_pasiens()
    {
        // Buat pasien
        $pasien = Pasien::factory()->create();

        // Buat konsultasi terkait dengan pasien
        $konsultasi = Konsultasi::factory()->create(['pasien_id' => $pasien->pasien_id]);

        // Verifikasi hubungan dengan pasien
        $this->assertInstanceOf(Pasien::class, $konsultasi->pasiens);
        $this->assertEquals($pasien->pasien_id, $konsultasi->pasiens->pasien_id);
    }

    /** @test */
    public function it_has_one_chat_room()
    {
        // Buat konsultasi
        $konsultasi = Konsultasi::factory()->create();

        // Buat chat room terkait dengan konsultasi
        $chatRoom = ChatRoom::factory()->create(['konsultasi_id' => $konsultasi->konsultasi_id]);

        // Verifikasi hubungan dengan chat room
        $this->assertInstanceOf(ChatRoom::class, $konsultasi->chatRoom);
        $this->assertEquals($chatRoom->id, $konsultasi->chatRoom->id);
    }
}
