<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\KelasBinaanController;
use App\Http\Controllers\Guru\KelasAjaranController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\PembayaranController;
use App\Http\Controllers\Guru\PerkembanganController;
use App\Http\Controllers\Guru\ObrolanController;

// ==================================================
// ================ ROUTE UTAMA =====================
// ==================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ==================================================
// ================ AUTH ROUTE ======================
// ==================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================================================
// ================= ADMIN AREA =====================
// ==================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Data Guru, Murid, dan Kelas
        Route::resource('guru', GuruController::class);
        Route::resource('murid', MuridController::class);
        Route::resource('kelas', KelasController::class);

        // Kelola murid dalam kelas
        Route::get('kelas/{id}/kelola-murid', [KelasController::class, 'kelolaMurid'])
            ->whereNumber('id')->name('kelas.kelolaMurid');
        Route::post('kelas/{id}/tambah-murid', [KelasController::class, 'tambahMurid'])
            ->whereNumber('id')->name('kelas.tambahMurid');
        Route::delete('kelas/{kelas_id}/hapus-murid/{murid_id}', [KelasController::class, 'hapusMurid'])
            ->whereNumber('kelas_id')->whereNumber('murid_id')->name('kelas.hapusMurid');

        // ==================================================
        // ============== JADWAL (AJAX & CRUD) ===============
        // ==================================================
        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', [JadwalController::class, 'index'])->name('index');
            Route::get('/get', [JadwalController::class, 'getJadwal'])->name('get');
            Route::get('/hari/{tanggal}', [JadwalController::class, 'getByTanggal'])->name('hari');
            Route::get('/{id}', [JadwalController::class, 'show'])->name('show');
            Route::post('/', [JadwalController::class, 'store'])->name('store');
            Route::put('/{id}', [JadwalController::class, 'update'])->name('update');
            Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('destroy');
            Route::delete('/hapus-semester/{mata_pelajaran}', [JadwalController::class, 'deleteSemester'])->name('deleteSemester');
        });
    });

// ==================================================
// ================== GURU AREA ======================
// ==================================================
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        // Dashboard Guru
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

        // =======================
        // MANAJEMEN KELAS
        // =======================
        Route::prefix('kelas')->name('kelas.')->group(function () {
            // ---------- KELAS BINAAN ----------
            Route::prefix('binaan')->name('binaan.')->group(function () {
                Route::get('/', [KelasBinaanController::class, 'index'])->name('index');
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

            // ---------- KELAS AJARAN ----------
            Route::get('/ajaran', [KelasAjaranController::class, 'index'])->name('ajaran.index');
        });

        // =======================
        // MANAJEMEN NILAI
        // =======================
        Route::get('/nilai', fn() => redirect()->route('guru.nilai.semuaKelas'));

        Route::prefix('nilai')->name('nilai.')->group(function () {
            Route::get('/semua-kelas', [NilaiController::class, 'semuaKelas'])->name('semuaKelas');
            Route::get('/kelas/{id}', [NilaiController::class, 'index'])->name('index');
            Route::get('/murid/{id}', [NilaiController::class, 'detail'])->name('detail');
            Route::get('/murid/{id}/create', [NilaiController::class, 'create'])->name('create');
            Route::post('/murid/{id}', [NilaiController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [NilaiController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [NilaiController::class, 'update'])->name('update');
            Route::delete('/hapus/{id}', [NilaiController::class, 'destroy'])->name('destroy');
        });

        // =======================
        // MENU TAMBAHAN GURU
        // =======================
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/perkembangan', [PerkembanganController::class, 'index'])->name('perkembangan.index');
        Route::get('/obrolan', [ObrolanController::class, 'index'])->name('obrolan.index');
    });
