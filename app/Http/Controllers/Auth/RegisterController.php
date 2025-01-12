<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	public function showRegistrationForm()
	{
		return view('auth.register');
	}

	public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		$user = $this->create($request->all());

		return redirect()->route('login');
	}

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'username' => ['required', 'string', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
			'tanggal_lahir' => ['required', 'date'],
			'alamat' => ['required', 'string'],
			'no_telepon' => ['required', 'string', 'max:255'],
			'riwayat_medis' => ['required', 'string'],
			'asuransi' => ['required', 'string'],
		]);
	}

	protected function create(array $data)
	{
		$validatedData = $this->validator($data)->validate();

		$user = User::create([
			'name' => $validatedData['name'],
			'email' => $validatedData['email'],
			'username' => $validatedData['username'],
			'password' => Hash::make($validatedData['password']),
			'jenis_kelamin' => $validatedData['jenis_kelamin'],
			'tanggal_lahir' => $validatedData['tanggal_lahir'],
			'alamat' => $validatedData['alamat'],
			'no_telepon' => $validatedData['no_telepon'],
			'tipe_pengguna' => 'Pasien',
			'status' => 'pending',
			
		]);

		Pasien::create([
			'user_id' => $user->id,
			'riwayat_medis' => $validatedData['riwayat_medis'],
			'asuransi' => $validatedData['asuransi'],
		]);

		return $user;
	}
}