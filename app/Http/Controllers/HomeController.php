<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Konsultasi;
use App\Models\Pasien;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	public function indexx()
    {
        $users = User::all();

        return view('home', compact('users'));
    }

    public function index()
	{
		$doctors = DB::table('doctors')
			->join('users', 'doctors.user_id', '=', 'users.id')
			->where('users.tipe_pengguna', 'Dokter')
			->select('users.name', 'users.email', 'users.jenis_kelamin', 'users.alamat', 'users.no_telepon', 'users.tanggal_lahir', 'doctors.doctor_id', 'doctors.spesialisasi', 'doctors.kualifikasi', 'doctors.pengalaman')
			->get();


		return view('index', compact('doctors'));
	}

	public function dashboard_laporan()
	{
        $konsultasi = DB::table('konsultasi')
		->select('konsultasi.konsultasi_id', 'konsultasi.pasien_id', 'konsultasi.doctor_id', 'konsultasi.tanggal_konsultasi', 'konsultasi.status', 'konsultasi.keluhan_pasien', 'konsultasi.balasan_dokter')
		->get();

		$sum_pasien = Pasien::Count('*');
		$total_appoiment = Konsultasi::Where('status','terjawab')->Count('*');
		$total_pesan_blm_dijawab =  Konsultasi::Where('status','belum dijawab')->Count('*');
		$total_dokter = Dokter::Count('*');

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
			// ->where('pasien.pasien_id', $pasienId)
			->get();

		// var_dump($sum_pasien);die();
		return view('admin.dashboard-laporan', compact(
			'konsultasi',
			'sum_pasien',
			'total_appoiment',
			'total_pesan_blm_dijawab',
			'total_dokter',
			'consultations'
		));
	}

	public function dashboard_laporan_dokter()
	{
        $konsultasi = DB::table('konsultasi')
		->select('konsultasi.konsultasi_id', 'konsultasi.pasien_id', 'konsultasi.doctor_id', 'konsultasi.tanggal_konsultasi', 'konsultasi.status', 'konsultasi.keluhan_pasien', 'konsultasi.balasan_dokter')
		->get();

		$sum_pasien = Pasien::Count('*');
		$total_appoiment = Konsultasi::Where('status','terjawab')->Count('*');
		$total_pesan_blm_dijawab =  Konsultasi::Where('status','belum dijawab')->Count('*');
		$total_dokter = Dokter::Count('*');

		// Mendapatkan ID pengguna yang sedang login
		$userId = Auth::id();

		// Mendapatkan pasien_id dari tabel pasien berdasarkan user_id
		$pasienId = Pasien::where('user_id', $userId)->value('pasien_id');

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
			// ->where('pasien.pasien_id', $pasienId)
			->get();

		

		// var_dump($sum_pasien);die();
		return view('laporan.dashboard-laporan', compact(
			'konsultasi',
			'sum_pasien',
			'total_appoiment',
			'total_pesan_blm_dijawab',
			'total_dokter',
			'consultations'
		));
	}
}
