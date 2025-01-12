<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Test login success for different user types.
	 */
	public function test_login_success_for_admin()
	{
		// Buat user tipe Admin
		$user = User::factory()->create([
			'email' => 'admin@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Admin',
			'status' => 'approved',
		]);

		// Kirimkan request login
		$response = $this->post(route('login'), [
			'email' => 'admin@example.com',
			'password' => 'password123',
		]);

		// Pastikan redirect ke halaman admin
		$response->assertRedirect(route('admin.home'));
		$this->assertAuthenticatedAs($user);
	}

	public function test_login_success_for_patient()
	{
		// Buat user tipe Pasien
		$user = User::factory()->create([
			'email' => 'patient@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Pasien',
			'status' => 'approved',
		]);

		// Kirimkan request login
		$response = $this->post(route('login'), [
			'email' => 'patient@example.com',
			'password' => 'password123',
		]);

		// Pastikan redirect ke dashboard pasien
		$response->assertRedirect(route('pasien.dashboard'));
		$this->assertAuthenticatedAs($user);
	}

	public function test_login_success_for_doctor()
	{
		// Buat user tipe Dokter
		$user = User::factory()->create([
			'email' => 'doctor@example.com',
			'password' => bcrypt('password123'),
			'tipe_pengguna' => 'Dokter',
			'status' => 'approved',
		]);

		// Kirimkan request login
		$response = $this->post(route('login'), [
			'email' => 'doctor@example.com',
			'password' => 'password123',
		]);

		// Pastikan redirect ke dashboard dokter
		$response->assertRedirect(route('dokter.dashboard'));
		$this->assertAuthenticatedAs($user);
	}

	/**
	 * Test login failure due to incorrect credentials.
	 */
	public function test_login_failure_invalid_credentials()
	{
		// Buat user
		User::factory()->create([
			'email' => 'invalid@example.com',
			'password' => bcrypt('password123'),
		]);

		// Kirimkan login dengan password salah
		$response = $this->post(route('login'), [
			'email' => 'invalid@example.com',
			'password' => 'wrongpassword',
		]);

		// Pastikan error dan tidak terautentikasi
		$response->assertSessionHasErrors(['email']);
		$this->assertGuest();
	}

	/**
	 * Test login failure due to pending status.
	 */
	public function test_login_failure_pending_status()
	{
		// Buat user dengan status pending
		User::factory()->create([
			'email' => 'pending@example.com',
			'password' => bcrypt('password123'),
			'status' => 'pending',
		]);

		// Kirimkan request login
		$response = $this->post(route('login'), [
			'email' => 'pending@example.com',
			'password' => 'password123',
		]);

		// Pastikan error dan tidak terautentikasi
		$response->assertSessionHasErrors(['email']);
		$this->assertGuest();
	}

	/**
	 * Test login failure due to rejected status.
	 */
	public function test_login_failure_rejected_status()
	{
		// Buat user dengan status rejected
		User::factory()->create([
			'email' => 'rejected@example.com',
			'password' => bcrypt('password123'),
			'status' => 'rejected',
		]);

		// Kirimkan request login
		$response = $this->post(route('login'), [
			'email' => 'rejected@example.com',
			'password' => 'password123',
		]);

		// Pastikan error dan tidak terautentikasi
		$response->assertSessionHasErrors(['email']);
		$this->assertGuest();
	}
}
