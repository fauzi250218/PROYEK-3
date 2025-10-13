<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;

// 🔹 Route utama diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🔹 Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 ADMIN ROUTES
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Redirect /admin → /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // CRUD Guru
        Route::resource('guru', GuruController::class);

        // CRUD Murid
        Route::resource('murid', MuridController::class);

        // CRUD Kelas
        Route::resource('kelas', KelasController::class);

        // ✅ Kelola Murid di Kelas
        Route::get('kelas/{id}/kelola-murid', [KelasController::class, 'kelolaMurid'])
            ->name('kelas.kelolaMurid');
        Route::post('kelas/{id}/tambah-murid', [KelasController::class, 'tambahMurid'])
            ->name('kelas.tambahMurid');
        Route::delete('kelas/{kelas_id}/hapus-murid/{murid_id}', [KelasController::class, 'hapusMurid'])
            ->name('kelas.hapusMurid');

        // ✅ Tambahan Route untuk Jadwal Pelajaran (FullCalendar)
        Route::resource('jadwal', JadwalController::class)->except(['show']);
        Route::get('jadwal/get', [JadwalController::class, 'getJadwal'])->name('jadwal.get'); // untuk API ke kalender
        // ✅ API tambahan untuk ambil jadwal berdasarkan tanggal
        Route::get('jadwal/hari/{tanggal}', [JadwalController::class, 'getByTanggal'])->name('jadwal.hari');
    });

// 🔹 GURU ROUTES
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    });
