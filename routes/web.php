<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\AtkRekapController;
use App\Http\Controllers\AtkMasukController;
use App\Http\Controllers\AtkKeluarController;
use App\Http\Controllers\AtkController;
use App\Http\Controllers\PenagihanLapanganController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect('/login'));

Auth::routes(['register' => false]);

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| USER MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::resource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| ROLE MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:manage roles'])->group(function () {
    Route::resource('roles', RoleController::class);
});

/*
|--------------------------------------------------------------------------
| ACTIVITY LOGS
|--------------------------------------------------------------------------
*/
Route::get('/logs', [ActivityController::class, 'index'])
    ->name('logs.index')
    ->middleware(['auth', 'permission:view logs']);

/*
|--------------------------------------------------------------------------
| PEGAWAI MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // CRUD Pegawai utama
    Route::resource('pegawai', PegawaiController::class);

    // Profil Pegawai
    Route::get('/pegawai/{pegawai}/profil', [PegawaiController::class, 'profil'])
        ->name('pegawai.profil');

    /*
    |--------------------------------------------------------------------------
    | AJAX Riwayat Jabatan (langsung dari profil pegawai)
    | Contoh: POST /pegawai/5/ajax-riwayat
    |--------------------------------------------------------------------------
    */
    Route::prefix('pegawai/{pegawai}/ajax-riwayat')->group(function () {
        Route::post('/', [PegawaiController::class, 'storeRiwayat'])->name('pegawai.ajax.riwayat.store');
        Route::put('/{riwayat}', [PegawaiController::class, 'updateRiwayat'])->name('pegawai.ajax.riwayat.update');
        Route::delete('/{riwayat}', [PegawaiController::class, 'destroyRiwayat'])->name('pegawai.ajax.riwayat.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | CRUD Manual Riwayat Jabatan (melalui RiwayatJabatanController)
    | Contoh: GET /pegawai/5/riwayat/create
    |--------------------------------------------------------------------------
    */
    Route::prefix('pegawai/{pegawai}')->group(function () {
        Route::get('riwayat', [RiwayatJabatanController::class, 'index'])->name('riwayat.index');
        Route::get('riwayat/create', [RiwayatJabatanController::class, 'create'])->name('riwayat.create');
        Route::post('riwayat', [RiwayatJabatanController::class, 'store'])->name('riwayat.store');
        Route::get('riwayat/{id}/edit', [RiwayatJabatanController::class, 'edit'])->name('riwayat.edit');
        Route::put('riwayat/{id}', [RiwayatJabatanController::class, 'update'])->name('riwayat.update');
        Route::delete('riwayat/{id}', [RiwayatJabatanController::class, 'destroy'])->name('riwayat.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| MASTER DATA — Bagian dan Jabatan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('bagian', BagianController::class);
    Route::resource('jabatan', JabatanController::class);
});

Route::middleware(['auth', 'permission:manage atk'])->group(function () {

    Route::resource('atk', AtkController::class);
    Route::resource('atk-masuk', AtkMasukController::class);
    Route::resource('atk-keluar', AtkKeluarController::class);

    // ✅ Laporan & Export Persediaan ATK
    Route::get('rekap-atk', [AtkRekapController::class, 'index'])->name('atk.rekap');
    Route::get('rekap-atk/export-excel', [AtkRekapController::class, 'exportExcel'])->name('atk.rekap.export');
    Route::get('rekap-atk/export-pdf', [AtkRekapController::class, 'exportPdf'])->name('atk.rekap.pdf');
});




Route::middleware(['auth'])->group(function () {
    // Input penagihan oleh staf
    Route::get('/penagihan/create', [PenagihanLapanganController::class, 'create'])->name('penagihan.create');
    Route::post('/penagihan',        [PenagihanLapanganController::class, 'store'])->name('penagihan.store');

    // Laporan penagihan (per staf)
    Route::get('/penagihan/export-excel', [PenagihanLapanganController::class, 'exportExcel'])
        ->name('penagihan.export.excel');

    Route::get('/penagihan/export-pdf', [PenagihanLapanganController::class, 'exportPdf'])
        ->name('penagihan.export.pdf');

    Route::post('/penagihan/{id}/follow-up', [PenagihanLapanganController::class, 'followUpStore'])
        ->name('penagihan.followup.store')
        ->middleware('auth');
    Route::get(
        '/penagihan-laporan',
        [PenagihanLapanganController::class, 'laporan']
    )->name('penagihan.laporan');

    Route::post(
        '/penagihan/{id}/followup',
        [PenagihanLapanganController::class, 'followupStore']
    )->name('penagihan.followup.store');
});

use App\Http\Controllers\KreditLalaiHarianController;

Route::middleware(['auth'])->group(function () {
    Route::get('/kredit-lalai',              [KreditLalaiHarianController::class, 'index'])->name('kredit-lalai.index');
    Route::get('/kredit-lalai/create',       [KreditLalaiHarianController::class, 'create'])->name('kredit-lalai.create');
    Route::post('/kredit-lalai',             [KreditLalaiHarianController::class, 'store'])->name('kredit-lalai.store');

    Route::get('/kredit-lalai/export-excel', [KreditLalaiHarianController::class, 'exportExcel'])->name('kredit-lalai.export.excel');
    Route::get('/kredit-lalai/export-pdf',   [KreditLalaiHarianController::class, 'exportPdf'])->name('kredit-lalai.export.pdf');
});
