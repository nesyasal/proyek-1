<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Dokter;
use App\Models\Konsultasi;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
	// Halaman untuk user dokter
	public function dashboard()
	{
		$user = Auth::user();

		$doctor = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('doctors.user_id', $user->id)
			->select(
				'doctors.doctor_id',
				'doctors.spesialisasi',
				'users.name as nama_dokter',
				'users.tipe_pengguna'
			)
			->first();

			$konsultasi = DB::table('konsultasi as k')
			->join('pasien as p', 'k.pasien_id', '=', 'p.pasien_id')
			->join('users as pu', 'p.user_id', '=', 'pu.id')
			->join('doctors as d', 'k.doctor_id', '=', 'd.doctor_id')
			->join('users as du', 'd.user_id', '=', 'du.id')
			->join('review as r', 'k.konsultasi_id', '=', 'r.konsultasi_id')
			->where('k.doctor_id', $doctor->doctor_id)
			->select(
				'k.konsultasi_id',
				'k.tanggal_konsultasi',
				'k.status',
				'k.keluhan_pasien',
				'k.balasan_dokter',
				'r.rating',
				'pu.name as nama_pasien',
				'du.name as nama_dokter'
			)
			->get();

		$total_terjawab = DB::table('konsultasi')
			->where('doctor_id', $doctor->doctor_id)
			->where('status', 'terjawab')
			->count();

		$total_reviewed = DB::table('konsultasi')
			->where('doctor_id', $doctor->doctor_id)
			->where('status', 'reviewed')
			->count();

		$total_pesan_blm_dijawab = DB::table('konsultasi')
			->where('doctor_id', $doctor->doctor_id)
			->where('status', 'belum dijawab')
			->count();

		// Statistik total pasien (tidak perlu difilter karena global)
		$sum_pasien = Pasien::count('*');

		return view('dokter.dashboard', compact(
			'konsultasi',
			'sum_pasien',
			'total_terjawab',
			'total_pesan_blm_dijawab',
			'total_reviewed',
			'doctor',
			'user'
		));
	}

	public function profile()
	{
		$user = Auth::user();

		$doctor = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('doctors.user_id', $user->id)
			->select(
				'doctors.doctor_id',
				'doctors.spesialisasi',
				'users.name as nama_dokter',
				'users.email',
				'users.username',
				'users.jenis_kelamin',
				'users.tanggal_lahir',
				'users.alamat',
				'users.no_telepon'
			)
			->first();

		return view('dokter.profile', compact(
			'doctor',
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

	public function respon($konsultasi_id)
	{
		$respon = DB::table('konsultasi')
			->join('pasien', 'konsultasi.pasien_id', '=', 'pasien.pasien_id')
			->join('users', 'users.id', '=', 'pasien.user_id')
			->where('konsultasi.konsultasi_id', $konsultasi_id)
			->select(
				'users.name',
				'users.jenis_kelamin',
				'users.tanggal_lahir',
				'users.alamat',
				'users.no_telepon',
				'pasien.asuransi',
				'pasien.riwayat_medis',
				'konsultasi.tanggal_konsultasi',
				'konsultasi.status',
				'konsultasi.keluhan_pasien',
				'konsultasi.konsultasi_id'
			)
			->get();

		return view('dokter.respon', compact('respon'));
	}

	public function responKeluhan(Request $request, $konsultasi_id)
	{

		$doctors = konsultasi::findOrFail($konsultasi_id);

		// Update data pasien
		$doctors->update([
			'status' => 'terjawab',
			'balasan_dokter' => $request->respon
		]);
		//dd($request->respon);

		// Redirect ke halaman dashboard dengan pesan sukses
		return redirect()->route('dokter.dashboard')->with('success', 'Data dokter berhasil diperbarui');
	}


	// Dashboard dokter di admin
	public function dashboardDokter()
	{
		$doctors = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Dokter')
			->select(
				'users.name',
				'users.email',
				'users.jenis_kelamin',
				'users.alamat',
				'users.no_telepon',
				'users.tanggal_lahir',
				'doctors.doctor_id',
				'doctors.spesialisasi',
				'doctors.kualifikasi',
				'doctors.pengalaman'
			)
			->get();


		return view('admin.dashboard-dokter', compact('doctors'));
	}

	// Halaman tambah data dokter di admin
	public function showAddDokterForm()
	{
		$dokter = User::where('tipe_pengguna', 'Dokter')->get();
		return view('admin.tambah-dokter', compact('dokter'));
	}

	// validator body request insert dan update data
	protected function validator(array $data, $isUpdate = false)
	{
		$rules = [
			'spesialisasi' => ['required', 'string', 'max:100'],
			'kualifikasi' => ['required', 'string', 'max:100'],
			'pengalaman' => ['required', 'string', 'max:255'],
		];

		if (!$isUpdate) {
			// Aturan tambahan untuk create
			$rules['user_id'] = ['required', 'integer', 'unique:pasien,user_id'];
		}

		return Validator::make($data, $rules);
	}

	// function untuk tambah data dokter di admin
	public function addDokter(Request $request)
	{
		// Lakukan validasi input sesuai kebutuhan

		Dokter::create([
			'user_id' => $request->input('user_id'),
			'spesialisasi' => $request->input('Spesialisasi'),
			'kualifikasi' => $request->input('Kualifikasi'),
			'pengalaman' => $request->input('Pengalaman'),
		]);

		return redirect()->route('admin.dashboard-dokter')->with('success', 'Data dokter berhasil ditambahkan');
	}

	public function deleteDokter($doctor_id)
	{
		$dokter = Dokter::findOrFail($doctor_id);
		$dokter->delete();

		return redirect()->route('admin.dashboard-dokter')->with('success', 'Data dokter berhasil dihapus');
	}

	public function showEditDokterForm($doctor_id)
	{
		$doctors = Dokter::with('user')->findOrFail($doctor_id);

		$doctors = Dokter::findOrFail($doctor_id);


		return view('admin.edit-dokter', compact('doctors'));
	}

	public function updateDokter(Request $request, $doctors_Id)
	{
		// Temukan pasien berdasarkan ID
		$doctors = Dokter::findOrFail($doctors_Id);

		// Validasi data tanpa user_id karena tidak perlu diubah
		//$this->validator($request->all(), $doctors_Id, true)->validate();

		// Ambil data yang diinginkan untuk diupdate
		$data = $request->only(['spesialisasi', 'kualifikasi', 'pengalaman']);

		// Update data pasien
		$doctors->update($data);

		// Redirect ke halaman dashboard dengan pesan sukses
		return redirect()->route('admin.dashboard-dokter')->with('success', 'Data dokter berhasil diperbarui');
	}
}
