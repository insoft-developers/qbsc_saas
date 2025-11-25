<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function checkQrTamu(Request $request) {
        $input = $request->all();

        $data = Tamu::where('uuid', $input['qrcode'])->where('comid', $input['comid'])->first();
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
}
