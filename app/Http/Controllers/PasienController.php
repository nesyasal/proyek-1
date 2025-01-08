<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
	public function getDataPasien()
	{
		$pasiens = DB::table('pasien')
			->join('users', 'pasien.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Pasien')
			->select('users.name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'pasien.pasien_id', 'pasien.riwayat_medis', 'pasien.asuransi')
			->get();

		return view('admin.pasien-dashboard', compact('pasiens'));
	}

	public function showAddPasienForm()
	{
		$pasiens = User::where('tipe_pengguna', 'Pasien')->get();
		return view('admin.tambah-pasien', compact('pasiens'));
	}

	protected function validator(array $data, $isUpdate = false)
	{
		$rules = [
			'riwayat_medis' => ['required', 'string'],
			'asuransi' => ['required', 'string', 'max:100'],
		];
	
		if (!$isUpdate) {
			// Aturan tambahan untuk create
			$rules['user_id'] = ['required', 'integer', 'unique:pasien,user_id'];
		}
	
		return Validator::make($data, $rules);
	}

	public function addPasien(Request $request)
	{
		// Lakukan validasi input sesuai kebutuhan

		Pasien::create([
			'user_id' => $request->input('user_id'),
			'riwayat_medis' => $request->input('riwayat_medis'),
			'asuransi' => $request->input('asuransi'),
		]);

		return redirect()->route('admin.pasien-dashboard')->with('success', 'Data pasien berhasil ditambahkan');
	}

	public function showEditPasienForm($pasienId)
	{
		$pasien = Pasien::with('user')->findOrFail($pasienId);

		$pasien = Pasien::findOrFail($pasienId);
		return view('admin.edit-pasien', compact('pasien'));
	}

	public function updatePasien(Request $request, $pasienId)
	{
		// Temukan pasien berdasarkan ID
		$pasien = Pasien::findOrFail($pasienId);

		// Validasi data tanpa user_id karena tidak perlu diubah
		$this->validator($request->all(), $pasienId, true)->validate();

		// Ambil data yang diinginkan untuk diupdate
		$data = $request->only(['riwayat_medis', 'asuransi']);

		// Update data pasien
		$pasien->update($data);

		// Redirect ke halaman dashboard dengan pesan sukses
		return redirect()->route('admin.pasien-dashboard')->with('success', 'Data pasien berhasil diperbarui');
	}

	public function deletePasien($id)
	{
		$pasien = Pasien::findOrFail($id);
		$pasien->delete();

		return redirect()->route('admin.pasien-dashboard')->with('success', 'Data pasien berhasil dihapus');
	}

	public function profile()
	{
		$user = Auth::user();

		$pasien = DB::table('pasien')
		->join('users', 'pasien.user_id', '=', 'users.id')
		->where('pasien.user_id', $user->id)
			->select(
				'pasien.pasien_id',
				'pasien.riwayat_medis',
				'pasien.asuransi',
				'users.name as nama_pasien',
				'users.email',
				'users.username',
				'users.jenis_kelamin',
				'users.tanggal_lahir',
				'users.alamat',
				'users.no_telepon'
			)
			->first();

		return view('pasien.profile', compact(
			'pasien',
			'user'
		));
	}

	public function updateProfile(Request $request)
	{
		$user = Auth::user();

		// Validasi input
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:255',
			'username' => 'required|string|max:255|unique:users,username,' . $user->id,
			'jenis_kelamin' => 'required|string|max:10',
			'tanggal_lahir' => 'required|date',
			'alamat' => 'required|string|max:255',
			'no_telepon' => 'required|string|max:15',
		]);

		// Update data di table users
		DB::table('users')->where('id', $user->id)->update([
			'name' => $request->name,
			'email' => $request->email,
			'username' => $request->username,
			'jenis_kelamin' => $request->jenis_kelamin,
			'tanggal_lahir' => $request->tanggal_lahir,
			'alamat' => $request->alamat,
			'no_telepon' => $request->no_telepon,
		]);

		// Berikan respon sukses
		return redirect()->back()->with('success', 'Profile updated successfully.');
	}

	public function updatePassword(Request $request)
	{
		// Validasi input
		$request->validate([
			'password' => 'required|string|min:8', // Password saat ini
			'newpassword' => 'required|string|min:8|different:password', // Password baru
			'renewpassword' => 'required|string|same:newpassword', // Konfirmasi password baru
		]);

		// Ambil pengguna yang sedang login
		$user = Auth::user();

		// Periksa apakah password saat ini cocok
		if (!Hash::check($request->password, $user->password)) {
			return back()->with('error', 'Kata sandi saat ini salah.');
		}

		// Update password pengguna
		$user->password = Hash::make($request->newpassword);
		$user->save();

		// Redirect dengan pesan sukses
		return back()->with('success', 'Kata sandi berhasil diubah.');
	}
}