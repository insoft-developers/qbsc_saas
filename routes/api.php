<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PatroliController;
use App\Http\Controllers\API\ValidateLocationController;
use App\Http\Controllers\API\AbsenController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify_face', [AbsenController::class, 'verifyFace']);
Route::post('/absen_active', [AbsenController::class, 'absenActive']);


Route::post('/location_data', [ValidateLocationController::class, 'locationData']);
Route::post('/get_data_location', [ValidateLocationController::class,  'getDataLocation']);

Route::post('/update_location_coordinates', [ValidateLocationController::class, 'updateCoordinates']);
Route::post('/send_patroli_to_server', [PatroliController::class, 'sendPatrolitoServer'] );
Route::post('/patroli_kandang_to_server', [PatroliController::class, 'patroliKandangToServer']);
Route::post('/get_data_kandang', [PatroliController::class, 'getDataKandang']);
Route::post('/get_data_mesin', [PatroliController::class, 'getDataMesin']);
Route::post('/sync_suhu_kandang', [PatroliController::class, 'syncSuhuKandang']);
Route::post('/sync_kipas_kandang', [PatroliController::class, 'syncKipasKandang']);
Route::post('/sync_alarm_kandang', [PatroliController::class, 'syncAlarmKandang']);
Route::post('/sync_lampu_kandang', [PatroliController::class, 'syncLampuKandang']);





