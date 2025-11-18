<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\AbsenLocationController;
use App\Http\Controllers\Frontend\AbsensiController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DocOutController;
use App\Http\Controllers\Frontend\EkspedisiController;
use App\Http\Controllers\Frontend\KandangController;
use App\Http\Controllers\Frontend\LokasiController;
use App\Http\Controllers\Frontend\MesinController;
use App\Http\Controllers\Frontend\PatroliController;
use App\Http\Controllers\Frontend\PatroliKandangController;
use App\Http\Controllers\Frontend\SatpamController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/activate/{token}', [RegisteredUserController::class, 'activate'])->name('activate');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('satpam', SatpamController::class);
    Route::get('/satpam_table', [SatpamController::class, 'satpam_table'])->name('satpam.table');
    Route::post('/activate', [SatpamController::class, 'activate'])->name('satpam.activate');

    Route::resource('lokasi', LokasiController::class);
    Route::get('lokasi_table', [LokasiController::class, 'lokasi_table'])->name('lokasi.table');
    Route::post('/lokasi_activate', [LokasiController::class, 'activate'])->name('lokasi.activate');
    Route::get('/download_qrcode/{id}',[LokasiController::class, 'download_qrcode']);
    Route::resource('absen_location', AbsenLocationController::class);
    Route::get('/absen_location_table', [AbsenLocationController::class, 'absen_location_table'])->name('absen.location.table');

    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi_table', [AbsensiController::class, 'absensi_table'])->name('absensi.table');
    Route::get('/absensi_xls', [AbsensiController::class, 'exportXls'])->name('absensi.export.xls');
    Route::get('/absensi_pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.export.pdf');

    Route::resource('patroli', PatroliController::class);
    Route::get('/patroli_table', [PatroliController::class, 'patroli_table'])->name('patroli.table');
    Route::get('/patroli_xls', [PatroliController::class, 'exportXls'])->name('patroli.export.xls');
    Route::get('/patroli_pdf', [PatroliController::class, 'exportPdf'])->name('patroli.export.pdf');

    Route::resource('user', UserController::class);
    Route::get('/user_table', [UserController::class, 'user_table'])->name('user.table');

    Route::resource('kandang', KandangController::class);
    Route::get('/kandang_table', [KandangController::class, 'kandang_table'])->name('kandang.table');


    Route::resource('mesin', MesinController::class);
    Route::get('/mesin_table', [MesinController::class, 'mesin_table'])->name('mesin.table');

    Route::resource('ekspedisi', EkspedisiController::class);
    Route::get('/ekspedisi_table', [EkspedisiController::class, 'ekspedisi_table'])->name('ekspedisi.table');

    Route::resource('patroli_kandang', PatroliKandangController::class);
    Route::get('/kandang_suhu_table', [PatroliKandangController::class, 'kandang_suhu_table'])->name('kandang.suhu.table');
    Route::get('/kandang_kipas_table', [PatroliKandangController::class, 'kandang_kipas_table'])->name('kandang.kipas.table');
    Route::get('/kandang_alarm_table', [PatroliKandangController::class, 'kandang_alarm_table'])->name('kandang.alarm.table');
    Route::get('/kandang_lampu_table', [PatroliKandangController::class, 'kandang_lampu_table'])->name('kandang.lampu.table');

    Route::get('/patroli_kandang_xls', [PatroliKandangController::class, 'exportXls'])->name('patroli.kandang.export.xls');
    Route::get('/patroli_kandang_pdf', [PatroliKandangController::class, 'exportPdf'])->name('patroli.kandang.export.pdf');

    Route::resource('doc_out', DocOutController::class);
    Route::get('/doc_out_table', [DocOutController::class, 'doc_out_table'])->name('doc.out.table');
    Route::get('/doc_export_xls', [DocOutController::class, 'export_xls'])->name('doc.export.xls');
    Route::get('/doc_export_pdf', [DocOutController::class, 'export_pdf'])->name('doc.export.pdf');
    
});

require __DIR__ . '/auth.php';
