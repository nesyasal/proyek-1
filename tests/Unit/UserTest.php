<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_pasiens()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat beberapa pasien terkait dengan user
        $pasiens = Pasien::factory()->count(3)->create(['user_id' => $user->id]);

        // Verifikasi relasi pasiens pada user
        $this->assertCount(3, $user->pasiens);

        foreach ($pasiens as $pasien) {
            $this->assertTrue($user->pasiens->contains($pasien));
        }
    }

    /** @test */
    public function it_has_many_doctors()
    {
        // Buat user
        $user = User::factory()->create();

        // Buat beberapa dokter terkait dengan user
        $doctors = Dokter::factory()->count(2)->create(['user_id' => $user->id]);

        // Verifikasi relasi doctors pada user
        $this->assertCount(2, $user->doctors);

        foreach ($doctors as $doctor) {
            $this->assertTrue($user->doctors->contains($doctor));
        }
    }
}
