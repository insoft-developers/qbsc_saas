<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Satpam;
use Illuminate\Http\Request;

class ValidateLocationController extends Controller
{
    public function locationData(Request $request) {
        $request->validate([
            'satpam_id' => 'required',
        ]);

        $satpam = Satpam::find($request->satpam_id);

        $data = AbsenLocation::where('comid', $satpam->comid)->first();
        if($data) {
            return response()->json([
                "success" => true,
                "data" => $data
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Lokasi Absen tidak ditemukan!'
            ]);
        }
    }
}
