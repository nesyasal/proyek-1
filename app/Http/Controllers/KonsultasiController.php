<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\Konsultasi;
use Illuminate\Support\Str;

class KonsultasiController extends Controller
{
	public function dashboard()
	{
		// Mendapatkan ID pengguna yang sedang login
		$userId = Auth::id();

		// Mendapatkan pasien_id dari tabel pasien berdasarkan user_id
		$pasienId = Pasien::where('user_id', $userId)->value('pasien_id');

		$pasien = DB::table('pasien')
			->join('users', 'pasien.user_id', '=', 'users.id')
			->where('pasien.user_id', $userId)
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

		$consultations = Konsultasi::select(
			'konsultasi.konsultasi_id',
			'users_pasien.name as nama_pasien',
			'users_dokter.name as nama_dokter',
			'konsultasi.tanggal_konsultasi',
			'konsultasi.status',
			'konsultasi.keluhan_pasien',
			'konsultasi.balasan_dokter',
			'review.rating'
		)
			->join('pasien', 'konsultasi.pasien_id', '=', 'pasien.pasien_id')
			->join(
				'doctors',
				'konsultasi.doctor_id',
				'=',
				'doctors.doctor_id'
			)
			->join('users as users_pasien', 'pasien.user_id', '=', 'users_pasien.id')
			->join('users as users_dokter', 'doctors.user_id', '=', 'users_dokter.id')
			->leftJoin('review', 'konsultasi.konsultasi_id', '=', 'review.konsultasi_id')
			->where('pasien.pasien_id', $pasienId)
			->get();

		return view('pasien.dashboard', compact('consultations', 'pasien'));
	}

	public function dashboardKeluhan()
	{
		// Ambil user_id dari user yang sedang login
		$userId = Auth::id();

		// Query untuk ambil data dari tabel pasien dan user untuk mengisi field Nama, Email dan No. Telepon
		$pasien = DB::table('pasien')
			->join('users', 'pasien.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Pasien')
			->select('users.name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'pasien.pasien_id', 'pasien.riwayat_medis', 'pasien.asuransi')
			->where('users.id', $userId)
			->first();

		// Query untuk ambil data dari tabel doctors dan user untuk mengisi field Pilih Dokter
		$doctors = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Dokter')
			->select('users.name as doctor_name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'doctors.doctor_id', 'doctors.spesialisasi', 'doctors.kualifikasi', 'doctors.pengalaman')
			->get();

		// Menampilkan halaman beserta data yang diambil
		return view('pasien.dashboard-keluhan', compact('pasien', 'doctors'));
	}

	public function tambahKeluhan(Request $request)
	{
		// Validasi data input
		$request->validate([
			'date' => 'required|date',
			'doctor' => 'required|integer',
			'message' => 'required|string',
		]);

		// Mendapatkan user_id dari sesi login
		$userId = Auth::id();

		// Mendapatkan pasien_id dari tabel pasien berdasarkan user_id
		$pasienId = DB::table('pasien')->where('user_id', $userId)->value('pasien_id');

		// Insert data ke dalam tabel konsultasi
		DB::table('konsultasi')->insert([
			'pasien_id' => $pasienId,
			'doctor_id' => $request->doctor,
			'tanggal_konsultasi' => $request->date,
			'status' => 'belum dijawab',
			'keluhan_pasien' => $request->message,
			'balasan_dokter' => null,
		]);

		// Redirect ke halaman lain atau tampilkan pesan sukses
		return redirect()->route('pasien.dashboard')->with('success', 'Permintaan konsultasi berhasil dikirim.');
	}

	public function formEditKeluhan($id)
	{
		$konsultasi = DB::table('konsultasi')
			->join('pasien', 'konsultasi.pasien_id', '=', 'pasien.pasien_id')
			->join('users as pasien_users', 'pasien.user_id', '=', 'pasien_users.id')
			->join('doctors', 'konsultasi.doctor_id', '=', 'doctors.doctor_id')
			->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
			->select('konsultasi.*', 'pasien_users.name as pasien_name', 'doctor_users.name as doctor_name', 'doctors.doctor_id', 'pasien.pasien_id')
			->where('konsultasi.konsultasi_id', $id)
			->first();

		$doctors = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->select('doctors.doctor_id', 'users.name as doctor_name')
			->get();

		return view('admin.edit-keluhan', compact('konsultasi', 'doctors'));
	}

	public function updateKeluhan(Request $request, $konsultasi_id)
	{
		// Validasi data input
		$request->validate([
			'tanggal_konsultasi' => 'required|date',
			'doctor_id' => 'required|integer',
			'keluhan_pasien' => 'required|string',
		]);

		// Update data dalam tabel konsultasi
		$update = DB::table('konsultasi')->where('konsultasi_id', $konsultasi_id)->update([
			'doctor_id' => $request->doctor_id,
			'tanggal_konsultasi' => $request->tanggal_konsultasi,
			'keluhan_pasien' => $request->keluhan_pasien,
		]);

		if ($update) {
			return redirect()->route('admin.dashboard-keluhan')->with('success', 'Konsultasi berhasil diperbarui.');
		} else {
			return redirect()->back()->with('error', 'Gagal memperbarui konsultasi.');
		}
	}

	public function deleteKeluhan($konsultasiId)
	{
		$konsultasi = Konsultasi::findOrFail($konsultasiId);
		$konsultasi->delete();

		return redirect()->route('admin.dashboard-keluhan')->with('success', 'Data Keluhan Pasien berhasil dihapus');
	}

	public function terimaKonsultasi($konsultasiId)
	{
		$konsultasi = Konsultasi::with(['pasiens', 'doctors'])->findOrFail($konsultasiId);

		if ($konsultasi->status === 'belum dijawab') {
			// Update status konsultasi menjadi diterima
			$konsultasi->status = 'terjawab';
			$konsultasi->save();

			// Cek apakah chat room untuk konsultasi ini sudah ada berdasarkan konsultasi_id
			$room = DB::table('chat_rooms')
				->where('konsultasi_id', $konsultasi->konsultasi_id)
				->first();

			$uuid = $room ? $room->id : Str::orderedUuid();

			if (!$room) {
				// Jika belum ada, buat chat room baru
				DB::table('chat_rooms')->insert([
					'id' => $uuid,
					'name' => 'Konsultasi: ' . $konsultasi->keluhan_pasien,
					'Konsultasi_id' => $konsultasi->konsultasi_id,
					'created_at' => now(),
					'updated_at' => now(),
				]);
				$roomId = $uuid;
			} else {
				// Jika room sudah ada, gunakan ID yang ada
				$roomId = $room->id;
			}

			// Tambahkan pengguna (pasien dan dokter) ke chat room jika belum ada
			DB::table('chat_room_users')->Insert([
				['chat_room_id' => $roomId,
                'user_id' => $konsultasi->pasiens->user_id,
                'created_at' => now(),
                'updated_at' => now(),],

				['chat_room_id' => $roomId,
                'user_id' => $konsultasi->doctors->user_id,
                'created_at' => now(),
                'updated_at' => now(),]
			]);

			// Redirect ke halaman chat room
			return redirect()->route('chat', ['konsultasiId' => $konsultasiId])->with('success', 'Konsultasi diterima dan chat room dibuat.');
		}

		return redirect()->back()->with('error', 'Konsultasi tidak valid atau sudah diterima.');
	}
}
