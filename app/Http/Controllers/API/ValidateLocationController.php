<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Lokasi;
use App\Models\Satpam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidateLocationController extends Controller
{
    public function updateCoordinates(Request $request) {
        $request->validate([
            "comid" => 'required',
            "qrcode" => 'required',
            "lat" => 'required',
            "lng" => 'required'
        ]);

        $data = Lokasi::where('qrcode', $request->qrcode)
            ->where('comid', $request->comid)
            ->first();

        if($data) {
             $data->latitude = $request->lat;
             $data->longitude = $request->lng;
             $data->updated_at = Carbon::now();
             $data->save();
             return response()->json([
                "success" => true,
                "message" => 'Upload Coordinat Berhasil'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Data tidak ditemukan'
            ]);
        }
    }
   
   
    public function getDataLocation(Request $request) {
        $request->validate([
            "comid" => 'required'
        ]);

        $data = Lokasi::where('comid', $request->comid)->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }
    
    
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
