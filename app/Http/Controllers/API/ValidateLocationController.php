<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidateLocationController extends Controller
{
    use CommonTrait;
    public function testing()
    {
        $clockIn = '21:13:40';
        $comid = 1;
        $data = $this->shiftDetection($clockIn, $comid);

        $shift_id = $data['id'] ?? null;
        $nama_shift = $data['name'] ?? null;
        $jam_masuk_shift = $data['jam_masuk'] ?? null;
        $jam_pulang_shift = $data['jam_pulang'] ?? null;

        Absensi::create(['tanggal' => date('Y-m-d'), 'satpam_id' => 1, 'latitude' => 0.2, 'longitude' => 0.2, 'jam_masuk' => date('Y-m-d H:i:s'), 'shift_id' => $shift_id, 'shift_name' => $nama_shift, 'jam_setting_masuk' => $jam_masuk_shift, 'jam_setting_pulang' => $jam_pulang_shift, 'status' => 1, 'description' => 'Absensi Berhasil', 'comid' => 1]);

        return response()->json([
            'id' => $shift_id,
            'nama' => $nama_shift,
            'masuk' => $jam_masuk_shift,
            'pulang' => $jam_pulang_shift,
        ]);
    }

    public function updateCoordinates(Request $request)
    {
        $request->validate([
            'comid' => 'required',
            'qrcode' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $data = Lokasi::where('qrcode', $request->qrcode)->where('comid', $request->comid)->first();

        if ($data) {
            $data->latitude = $request->lat;
            $data->longitude = $request->lng;
            $data->updated_at = Carbon::now();
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Upload Coordinat Berhasil',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function getDataLocation(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = Lokasi::where('comid', $request->comid)
        ->where('is_active', 1)
        ->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function locationData(Request $request)
    {
        $request->validate([
            'satpam_id' => 'required',
        ]);

        $satpam = Satpam::find($request->satpam_id);

        $data = AbsenLocation::where('comid', $satpam->comid)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Absen tidak ditemukan!',
            ]);
        }
    }
}
