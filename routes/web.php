<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Index route
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk Pasien
Route::get('pasien/dashboardkeluhan', [KonsultasiController::class, 'dashboardKeluhan'])->name('pasien.dashboard-keluhan')->middleware('auth');
Route::get('pasien/dashboard', [KonsultasiController::class, 'dashboard'])->name('pasien.dashboard')->middleware('auth');
Route::post('pasien/tambahkeluhan', [KonsultasiController::class, 'tambahKeluhan'])->name('tambahKeluhan')->middleware('auth');
Route::post('pasien/tambahreview', [ReviewController::class, 'tambahReview'])->name('tambahReview')->middleware('auth');
Route::get('/pasien/konsultasi/{id}/pdf', [KonsultasiController::class, 'generateDocument'])->name('konsultasi.pdf');
Route::get('/review/{konsultasiId}/create', [ReviewController::class, 'create'])->name('review.create');
Route::post('/review', [ReviewController::class, 'tambahReview'])->name('review.store');


// Route untuk Admin
Route::get('admin/home', [AdminController::class, 'home'])->name('admin.home')->middleware('auth');
Route::get('admin/dashboard-laporan', [HomeController::class, 'dashboard_laporan'])->name('admin.dashboard-laporan')->middleware('auth');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');

Route::get('admin/dashboard-dokter', [DokterController::class, 'dashboardDokter'])->name('admin.dashboard-dokter')->middleware('auth');

Route::get('admin/dashboard/adddokter', [DokterController::class, 'showAddDokterForm'])->name('admin.tambah-dokter')->middleware('auth');
Route::post('admin/dashboard/adddokter', [DokterController::class, 'addDokter'])->name('addDokter')->middleware('auth');

Route::delete('admin/dashboard/deletedokter/{doctor_id}', [DokterController::class, 'deleteDokter'])->name('deleteDokter')->middleware('auth');

Route::get('admin/dashboard/editdokter/{doctor_id}', [DokterController::class, 'showEditDokterForm'])->name('admin.edit-dokter')->middleware('auth');
Route::put('admin/dashboard/editdokters/{doctor_id}', [DokterController::class, 'updateDokter'])->name('updateDokters')->middleware('auth');

Route::get('admin/dashboard/addpengguna', [AdminController::class, 'showAddPenggunaForm'])->name('admin.tambah-pengguna')->middleware('auth');

Route::post('admin/dashboard/addpengguna', [AdminController::class, 'addPengguna'])->name('addPengguna')->middleware('auth');
Route::get('admin/dashboard/pasien', [PasienController::class, 'getDataPasien'])->name('admin.pasien-dashboard')->middleware('auth');
Route::get('admin/dashboard/addpasien', [PasienController::class, 'showAddPasienForm'])->name('admin.tambah-pasien')->middleware('auth');
Route::post('admin/dashboard/addpasien', [PasienController::class, 'addPasien'])->name('addPasien')->middleware('auth');

Route::get('admin/dashboard/editpengguna/{id}', [AdminController::class, 'showEditPenggunaForm'])->name('admin.edit-pengguna')->middleware('auth');
Route::put('admin/dashboard/editpengguna/{id}', [AdminController::class, 'updatePengguna'])->name('updatePengguna')->middleware('auth');

Route::delete('admin/dashboard/deletepengguna/{id}', [AdminController::class, 'deletePengguna'])->name('deletePengguna')->middleware('auth');

Route::get('admin/dashboard/editpasien/{id}', [PasienController::class, 'showEditPasienForm'])->name('admin.edit-pasien')->middleware('auth');
Route::put('admin/dashboard/editpasien/{id}', [PasienController::class, 'updatePasien'])->name('updatePasien')->middleware('auth');
Route::get('admin/dashboard/editjadwal/{id}', [JadwalController::class, 'showEditjadwalForm'])->name('admin.edit-jadwal')->middleware('auth');
Route::put('admin/dashboard/editjadwal/{id}', [JadwalController::class, 'updatejadwal'])->name('updateJadwal')->middleware('auth');
Route::delete('admin/dashboard/deletejadwal/{id}', [JadwalController::class, 'deletejadwal'])->name('deleteJadwal')->middleware('auth');
Route::delete('admin/dashboard/deletepasien/{id}', [PasienController::class, 'deletePasien'])->name('deletePasien')->middleware('auth');
Route::get('admin/dashboard/jadwaldokter', [JadwalController::class, 'getDataJadwalDokter'])->name('admin.dashboard-jadwal')->middleware('auth');
Route::get('admin/dashboard/addjadwal', [JadwalController::class, 'showAddJadwalForm'])->name('admin.tambah-jadwal')->middleware('auth');
Route::post('admin/dashboard/addjadwal', [JadwalController::class, 'addJadwal'])->name('addJadwal')->middleware('auth');
Route::get('admin/dashboard/keluhanpasien', [AdminController::class, 'getKeluhan'])->name('admin.dashboard-keluhan')->middleware('auth');
Route::get('admin/dashboard/addkeluhan', [AdminController::class, 'showTambahKeluhanForm'])->name('admin.tambah-keluhan')->middleware('auth');
Route::post('admin/dashboard/addkeluhan', [AdminController::class, 'addKeluhan'])->name('addKeluhan')->middleware('auth');
Route::get('/admin/dashboard/editkeluhan/{konsultasi_id}', [KonsultasiController::class, 'formEditKeluhan'])->name('admin.edit-keluhan')->middleware('auth');
Route::put('/admin/dashboard/editkeluhan/{konsultasi_id}', [KonsultasiController::class, 'updateKeluhan'])->name('updateKeluhan')->middleware('auth');
Route::delete('admin/dashboard/deletekeluhan/{id}', [KonsultasiController::class, 'deleteKeluhan'])->name('deleteKeluhan')->middleware('auth');
// Rute untuk persetujuan dan penolakan pengguna
Route::get('/admin/approve-user/{id}', [AdminController::class, 'approveUser'])->name('admin.approveUser');
Route::get('/admin/reject-user/{id}', [AdminController::class, 'rejectUser'])->name('admin.rejectUser');


// Route untuk Dokter
Route::get('dokter/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard')->middleware('auth');
Route::get('dokter/respon/{konsultasi_id}', [DokterController::class, 'respon'])->name('dokter.respon')->middleware('auth');
Route::put('dokter/respon/{konsultasi_id}', [DokterController::class, 'responKeluhan'])->name('respon')->middleware('auth');
Route::get('dokter/dashboard-laporan', [HomeController::class, 'dashboard_laporan_dokter'])->name('laporan.dashboard-laporan')->middleware('auth');
Route::get('dokter/profile', [DokterController::class, 'profile'])->name('dokter.profile')->middleware('auth');
Route::put('dokter/profile/edit', [DokterController::class, 'updateProfile'])->name('dokter.profile.edit')->middleware('auth');
Route::post('dokter/profile/reset-password', [DokterController::class, 'updatePassword'])->name('dokter.reset-password');

//chat
Route::get('/chat/{konsultasiId}', [App\Http\Controllers\ChatController::class, 'chat'])->name('chat');;
Route::get('/chat/room/{room}', [App\Http\Controllers\ChatController::class, 'room'])->name('chat.room');
Route::get('/chat/get/{room}', [App\Http\Controllers\ChatController::class, 'getChat'])->name('chat.get');
Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendChat'])->name('chat.send');
Route::get('/pasien/chat/{id}', [App\Http\Controllers\ChatController::class, 'chat'])->name('pasien.chat');
Route::post('/konsultasi/terima/{id}', [KonsultasiController::class, 'terimaKonsultasi'])->name('konsultasi.terima');