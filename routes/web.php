<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\AbsenLocationController;
use App\Http\Controllers\Frontend\AbsensiController;
use App\Http\Controllers\Frontend\BroadcastController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DocOutController;
use App\Http\Controllers\Frontend\EkspedisiController;
use App\Http\Controllers\Frontend\EmergencyListController;
use App\Http\Controllers\Frontend\JamShiftController;
use App\Http\Controllers\Frontend\KandangController;
use App\Http\Controllers\Frontend\LaporanKandangController;
use App\Http\Controllers\Frontend\LaporanSituasiController;
use App\Http\Controllers\Frontend\LokasiController;
use App\Http\Controllers\Frontend\MesinController;
use App\Http\Controllers\Frontend\PatroliController;
use App\Http\Controllers\Frontend\PatroliKandangController;
use App\Http\Controllers\Frontend\PerusahaanController;
use App\Http\Controllers\Frontend\SatpamController;
use App\Http\Controllers\Frontend\TamuController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\ProfileController;
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
Route::get('/copy_link_tamu/{uuid}', [TamuController::class, 'copy_link_tamu']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/tampilkan_absensi_satpam', [DashboardController::class, 'tampilkan_absensi_satpam'])->name('tampilkan.absensi.satpam');
    Route::post('/tampilkan_patroli_satpam', [DashboardController::class, 'tampilkan_patroli_satpam'])->name('tampilkan.patroli.satpam');


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

    Route::resource('kandang', KandangController::class)->middleware('checkCom');
    Route::get('/kandang_table', [KandangController::class, 'kandang_table'])->name('kandang.table')->middleware('checkCom');;


    Route::resource('mesin', MesinController::class)->middleware('checkCom');;
    Route::get('/mesin_table', [MesinController::class, 'mesin_table'])->name('mesin.table')->middleware('checkCom');;

    Route::resource('ekspedisi', EkspedisiController::class)->middleware('checkCom');
    Route::get('/ekspedisi_table', [EkspedisiController::class, 'ekspedisi_table'])->name('ekspedisi.table')->middleware('checkCom');;

    Route::resource('patroli_kandang', PatroliKandangController::class)->middleware('checkCom');
    Route::get('/kandang_suhu_table', [PatroliKandangController::class, 'kandang_suhu_table'])->name('kandang.suhu.table')->middleware('checkCom');
    Route::get('/kandang_kipas_table', [PatroliKandangController::class, 'kandang_kipas_table'])->name('kandang.kipas.table')->middleware('checkCom');
    Route::get('/kandang_alarm_table', [PatroliKandangController::class, 'kandang_alarm_table'])->name('kandang.alarm.table')->middleware('checkCom');
    Route::get('/kandang_lampu_table', [PatroliKandangController::class, 'kandang_lampu_table'])->name('kandang.lampu.table')->middleware('checkCom');

    Route::get('/patroli_kandang_xls', [PatroliKandangController::class, 'exportXls'])->name('patroli.kandang.export.xls')->middleware('checkCom');
    Route::get('/patroli_kandang_pdf', [PatroliKandangController::class, 'exportPdf'])->name('patroli.kandang.export.pdf')->middleware('checkCom');

    Route::resource('doc_out', DocOutController::class)->middleware('checkCom');
    Route::get('/doc_out_table', [DocOutController::class, 'doc_out_table'])->name('doc.out.table')->middleware('checkCom');
    Route::get('/doc_export_xls', [DocOutController::class, 'export_xls'])->name('doc.export.xls')->middleware('checkCom');
    Route::get('/doc_export_pdf', [DocOutController::class, 'export_pdf'])->name('doc.export.pdf')->middleware('checkCom');

    Route::resource('jam_shift', JamShiftController::class);
    Route::get('/jam_shift_table', [JamShiftController::class, 'jam_shift_table'])->name('jam.shift.table');

    Route::resource('laporan_situasi', LaporanSituasiController::class);
    Route::get('/laporan_situasi_table', [LaporanSituasiController::class, 'laporan_situasi_table'])->name('laporan.situasi.table');
    Route::get('/situasi_export_xls', [LaporanSituasiController::class, 'export_xls'])->name('situasi.export.xls');
    Route::get('/situasi_export_pdf', [LaporanSituasiController::class, 'export_pdf'])->name('situasi.export.pdf');

    Route::resource('tamu', TamuController::class);
    Route::get('/tamu_table', [TamuController::class, 'tamu_table'])->name('tamu.table');
    Route::get('/tamu_export_xls', [TamuController::class, 'export_xls'])->name('tamu.export.xls');
    Route::get('/tamu_export_pdf', [TamuController::class, 'export_pdf'])->name('tamu.export.pdf');

    Route::resource('emergency', EmergencyListController::class);
    Route::get('/emergency_table', [EmergencyListController::class, 'emergency_table'])->name('emergency.table');

    Route::get('/laporan_kandang', [LaporanKandangController::class, 'index'])->middleware('checkCom');
    Route::post('/tampilkan_laporan_kandang', [LaporanKandangController::class, 'tampilkan_laporan'])->name('tampilkan.laporan.kandang')->middleware('checkCom');
    Route::post('/laporan_kandang_export_xls', [LaporanKandangController::class, 'export_xls'])->name('laporan.kandang.export.xls')->middleware('checkCom');
    Route::get('/laporan_kandang_export_pdf', [LaporanKandangController::class, 'export_pdf'])->name('laporan.kandang.export.pdf')->middleware('checkCom');

    Route::resource('perusahaan', PerusahaanController::class);
    Route::get('/perusahaan_table', [PerusahaanController::class, 'perusahaan_table'])->name('perusahaan.table');

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile_update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change_password', [ProfileController::class, 'change']);
    Route::post('/update_password', [ProfileController::class, 'update_password'])
    ->name('user.password.update');

    Route::resource('broadcast', BroadcastController::class);
    Route::get('/broadcast_table', [BroadcastController::class, 'broadcast_table'])->name('broadcast.table');


    
});

require __DIR__ . '/auth.php';
