<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DocChick;
use App\Models\Kandang;
use App\Models\Patroli;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function kandang(Request $request)
    {
        $comid = $request->comid;
        $satpam_id = $request->satpam_id;
        $tanggal = $request->tanggal;
        $jam = $request->jam;
        $start = $jam . ':00';   
        $end   = date('H:i:s', strtotime($start . ' +1 hour')); // 05:00:00

        $kandangs = Kandang::where('comid', $comid)
            ->select('id', 'name')
            ->with([
                'suhuData' => function ($q) use ($satpam_id, $tanggal, $start, $end) {
                    $q->where('satpam_id', $satpam_id)->whereDate('tanggal', $tanggal)
                    ->whereTime('jam', '>=', $start)
                    ->whereTime('jam', '<', $end)
                    ->select('id','tanggal','jam', 'kandang_id', 'temperature');
                   
                },
            ])

            ->with([
                'kipasData' => function ($q) use ($satpam_id, $tanggal, $start, $end) {
                    $q->where('satpam_id', $satpam_id)->whereDate('tanggal', $tanggal)
                    ->whereTime('jam', '>=', $start)
                    ->whereTime('jam', '<', $end)
                    ->select('id','tanggal','jam', 'kandang_id', 'kipas','foto');
                },
            ])


            ->with([
                'alarmData' => function ($q) use ($satpam_id, $tanggal, $start, $end) {
                    $q->where('satpam_id', $satpam_id)->whereDate('tanggal', $tanggal)
                    ->whereTime('jam', '>=', $start)
                    ->whereTime('jam', '<', $end)
                    ->select('id','tanggal','jam', 'kandang_id', 'is_alarm_on');
                },
            ])


            ->with([
                'lampuData' => function ($q) use ($satpam_id, $tanggal, $start, $end) {
                    $q->where('satpam_id', $satpam_id)->whereDate('tanggal', $tanggal)
                    ->whereTime('jam', '>=', $start)
                    ->whereTime('jam', '<', $end)
                    ->select('id','tanggal','jam', 'kandang_id', 'is_lamp_on');
                },
            ])

            ->get();

        return response()->json([
            'success' => true,
            'data' => $kandangs,
        ]);
    }


    public function doc(Request $request) {
        $comid = $request->comid;
        $satpam_id = $request->satpam_id;
        $tanggal = $request->tanggal;

        $docs = DocChick::with('ekspedisi')->where('satpam_id', $satpam_id)->where('comid', $comid)->where('tanggal', $tanggal)->get();
        return response()->json([
            "success" => true,
            "data" => $docs
        ]);
    }


    public function patroli(Request $request) {
        $comid = $request->comid;
        $satpam_id = $request->satpam_id;
        $tanggal = $request->tanggal;

        $docs = Patroli::with('lokasi')->where('satpam_id', $satpam_id)->where('comid', $comid)->where('tanggal', $tanggal)
        ->orderBy('tanggal','desc')
        ->orderBy('jam','desc')
        ->get();
        return response()->json([
            "success" => true,
            "data" => $docs
        ]);
    }
}
