<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PatroliController;
use App\Http\Controllers\API\ValidateLocationController;
use App\Http\Controllers\API\AbsenController;
use App\Http\Controllers\API\DaruratController;
use App\Http\Controllers\API\DuitkuCallbackController;
use App\Http\Controllers\API\LaporanSituasiController;
use App\Http\Controllers\API\NotifController;
use App\Http\Controllers\API\NotifikasiController;
use App\Http\Controllers\API\TamuController;
use App\Http\Controllers\BOS\BosAbsensiController;
use App\Http\Controllers\BOS\BosAuthController;
use App\Http\Controllers\BOS\BosBroadcastController;
use App\Http\Controllers\BOS\BosDocController;
use App\Http\Controllers\BOS\BosHomeController;
use App\Http\Controllers\BOS\BosKandangController;
use App\Http\Controllers\BOS\BosPatroliController;
use App\Http\Controllers\BOS\BosSituasiController;
use App\Http\Controllers\Frontend\DuitkuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('bos')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/login', [BosAuthController::class, 'login'])->withoutMiddleware('auth:sanctum');      
        Route::post('/absensi', [BosAbsensiController::class, 'index'] );
        Route::post('/satpam', [BosAbsensiController::class, 'satpam']);
        Route::post('/patroli', [BosPatroliController::class, 'index']);
        Route::post('/lokasi', [BosPatroliController::class, 'lokasi']);
        Route::post('/kandang_suhu', [BosKandangController::class, 'suhu']);
        Route::post('/kandang_kipas', [BosKandangController::class, 'kipas']);
        Route::post('/kandang_alarm', [BosKandangController::class, 'alarm']);
        Route::post('/kandang_lampu', [BosKandangController::class, 'lampu']);
        Route::post('/kandang', [BosKandangController::class, 'kandang']);
        Route::post('/doc', [BosDocController::class, 'index']);
        Route::post('/ekspedisi', [BosDocController::class, 'ekspedisi']);
        Route::post('/broadcast', [BosBroadcastController::class, 'index']);
        Route::post('/broadcast_add', [BosBroadcastController::class, 'add']);
        Route::post('/broadcast_delete', [BosBroadcastController::class, 'delete']);
        Route::post('/situasi', [BosSituasiController::class, 'index']);
        Route::post('/slider', [BosHomeController::class, 'slider']);
    });




Route::get('/shift_testing', [ValidateLocationController::class, 'testing']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify_face', [AbsenController::class, 'verifyFace']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/check_paket', [AuthController::class, 'checkPaket']);
    Route::post('/absen_active', [AbsenController::class, 'absenActive']);
    Route::post('/get_data_shift', [AbsenController::class, 'getDataShift']);

    Route::post('/location_data', [ValidateLocationController::class, 'locationData']);
    Route::post('/get_data_location', [ValidateLocationController::class, 'getDataLocation']);

    Route::post('/update_location_coordinates', [ValidateLocationController::class, 'updateCoordinates']);
    Route::post('/send_patroli_to_server', [PatroliController::class, 'sendPatrolitoServer']);
    Route::post('/patroli_kandang_to_server', [PatroliController::class, 'patroliKandangToServer']);
    Route::post('/get_data_kandang', [PatroliController::class, 'getDataKandang']);
    Route::post('/get_data_mesin', [PatroliController::class, 'getDataMesin']);
    Route::post('/get_data_ekspedisi', [PatroliController::class, 'getDataEkspedisi']);
    Route::post('/sync_suhu_kandang', [PatroliController::class, 'syncSuhuKandang']);
    Route::post('/sync_kipas_kandang', [PatroliController::class, 'syncKipasKandang']);
    Route::post('/sync_alarm_kandang', [PatroliController::class, 'syncAlarmKandang']);
    Route::post('/sync_lampu_kandang', [PatroliController::class, 'syncLampuKandang']);
    Route::post('/sync_doc_report', [PatroliController::class, 'syncDocReport']);
    Route::post('/laporan_situasi', [LaporanSituasiController::class, 'laporan_situasi']);

    Route::post('/check_qr_tamu', [TamuController::class, 'checkQrTamu']);
    Route::post('/save_data_tamu', [TamuController::class, 'saveDataTamu']);
    Route::post('/tambah_data_tamu', [TamuController::class, 'tambahDataTamu']);

    Route::post('/get_list_tamu', [TamuController::class, 'getListTamu']);
    Route::post('/update_status_tamu', [TamuController::class, 'updateStatusTamu']);

    Route::post('/darurat', [DaruratController::class, 'index']);
    Route::post('/get_notif_list', [NotifController::class, 'index']);

    Route::post('/get_profile_data', [AuthController::class, 'profile']);
    Route::post('/update_satpam_profile', [AuthController::class, 'updateSatpamProfile']);
    Route::post('/ubah_password_satpam', [AuthController::class, 'ubah_password']);
});

Route::post('/duitku_callback', [DuitkuCallbackController::class, 'callback']);



