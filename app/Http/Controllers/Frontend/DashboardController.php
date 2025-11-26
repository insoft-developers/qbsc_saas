<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\Patroli;
use App\Models\Satpam;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use CommonTrait;
    public function index() {
        $view = 'dashboard';
        $comid = $this->comid();
        $satpams = Satpam::where('comid', $comid)->count();
        $locations = Lokasi::where('comid', $comid)->count();
        $users = User::where('company_id', $comid)->count();
        return view('frontend.dashboard_new', compact('view','satpams','locations', 'users'));
    }

    public function tampilkan_absensi_satpam() {
        $absensi = Absensi::with('satpam:id,name,whatsapp,face_photo_path')->where('comid', $this->comid())->orderBy('id','desc')->limit(10)->get();
        return response()->json([
            "success" => true,
            "data" => $absensi
        ]);
    }

    public function tampilkan_patroli_satpam(Request $request) {
        $data = Patroli::with('lokasi:id,nama_lokasi', 'satpam:id,name,whatsapp,face_photo_path')
            ->where('comid', $this->comid())
            ->orderBy('tanggal','desc')
            ->orderBy('jam','desc')
            ->limit(10)
            ->get();
        return response()->json([
            "success" => true,
            "data"=> $data
        ]);
    }
}
