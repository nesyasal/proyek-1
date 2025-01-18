<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Facades\Auth;

class PasienControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetDataPasien()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        Pasien::factory()->count(3)->create();

        $response = $this->get(route('admin.pasien-dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pasiens');
    }

    public function testShowAddPasienForm()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        $response = $this->get(route('admin.tambah-pasien'));

        $response->assertStatus(200);
        $response->assertViewHas('pasiens');
    }

    public function testAddPasien()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        $user = User::factory()->create(['tipe_pengguna' => 'Pasien']);

        $data = [
            'user_id' => $user->id,
            'riwayat_medis' => 'Riwayat penyakit hipertensi.',
            'asuransi' => 'BPJS',
        ];

        $response = $this->post(route('addPasien'), $data);

        $response->assertRedirect(route('admin.pasien-dashboard'));
        $response->assertSessionHas('success', 'Data pasien berhasil ditambahkan');

        $this->assertDatabaseHas('pasien', $data);
    }

    public function testShowEditPasienForm()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        $pasien = Pasien::factory()->create();

        $response = $this->get(route('admin.edit-pasien', ['id' => $pasien->pasien_id]));

        $response->assertStatus(200);
        $response->assertViewHas('pasien');
    }

    public function testUpdatePasien()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        $pasien = Pasien::factory()->create();

        $data = [
            'riwayat_medis' => 'Riwayat penyakit baru.',
            'asuransi' => 'Pribadi',
        ];

        $response = $this->put(route('updatePasien', ['id' => $pasien->pasien_id]), $data);

        $response->assertRedirect(route('admin.pasien-dashboard'));
        $response->assertSessionHas('success', 'Data pasien berhasil diperbarui');

        $this->assertDatabaseHas('pasien', $data);
    }

    public function testDeletePasien()
    {
        $admin = User::factory()->create(['tipe_pengguna' => 'Admin']);
        $this->actingAs($admin);

        $pasien = Pasien::factory()->create();

        $response = $this->delete(route('deletePasien', ['id' => $pasien->pasien_id]));

        $response->assertRedirect(route('admin.pasien-dashboard'));
        $response->assertSessionHas('success', 'Data pasien berhasil dihapus');

        $this->assertDatabaseMissing('pasien', ['pasien_id' => $pasien->pasien_id]);
    }

    public function testProfileDisplaysCorrectData()
    {
        $pasienUser = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $this->actingAs($pasienUser);

        $pasien = Pasien::factory()->create(['user_id' => $pasienUser->id]);

        $response = $this->get(route('pasien.profile'));

        $response->assertStatus(200);
        $response->assertViewHas('pasien');
        $response->assertViewHas('user');
    }

    public function testUpdateProfile()
    {
        $pasienUser = User::factory()->create(['tipe_pengguna' => 'Pasien']);
        $this->actingAs($pasienUser);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'username' => 'updatedusername',
            'jenis_kelamin' => 'Laki-Laki',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Updated Address',
            'no_telepon' => '081234567890',
        ];

        $response = $this->put(route('pasien.profile.edit'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully.');

        $this->assertDatabaseHas('users', $data);
    }

    public function testUpdatePassword()
    {
        $user = User::factory()->create(['tipe_pengguna' => 'Pasien', 'password' => bcrypt('password123')]);
        $this->actingAs($user);

        $data = [
            'password' => 'password123',
            'newpassword' => 'newpassword123',
            'renewpassword' => 'newpassword123',
        ];

        $response = $this->post(route('pasien.reset-password'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Kata sandi berhasil diubah.');

        $this->assertTrue(
            \Hash::check('newpassword123', $user->refresh()->password)
        );
    }
}
