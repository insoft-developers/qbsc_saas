<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BosDashboardController extends Controller
{
    public function satpam(Request $request) {
        $input = $request->all();
        $comid = $input['comid'];
        
        $satpams = Satpam::with('company')->where('comid', $comid);
        

        // $absensi = Absensi::with('satpam')->where('comid', $comid)->where('status', 1)
        // ->groupBy('satpam_id')
        // ->orderBy('id','desc');
        $absensi = Absensi::with('satpam:id,name,face_photo_path,whatsapp')->where('comid', $comid)
            ->where('status', 1)
            ->whereIn('id', function($q){
                $q->select(DB::Raw('MAX(id)'))
                    ->from('absensis')
                    ->groupBy('satpam_id');
                
            })
            ->orderByDesc('id');




        return response()->json([
            "success" => true,
            "absensi" => $absensi->get(),
            "satpams" => $satpams->get(),
            "active" => $satpams->where('is_active', 1)->count(),
            "absensi_jumlah" => $absensi->count()

        ]);
    }
}
