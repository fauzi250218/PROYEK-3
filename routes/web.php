<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/* =======================
 | ADMIN CONTROLLERS
 ======================= */
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\PembayaranSPPController;

/* =======================
 | GURU CONTROLLERS
 ======================= */
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\KelasBinaanController;
use App\Http\Controllers\Guru\KelasAjaranController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\PerkembanganController;
use App\Http\Controllers\Guru\ObrolanController;
use App\Http\Controllers\Guru\KehadiranController;
use App\Http\Controllers\Guru\ERaportController;


/* ==================================================
 | ROUTE AWAL
 ================================================== */

Route::get('/', fn() => redirect()->route('login'));


/* ==================================================
 | AUTH
 ================================================== */
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/* ==================================================
 | ADMIN AREA
 ================================================== */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /* Dashboard */
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /* Master Data */
        Route::resource('guru',  GuruController::class);
        Route::resource('murid', MuridController::class);
        Route::resource('kelas', KelasController::class);

        /* =============================
         | KELOLA MURID DI KELAS
         ============================= */
        Route::get('kelas/{id}/kelola-murid', [KelasController::class, 'kelolaMurid'])
            ->whereNumber('id')
            ->name('kelas.kelolaMurid');

        Route::post('kelas/{id}/tambah-murid', [KelasController::class, 'tambahMurid'])
            ->whereNumber('id')
            ->name('kelas.tambahMurid');

        Route::delete(
            'kelas/{kelas_id}/hapus-murid/{murid_id}',
            [KelasController::class, 'hapusMurid']
        )
            ->whereNumber('kelas_id')
            ->whereNumber('murid_id')
            ->name('kelas.hapusMurid');

        /* =============================
         | JADWAL PELAJARAN
         ============================= */
        Route::prefix('jadwal')->name('jadwal.')->group(function () {

            Route::get('/', [JadwalController::class, 'index'])->name('index');
            Route::get('/get', [JadwalController::class, 'getJadwal'])->name('get');

            Route::get('/hari/{tanggal}', [JadwalController::class, 'getByTanggal'])
                ->where('tanggal', '[0-9\-]+')
                ->name('hari');

            Route::post('/', [JadwalController::class, 'store'])->name('store');

            Route::put('/{id}', [JadwalController::class, 'update'])
                ->whereNumber('id')
                ->name('update');

            Route::delete('/{id}', [JadwalController::class, 'destroy'])
                ->whereNumber('id')
                ->name('destroy');

            Route::delete(
                '/hapus-semester/{mata_pelajaran}',
                [JadwalController::class, 'deleteSemester']
            )->name('deleteSemester');

            Route::get('/detail/{id}', [JadwalController::class, 'show'])
                ->whereNumber('id')
                ->name('show');
        });

        /* =============================
         | TAGIHAN & PEMBAYARAN SPP
         ============================= */
        Route::get('/pembayaran-spp', [PembayaranSPPController::class, 'index'])
            ->name('pembayaran-spp.index');

        Route::get('/pembayaran-spp/buat', [PembayaranSPPController::class, 'create'])
            ->name('pembayaran-spp.create');

        Route::post('/pembayaran-spp/simpan', [PembayaranSPPController::class, 'store'])
            ->name('pembayaran-spp.store');

        Route::post(
            '/pembayaran-spp/murid/{murid}/simpan',
            [PembayaranSPPController::class, 'storeForMurid']
        )
            ->whereNumber('murid')
            ->name('pembayaran-spp.store-murid');

        Route::get(
            '/pembayaran-spp/{id}/bayar',
            [PembayaranSPPController::class, 'bayar']
        )
            ->whereNumber('id')
            ->name('pembayaran-spp.bayar');

        /* 🔥 OPSI LOCALHOST (FORCE LUNAS) */
        Route::post(
            '/pembayaran-spp/{id}/force-lunas',
            [PembayaranSPPController::class, 'forceLunas']
        )
            ->whereNumber('id')
            ->name('pembayaran-spp.force-lunas');
    });


/* ==================================================
 | GURU AREA
 ================================================== */
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        Route::get('/dashboard', [GuruDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/jadwal/{tanggal}', [GuruDashboardController::class, 'getJadwalTanggal'])
            ->where('tanggal', '[0-9\-]+')
            ->name('jadwal.tanggal');

        Route::prefix('kelas')->name('kelas.')->group(function () {

            Route::prefix('binaan')->name('binaan.')->group(function () {

                Route::get('/', [KelasBinaanController::class, 'index'])->name('index');

                Route::get('/{id}/data-siswa', [KelasBinaanController::class, 'dataSiswa'])
                    ->whereNumber('id')
                    ->name('dataSiswa');

                Route::get('/{id}/perkembangan', [PerkembanganController::class, 'index'])
                    ->whereNumber('id')
                    ->name('perkembangan.index');

                Route::get('/{id}/perkembangan/{murid}', [PerkembanganController::class, 'show'])
                    ->whereNumber(['id', 'murid'])
                    ->name('perkembangan.show');

                Route::post(
                    '/{id}/perkembangan/{murid}/catatan',
                    [PerkembanganController::class, 'storeCatatan']
                )
                    ->whereNumber(['id', 'murid'])
                    ->name('perkembangan.storeCatatan');

                Route::delete(
                    '/{id}/perkembangan/catatan/{catatan}',
                    [PerkembanganController::class, 'destroyCatatan']
                )
                    ->whereNumber(['id', 'catatan'])
                    ->name('perkembangan.destroyCatatan');

                Route::get('/{id}/kehadiran', [KelasBinaanController::class, 'kehadiran'])
                    ->whereNumber('id')
                    ->name('kehadiran');

                Route::get('/{id}/laporan', [KelasBinaanController::class, 'laporan'])
                    ->whereNumber('id')
                    ->name('laporan');

                Route::get('/{id}/eraport', [ERaportController::class, 'index'])
                    ->whereNumber('id')
                    ->name('eraport.index');

                Route::get('/eraport/{murid_id}/show', [ERaportController::class, 'show'])
                    ->whereNumber('murid_id')
                    ->name('eraport.show');

                Route::get('/eraport/{murid_id}/download', [ERaportController::class, 'download'])
                    ->whereNumber('murid_id')
                    ->name('eraport.download');
            });

            Route::prefix('ajaran')->name('ajaran.')->group(function () {

                Route::get('/', [KelasAjaranController::class, 'index'])->name('index');

                Route::get('/detail-kelas/{id}', [KelasAjaranController::class, 'detail'])
                    ->whereNumber('id')
                    ->name('detail');

                Route::post('/tambah-sesi', [KelasAjaranController::class, 'storeSesi'])
                    ->name('sesi.store');

                Route::post('/sesi/{kelas_id}/sync', [KelasAjaranController::class, 'syncSesi'])
                    ->whereNumber('kelas_id')
                    ->name('sesi.sync');

                Route::get('/sesi/{id}/edit', [KelasAjaranController::class, 'editSesi'])
                    ->whereNumber('id')
                    ->name('sesi.edit');

                Route::put('/sesi/{id}/update', [KelasAjaranController::class, 'updateSesi'])
                    ->whereNumber('id')
                    ->name('sesi.update');

                Route::get('/sesi/{id}/hapus', [KelasAjaranController::class, 'deleteSesi'])
                    ->whereNumber('id')
                    ->name('sesi.delete');

                Route::post('/upload-modul', [KelasAjaranController::class, 'uploadModul'])
                    ->name('upload-modul');

                Route::get('/modul/{id}/edit', [KelasAjaranController::class, 'editModul'])
                    ->whereNumber('id')
                    ->name('modul.edit');

                Route::put('/modul/{id}', [KelasAjaranController::class, 'updateModul'])
                    ->whereNumber('id')
                    ->name('modul.update');

                Route::delete('/modul/{id}/delete', [KelasAjaranController::class, 'deleteModul'])
                    ->whereNumber('id')
                    ->name('modul.delete');

                Route::get('/modul/{id}/preview', [KelasAjaranController::class, 'previewModul'])
                    ->whereNumber('id')
                    ->name('modul.preview');

                Route::get('/sesi/{id}/presensi', [KehadiranController::class, 'index'])
                    ->whereNumber('id')
                    ->name('kehadiran.presensi');

                Route::post('/sesi/{id}/presensi/simpan', [KehadiranController::class, 'store'])
                    ->whereNumber('id')
                    ->name('kehadiran.simpan');
            });
        });

        Route::prefix('nilai')->name('nilai.')->group(function () {

            Route::get('/semua-kelas', [NilaiController::class, 'semuaKelas'])
                ->name('semuaKelas');

            Route::get('/kelas/{id}', [NilaiController::class, 'index'])
                ->whereNumber('id')
                ->name('index');

            Route::get('/kelas/{kelasId}/mapel/{mapel}', [NilaiController::class, 'muridPerMapel'])
                ->name('mapel.murid');

            Route::get('/murid/{id}/{mapel}', [NilaiController::class, 'detail'])
                ->name('detail');

            Route::post('/murid/{id}', [NilaiController::class, 'store'])
                ->name('store');

            Route::put('/update/{id}', [NilaiController::class, 'update'])
                ->name('update');

            Route::delete('/hapus/{id}', [NilaiController::class, 'destroy'])
                ->name('destroy');

            Route::post('/murid/{id}/inline', [NilaiController::class, 'storeInline'])
                ->name('storeInline');

            Route::put('/update-inline/{id}', [NilaiController::class, 'updateInline'])
                ->name('updateInline');
        });

        Route::get('/obrolan', [ObrolanController::class, 'index'])
            ->name('obrolan.index');

        Route::get('/obrolan/{murid}', [ObrolanController::class, 'chat'])
            ->whereNumber('murid')
            ->name('obrolan.chat');
    });


/* ==================================================
 | MIDTRANS CALLBACK
 ================================================== */
Route::post('/midtrans/callback', [PembayaranSPPController::class, 'callback'])
    ->name('midtrans.callback');
