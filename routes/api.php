<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MuridController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\KontakController;
use App\Http\Controllers\Api\ModulController;

// =====================
// Login dan Registrasi
// =====================
Route::post('/register-murid', [MuridController::class, 'register']);
Route::post('/login-murid', [MuridController::class, 'login']);

// =====================
// Login With Google
// =====================
Route::post('/murids/google-login', [MuridController::class, 'googleLogin']);
Route::post('/murids/google-register-validate', [MuridController::class, 'googleRegisterValidate']);

// =====================
// Profil Murid
// =====================
Route::get('/murid/email/{email}', [MuridController::class, 'getByEmail']);
Route::get('/murid/{id}', [MuridController::class, 'show']);
Route::put('/murid/{id}', [MuridController::class, 'update']);
Route::put('/update-murid', [MuridController::class, 'updateByEmail']);
Route::post('/update-murid-foto', [MuridController::class, 'updateByEmail']);

// =====================
// Data Kelas
// =====================
Route::get('/kelas', [KelasController::class, 'index']);

// =====================
// Jadwal
// =====================
Route::get('/jadwal', [JadwalController::class, 'index']);
Route::get('/jadwal/tanggal', [JadwalController::class, 'getByDate']);

// =====================
// Password
// =====================
Route::post('/murid/change-password', [MuridController::class, 'changePassword']);

// =====================
// Notifikasi
// =====================
Route::get('murid/{email}/notifications', [MuridController::class, 'getNotifications']);
Route::put('/notification/{id}/read', [MuridController::class, 'markNotificationAsRead']);

// ==================================================
// CHAT API (INI YANG KURANG)
// ==================================================
Route::prefix('chat')->group(function () {

    Route::post('/open-room', [ChatController::class, 'openRoom']);
    Route::get('/rooms', [ChatController::class, 'getRooms']);
    Route::get('/messages/{id}', [ChatController::class, 'getMessages']);
    Route::post('/send', [ChatController::class, 'sendMessage']);

    // PENTING UNTUK BADGE
    Route::post('/mark-read', [ChatController::class, 'markAsRead']);
    Route::get('/unread-count/guru', [ChatController::class, 'unreadGuru']);
});

// =====================
// Kontak
// =====================
Route::get('/kontak/{email}', [KontakController::class, 'getKontak']);

// =====================
// Nilai
// =====================
Route::get('/nilai/murid/{murid_id}', [NilaiController::class, 'getNilaiByMurid']);

// =====================
// Modul
// =====================
Route::get('/modul/mata-pelajaran/{murid_id}', [ModulController::class, 'getMataPelajaran']);
Route::get('/modul/by-mapel-jadwal/{jadwal_id}', [ModulController::class, 'getModulByMapelJadwal']);
Route::post('/modul/by-many-jadwal', [ModulController::class, 'getModulByManyJadwal']);
