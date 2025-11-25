<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function checkQrTamu(Request $request) {
        $input = $request->all();

        $data = Tamu::where('uuid', $input['qrcode'])->where('comid', $input['comid'])
        ->where('is_status', '<', 3)
        ->first();
        if($data) {
            return response()->json([
                "success" => true,
                "message" => "Berhasil",
                "data" => $data
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Data tidak ditemukan",
                "data" => []
            ]);
        }
    }

    public function saveDataTamu(Request $request) {
        $input = $request->all();
        $is_masuk = $input['masuk'];
        $id = $input['id'];
        $satpam_id = $input['satpam_id'];

        $tamu = Tamu::find($id);

        if($is_masuk == 'masuk') {
            $tamu->satpam_id = $satpam_id;
            $tamu->is_status = 2;
            $tamu->arrive_at = date('Y-m-d H:i:s');

        } else {
            $tamu->satpam_id_pulang = $satpam_id;
            $tamu->is_status = 3;
            $tamu->leave_at = date('Y-m-d H:i:s');
        }
        $tamu->save();

        return response()->json([
            "success" => true,
            "message" => 'berhasil'
        ]);
    }
}
