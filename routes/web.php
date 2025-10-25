<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\LokasiController;
use App\Http\Controllers\Frontend\SatpamController;
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
});

require __DIR__ . '/auth.php';
