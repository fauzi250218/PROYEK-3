<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MuridController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\KontakController;

// =====================
// Login dan Registrasi
// =====================
Route::post('/register-murid', [MuridController::class, 'register']);
Route::post('/login-murid', [MuridController::class, 'login']);

// =====================
// Login With Google
// =====================
Route::post('/murids/google-login', [MuridController::class, 'googleLogin']);

// =====================
// Registrasi With Google dengan Validasi NIS
// =====================
Route::post('/murids/google-register-validate', [MuridController::class, 'googleRegisterValidate']);

// =====================
// Profil Murid
// =====================

// Ambil data berdasarkan email
Route::get('/murid/email/{email}', [MuridController::class, 'getByEmail']);

// Ambil data dan update berdasarkan ID
Route::get('/murid/{id}', [MuridController::class, 'show']);
Route::put('/murid/{id}', [MuridController::class, 'update']);

// Update murid berdasarkan email (versi baru)
Route::put('/update-murid', [MuridController::class, 'updateByEmail']);
Route::post('/update-murid-foto', [MuridController::class, 'updateByEmail']);

// =====================
// Data Kelas
// =====================
Route::get('/kelas', [KelasController::class, 'index']);

// =====================
// Jadwal Kelas
// =====================
Route::get('/jadwal', [JadwalController::class, 'index']);
Route::get('/jadwal/tanggal', [JadwalController::class, 'getByDate']);

// =====================
// Ubah Kata Sandi
// =====================
Route::post('/murid/change-password', [MuridController::class, 'changePassword']);

// =====================
// Notifikasi
// =====================
Route::get('murid/{email}/notifications', [MuridController::class, 'getNotifications']);
Route::put('/notification/{id}/read', [MuridController::class, 'markNotificationAsRead']);

// =====================
// Obrolan / chat
// =====================
Route::post('/chat/open-room', [ChatController::class, 'openRoom']);
Route::get('/chat/messages/{id}', [ChatController::class, 'getMessages']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);

// Ambil semua chat room untuk user tertentu
Route::get('/chat/rooms', [ChatController::class, 'getRooms']);

// Kontak
Route::get('/kontak/{email}', [KontakController::class, 'getKontak']);

// =====================
// Nilai
// =====================
Route::get('/nilai/murid/{murid_id}', [NilaiController::class, 'getNilaiByMurid']);
