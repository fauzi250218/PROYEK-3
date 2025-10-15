<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Guru\KelasBinaanController;
use App\Http\Controllers\Guru\KelasAjaranController;
use App\Http\Controllers\Guru\NilaiController as GuruNilai;
use App\Http\Controllers\Guru\PembayaranController as GuruPembayaran;
use App\Http\Controllers\Guru\PerkembanganController as GuruPerkembangan;

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

        // Kelola Murid di Kelas
        Route::get('kelas/{id}/kelola-murid', [KelasController::class, 'kelolaMurid'])
            ->name('kelas.kelolaMurid');
        Route::post('kelas/{id}/tambah-murid', [KelasController::class, 'tambahMurid'])
            ->name('kelas.tambahMurid');
        Route::delete('kelas/{kelas_id}/hapus-murid/{murid_id}', [KelasController::class, 'hapusMurid'])
            ->name('kelas.hapusMurid');

        // Tambahan Route untuk Jadwal Pelajaran (FullCalendar)
        Route::resource('jadwal', JadwalController::class)->except(['show']);
        Route::get('jadwal/get', [JadwalController::class, 'getJadwal'])->name('jadwal.get'); // untuk API ke kalender
        // API tambahan untuk ambil jadwal berdasarkan tanggal
        Route::get('jadwal/hari/{tanggal}', [JadwalController::class, 'getByTanggal'])->name('jadwal.hari');
    });

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        // Dashboard Guru
        Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])
            ->name('dashboard');

        // 🔹 MANAJEMEN KELAS
        Route::prefix('kelas')->name('kelas.')->group(function () {

            // 🔹 KELAS BINAAN
            Route::prefix('binaan')->name('binaan.')->group(function () {
                Route::get('/', [KelasBinaanController::class, 'index'])
                    ->name('index');

                // Semua route berdasarkan ID kelas
                Route::get('/{id}/data-siswa', [KelasBinaanController::class, 'dataSiswa'])
                    ->whereNumber('id')->name('dataSiswa');
                Route::get('/{id}/perkembangan', [KelasBinaanController::class, 'perkembangan'])
                    ->whereNumber('id')->name('perkembangan');
                Route::get('/{id}/kehadiran', [KelasBinaanController::class, 'kehadiran'])
                    ->whereNumber('id')->name('kehadiran');
                Route::get('/{id}/catatan', [KelasBinaanController::class, 'catatan'])
                    ->whereNumber('id')->name('catatan');
                Route::get('/{id}/laporan', [KelasBinaanController::class, 'laporan'])
                    ->whereNumber('id')->name('laporan');
            });

            // 🔹 KELAS AJARAN
            Route::prefix('ajaran')->name('ajaran.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Guru\KelasAjaranController::class, 'index'])
                    ->name('index');
            });
        });

        // 🔹 MENU TAMBAHAN
        Route::get('/nilai', [\App\Http\Controllers\Guru\NilaiController::class, 'index'])
            ->name('nilai.index');
        Route::get('/pembayaran', [\App\Http\Controllers\Guru\PembayaranController::class, 'index'])
            ->name('pembayaran.index');
        Route::get('/perkembangan', [\App\Http\Controllers\Guru\PerkembanganController::class, 'index'])
            ->name('perkembangan.index');
    });
