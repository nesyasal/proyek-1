<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
	public function showLoginForm()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		// validator request data email dan password (validasi)
		$this->validator($request->all())->validate();

		// Ambil pengguna berdasarkan email
        $user = \App\Models\User::where('email', $request->email)->first();

        // Validasi status pengguna sebelum login
        if ($user && $user->status === 'rejected') {
            return back()->withErrors([
                'email' => 'Akun Anda telah ditolak oleh admin.',
            ]);
        } elseif ($user && $user->status === 'pending') {
			return back()->withErrors([
				'email' => 'Akun Anda belum di setujui oleh admin.'
			]);
		}

		// kondisi untuk menentukan tipe pengguna dari user email yang digunakan
		if (Auth::attempt($request->only('username', 'email', 'password'))) {
			$user = Auth::user();

			// cek tipe pengguna
			switch ($user->tipe_pengguna) {
				case "Admin":
					return redirect()->route('admin.home');
					break;
				case "Pasien":
					return redirect()->route('pasien.dashboard');
					break;
				case "Dokter":
					return redirect()->route('dokter.dashboard');
					break;
				default:
					return redirect()->route('index'); // Redirect to default page
					break;
			}
		}

		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		]);
	}

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'email' => ['required', 'string', 'email', 'max:255'],
			'password' => ['required', 'string', 'min:8'],
		]);
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}