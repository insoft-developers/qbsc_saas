<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\AbsenLocationController;
use App\Http\Controllers\Frontend\AbsensiController;
use App\Http\Controllers\Frontend\AssetController;
use App\Http\Controllers\Frontend\BroadcastController;
use App\Http\Controllers\Frontend\CustomFeatureController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DocOutController;
use App\Http\Controllers\Frontend\DuitkuController;
use App\Http\Controllers\Frontend\EkspedisiController;
use App\Http\Controllers\Frontend\EmergencyListController;
use App\Http\Controllers\Frontend\GenerateKeyController;
use App\Http\Controllers\Frontend\GoogleController;
use App\Http\Controllers\Frontend\JadwalPatroliController;
use App\Http\Controllers\Frontend\JadwalPatroliDetailController;
use App\Http\Controllers\Frontend\JamShiftController;
use App\Http\Controllers\Frontend\KandangController;
use App\Http\Controllers\Frontend\LaporanKandangController;
use App\Http\Controllers\Frontend\LaporanSituasiController;
use App\Http\Controllers\Frontend\LokasiController;
use App\Http\Controllers\Frontend\MesinController;
use App\Http\Controllers\Frontend\NotifikasiController;
use App\Http\Controllers\Frontend\PaketLanggananController;
use App\Http\Controllers\Frontend\PatroliController;
use App\Http\Controllers\Frontend\PatroliKandangController;
use App\Http\Controllers\Frontend\PerusahaanController;
use App\Http\Controllers\Frontend\SatpamController;
use App\Http\Controllers\Frontend\TamuController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\RekapController;
use App\Http\Controllers\Frontend\RiwayatController;
use App\Http\Controllers\Frontend\RunningTextController;
use App\Http\Controllers\Reseller\ResellerAuthController;
use App\Http\Controllers\Reseller\ResellerDownloadController;
use App\Http\Controllers\Reseller\ResellerHomeController;
use App\Http\Controllers\Reseller\ResellerTransactionController;
use App\Http\Controllers\Reseller\ResellerUserController;
use App\Http\Controllers\Reseller\ResellerWithdrawController;
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
Route::post('whatsapp_payment', [PaketLanggananController::class, 'whatsapp_payment'])->name('whatsapp.payment');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'verified', 'isPaket']], function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('checkData');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('checkData');
    Route::post('/tampilkan_absensi_satpam', [DashboardController::class, 'tampilkan_absensi_satpam'])->name('tampilkan.absensi.satpam');
    Route::post('/tampilkan_patroli_satpam', [DashboardController::class, 'tampilkan_patroli_satpam'])->name('tampilkan.patroli.satpam');
    Route::post('/tampilkan_satpam_terlambat', [DashboardController::class, 'terlambat'])->name('tampilkan.satpam.terlambat');
    

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
    Route::post('/user_activate', [UserController::class, 'activate'])->name('user.activate');
    Route::post('/tambah_user_area', [UserController::class, 'tambah_user_area'])->name('tambah.user.area');
    Route::post('/tampilkan_area_table', [UserController::class, 'tampilkan_area_table'])->name('tampilkan.area.table');
    Route::post('/area_activate', [UserController::class, 'area_activate'])->name('area.activate');
    Route::post('/hapus_user_area', [UserController::class, 'hapus_user_area'])->name('hapus.user.area');

    Route::resource('kandang', KandangController::class)->middleware('checkCom');
    Route::get('/kandang_table', [KandangController::class, 'kandang_table'])->name('kandang.table')->middleware('checkCom');
    Route::post('/kandang_activate', [KandangController::class, 'activate'])->name('kandang.activate');

    Route::resource('mesin', MesinController::class)->middleware('checkCom');
    Route::get('/mesin_table', [MesinController::class, 'mesin_table'])->name('mesin.table')->middleware('checkCom');

    Route::resource('ekspedisi', EkspedisiController::class)->middleware('checkCom');
    Route::get('/ekspedisi_table', [EkspedisiController::class, 'ekspedisi_table'])->name('ekspedisi.table')->middleware('checkCom');

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

    Route::get('/paket_langganan', [PaketLanggananController::class, 'index'])->withoutMiddleware('isPaket');
    Route::post('/paket_gratis', [PaketLanggananController::class, 'paket_gratis'])->name('paket.gratis')->withoutMiddleware('isPaket');
;
    Route::post('/create_payment', [DuitkuController::class, 'create_payment'])->name('create.payment')->withoutMiddleware('isPaket');
    Route::get('/duitku_return', [PaketLanggananController::class, 'index'])->name('duitku.return')->withoutMiddleware('isPaket');

    Route::resource('riwayat', RiwayatController::class)->withoutMiddleware('isPaket');
    Route::get('/riwayat_table', [RiwayatController::class, 'riwayat_table'])->name('riwayat.table')->withoutMiddleware('isPaket');
    Route::get('/print_invoice/{invoice}', [RiwayatController::class, 'print'])->withoutMiddleware('isPaket');

    Route::resource('custom_feature', CustomFeatureController::class);
    Route::get('/custom_feature_table', [CustomFeatureController::class, 'custom_feature_table'])->name('custom.feature.table');
    Route::post('/create_feature_payment', [CustomFeatureController::class, 'payment'])->name('create.feature.payment');

    Route::resource('notifikasi', NotifikasiController::class);
    Route::get('/notifikasi_table', [NotifikasiController::class, 'notifikasi_table'])->name('notifikasi.table');
    Route::get('/check_notif', [NotifikasiController::class, 'check'])->name('check.notif');
    Route::get('/notif_list', [NotifikasiController::class, 'list'])->name('notif.list');

    Route::resource('asset_page', AssetController::class);
    Route::get('/asset_page_table', [AssetController::class, 'asset_page_table'])->name('asset.page.table');

    Route::resource('generate_key_id', GenerateKeyController::class);
    Route::post('generate_key_post', [GenerateKeyController::class, 'generate'])->name('generate.key');

    Route::get('/running_text', [RunningTextController::class, 'index']);
    Route::post('/running_text_update', [RunningTextController::class, 'update'])->name('running.text.update');

    Route::post('/check_jenis_perusahaan', [PerusahaanController::class, 'check'])->name('check.jenis.perusahaan')->withoutMiddleware('isPaket');
    Route::post('/update_jenis_perusahaan', [PerusahaanController::class, 'update_jenis'])->name('update.jenis.perusahaan')->withoutMiddleware('isPaket');

    Route::resource('jadwal_patroli', JadwalPatroliController::class);
    Route::get('/jadwal_patroli_table', [JadwalPatroliController::class, 'table'])->name('jadwal.patroli.table');

    Route::resource('/jadwal_patroli_detail', JadwalPatroliDetailController::class);
    Route::post('/jadwal_patroli_detail_table', [JadwalPatroliDetailController::class, 'table'])->name('jadwal.patroli.detail.table');


    Route::get('/rekap', [RekapController::class, 'index']);
    Route::get('/laporan_rekap_data', [RekapController::class, 'table'])->name('laporan.rekap.data');

    Route::get('/laporan_rekap_excel', [RekapController::class, 'exportExcel'])->name('laporan.rekap.excel');
    Route::get('/laporan_rekap_pdf', [RekapController::class, 'exportPdf'])->name('laporan.rekap.pdf');
}); 

Route::get('/r/register/{code}', function ($code) {
    session(['referal_code' => $code]);
    return redirect('/register');
});




Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);


Route::prefix('reseller')->group(function () {

    Route::get('/register', [ResellerAuthController::class, 'register']);
    Route::post('/register_post', [ResellerAuthController::class, 'register_post'])->name('reseller.register');
    Route::get('/login', [ResellerAuthController::class, 'showLogin'])->name('reseller.login');
    Route::post('/login', [ResellerAuthController::class, 'login'])->name('reseller.login.post');
    Route::get('/activate/{token}', [ResellerAuthController::class, 'activate'])->name('reseller.activate');
    Route::middleware('auth:reseller')->group(function () {
        Route::get('/', [ResellerHomeController::class, 'index']);
        Route::get('/dashboard', [ResellerHomeController::class, 'index']);
        Route::post('/logout', [ResellerAuthController::class, 'logout'])->name('reseller.logout');

        Route::resource('user', ResellerUserController::class);
        Route::get('/user_table', [ResellerUserController::class, 'user_table'])->name('reseller.user.table');

        Route::get('/transaction', [ResellerTransactionController::class, 'index']);
        Route::get('/transaction_table', [ResellerTransactionController::class, 'table'])->name('reseller.transaction.table');

        Route::resource('/withdraw', ResellerWithdrawController::class);
        Route::get('/withdraw_table', [ResellerWithdrawController::class, 'table'])->name('reseller.withdraw.table');

        Route::resource('/download', ResellerDownloadController::class);
        Route::get('/download_table', [ResellerDownloadController::class, 'table'])->name('download.table');
        
    });

});







require __DIR__ . '/auth.php';
