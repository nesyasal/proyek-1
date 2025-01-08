<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
}