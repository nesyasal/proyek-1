<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function getDataJadwalDokter()
	{
		$jadwals = DB::table('jadwal_dokter')
            ->join('doctors', 'jadwal_dokter.doctor_id', '=', 'doctors.doctor_id')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->where('users.tipe_pengguna', 'Dokter')
            ->select(
				'jadwal_dokter.jadwal_id', // Tambahkan ini
				'users.name as doctor_name',
				'doctors.spesialisasi',
				'jadwal_dokter.hari',
				'jadwal_dokter.jam_mulai',
				'jadwal_dokter.jam_selesai'
			)
			->get();

		// Debugging
		// dd($jadwals);

		return view('admin.dashboard-jadwal', compact('jadwals'));
	}

    public function showAddJadwalForm()
	{
		$jadwals = Dokter::with('user')->get();
		return view('admin.tambah-jadwal', compact('jadwals'));
	}

	protected function validator(array $data, $isUpdate = false)
	{
		Log::info('Validating data: ', $data);

		$rules = [
			'hari' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
			'jam_mulai' => ['required', 'date_format:H:i'],
			'jam_selesai' => ['required', 'date_format:H:i'],
		];

		if (!$isUpdate) {
			// Aturan tambahan untuk create
			$rules['doctor_id'] = ['required', 'integer', 'unique:jadwal_dokter,doctor_id'];
		}

		Log::info('Validation rules: ', $rules);

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			Log::error('Validation failed: ', $validator->errors()->toArray());
		}

		return $validator;
	}

	public function addJadwal(Request $request)
	{
		// Lakukan validasi input sesuai kebutuhan

		JadwalDokter::create([
			'doctor_id' => $request->input('doctor_id'),
			'hari' => $request->input('hari'),
			'jam_mulai' => $request->input('jam_mulai'),
            'jam_selesai' => $request->input('jam_selesai'),

		]);


		return redirect()->route('admin.dashboard-jadwal')->with('success', 'jadwal dokter berhasil ditambahkan');
	}

	public function showEditJadwalForm($jadwalId)
	{
		// Temukan jadwal berdasarkan ID dan ambil relasi dokter serta user
		$jadwal = JadwalDokter::with('doctor.user')->findOrFail($jadwalId);

		// Log error
		Log::info('Jadwal retrieved: ', ['jadwal' => $jadwal]);

		// Return view dengan data jadwal
		return view('admin.edit-jadwal', compact('jadwal'));
	}

	public function updateJadwal(Request $request, $jadwalId)
	{
		// Temukan jadwal berdasarkan ID
		$jadwal = JadwalDokter::findOrFail($jadwalId);
		Log::info('Updating jadwal ID: ' . $jadwalId);

		// Log request data
		Log::info('Request data: ', $request->all());

		try {
			// Validasi data tanpa user_id karena tidak perlu diubah
			$this->validator($request->all(), true)->validate();
			Log::info('Validation passed');

			// Ambil data yang diinginkan untuk diupdate
			$data = $request->only(['hari', 'jam_mulai', 'jam_selesai']);
			Log::info('Data to be updated: ', $data);

			// Update data jadwal
			$jadwal->update($data);
			Log::info('Jadwal updated successfully');

			// Redirect ke halaman dashboard dengan pesan sukses
			return redirect()->route('admin.dashboard-jadwal')->with('success', 'Jadwal dokter berhasil diperbarui');
		} catch (\Exception $e) {
			Log::error('Update failed: ' . $e->getMessage());
			return redirect()->route('admin.edit-jadwal', $jadwalId)->with('error', 'Terjadi kesalahan saat memperbarui jadwal.');
		}
	}

	public function deleteJadwal($jadwalId)
	{
		$jadwal = JadwalDokter::findOrFail($jadwalId);
		$jadwal->delete();

		return redirect()->route('admin.dashboard-jadwal')->with('success', 'Data pasien berhasil dihapus');
	}
}