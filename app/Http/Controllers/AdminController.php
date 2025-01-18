<?php

namespace App\Http\Controllers;

use App\Models\dokter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
	public function home()
	{
		$pengguna = DB::table('users')->count();

		$dokter = DB::table('users')->where('tipe_pengguna', 'Dokter')->count();

		$pasien = DB::table('users')->where('tipe_pengguna', 'Pasien')->count();

		$keluhanterjawab = DB::table('konsultasi')->where('status', 'terjawab')->orWhere('status', 'reviewed')
			->count();

		$keluhanbelumdijawab = DB::table('konsultasi')->where('status', 'belum dijawab')->count();

		$keluhanrated = DB::table('konsultasi')->where('status', 'reviewed')->count();

		return view('admin.home', compact('pengguna', 'dokter', 'pasien', 'keluhanterjawab', 'keluhanbelumdijawab', 'keluhanrated'));
	}

	public function dashboard()
	{
		$users = User::all();
		Log::info('Users sent to view: ', $users->toArray()); // Debug log
		return view('admin.dashboard', compact('users'));
	}

	public function showAddPenggunaForm()
	{
		return view('admin.tambah-pengguna');
	}

	public function addPengguna(Request $request)
	{
		$this->validator($request->all())->validate();

		$user = $this->create($request->all());

		return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil ditambahkan');
	}

	public function addDokter(Request $request)
	{

		$users	 = User::create([
			'nama' => $request->Nama,
			'email' => $request->Email,
			'password' => $request->Password,
			'Confirm Password' => $request->password_confirmation,
		]);


		//$this->validator($request->all())->validate();

		$dokter	 = dokter::create([
			'user_id' => '12',
			'spesialisasi' => $request->Spesialisasi,
			'kualifikasi' => $request->Kualifikasi,
			'pengalaman' => $request->Pengalaman,
		]);

		return redirect()->route('admin.dashboard-dokter')->with('success', 'Dokter berhasil ditambahkan');
	}

	protected function validator(array $data, $userId = null)
	{
		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
			'username' => ['required', 'string', 'max:255'],
			'tanggal_lahir' => ['required', 'date'],
			'jenis_kelamin' => ['required', 'string', 'max:255'],
			'alamat' => ['required', 'string', 'max:255'],
			'no_telepon' => ['required', 'string', 'max:255'],
			'tipe_pengguna' => ['required', 'string', 'max:255'],
		];

		if ($userId) {
			// Only apply password validation if password is filled
			$rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
		} else {
			$rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
		}

		return Validator::make($data, $rules);
	}

	protected function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'username' => $data['username'],
			'tanggal_lahir' => $data['tanggal_lahir'],
			'jenis_kelamin' => $data['jenis_kelamin'],
			'alamat' => $data['alamat'],
			'no_telepon' => $data['no_telepon'],
			'tipe_pengguna' => $data['tipe_pengguna'],
		]);
	}

	public function showEditPenggunaForm($id)
	{
		$users = User::findOrFail($id);
		return view('admin.edit-pengguna', compact('users'));
	}

	public function updatePengguna(Request $request, $id)
	{
		$user = User::findOrFail($id);

		// Debug untuk memastikan data yang diterima benar
		// dd($request->all());

		$this->validator($request->all(), $user->id)->validate();

		$data = $request->except(['_token', '_method', 'password_confirmation']);

		if ($request->filled('password')) {
			$data['password'] = Hash::make($request->input('password'));
		} else {
			unset($data['password']);
		}

		// Debug sebelum melakukan update
		// dd($data);

		$user->update($data);

		return redirect()->route('admin.dashboard')->with('success', 'Data pengguna berhasil diperbarui');
	}

	public function deletePengguna($id)
	{
		$user = User::findOrFail($id);
		$user->delete();

		return redirect()->route('admin.dashboard')->with('success', 'Data pengguna berhasil dihapus');
	}

	public function showTambahKeluhanForm()
	{
		// Query untuk ambil data dari tabel pasien dan user untuk mengisi field Nama, Email dan No. Telepon
		$pasien = DB::table('pasien')
			->join('users', 'pasien.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Pasien')
			->select('users.id', 'users.name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'pasien.pasien_id', 'pasien.riwayat_medis', 'pasien.asuransi')
			->get();

		// Query untuk ambil data dari tabel doctors dan user untuk mengisi field Pilih Dokter
		$doctors = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Dokter')
			->select('users.id', 'users.name as doctor_name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'doctors.doctor_id', 'doctors.spesialisasi', 'doctors.kualifikasi', 'doctors.pengalaman')
			->get();

		return view('admin.tambah-keluhan', compact('pasien', 'doctors'));
	}

	public function addKeluhan(Request $request)
	{
		// Validasi data input
		$request->validate([
			'pasien_id' => 'required|integer',
			'tanggal_konsultasi' => 'required|date',
			'doctor_id' => 'required|integer',
			'keluhan_pasien' => 'required|string',
		]);

		$pasien = DB::table('pasien')
			->where('user_id', $request->pasien_id)
			->select('pasien_id')
			->first();

		// Ambil doctor_id dari tabel doctors
		$doctor = DB::table('doctors')
			->where('user_id', $request->doctor_id)
			->select('doctor_id')
			->first();

		// Cek apakah pasien_id dan doctor_id ditemukan
		if (!$pasien || !$doctor) {
			return redirect()->back()->with('error', 'Pasien atau Dokter tidak ditemukan.');
		}

		// Insert data ke dalam tabel konsultasi
		DB::table('konsultasi')->insert([
			'pasien_id' => $pasien->pasien_id,
			'doctor_id' => $doctor->doctor_id,
			'tanggal_konsultasi' => $request->tanggal_konsultasi,
			'status' => 'belum dijawab',
			'keluhan_pasien' => $request->keluhan_pasien,
			'balasan_dokter' => null,
		]);

		return redirect()->route('admin.dashboard-keluhan')->with('success', 'Keluhan Pasien berhasil ditambahkan');
	}

	public function getKeluhan()
	{
		$consultations = Konsultasi::select(
			'konsultasi.konsultasi_id',
			'users_pasien.name as nama_pasien',
			'users_dokter.name as nama_dokter',
			'tanggal_konsultasi',
			'konsultasi.status', // Pastikan menggunakan alias tabel yang benar
			'keluhan_pasien',
			'balasan_dokter'
		)
			->join('pasien', 'konsultasi.pasien_id', '=', 'pasien.pasien_id')
			->join('doctors', 'konsultasi.doctor_id', '=', 'doctors.doctor_id')
			->join('users as users_pasien', 'pasien.user_id', '=', 'users_pasien.id')
			->join('users as users_dokter', 'doctors.user_id', '=', 'users_dokter.id')
			->get();

		return view('admin.dashboard-keluhan', compact('consultations'));
	}

	public function approveUser($id)
	{
		// Update status pengguna menjadi 'approved'
		$user = User::findOrFail($id);
		$user->update(['status' => 'approved']);

		return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil disetujui');
	}

	public function rejectUser($id)
	{
		// Update status pengguna menjadi 'rejected'
		$user = User::findOrFail($id);
		$user->update(['status' => 'rejected']);

		return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil ditolak');
	}
}
