<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JamShift;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsenController extends Controller
{
    public function verifyFace(Request $request)
    {
        $face_url = config('services.face_api.url');

        $request->validate([
            'image' => 'required|image|max:10240',
            'user_id' => 'required|integer',
            'absen_model' => 'required',
        ]);

        $userId = $request->user_id;
        $file = $request->file('image');
        $model = $request->absen_model;

        $user = Satpam::find($userId);
        if (!$user || !$user->face_embedding) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User tidak ditemukan atau belum punya embedding wajah.',
                ],
                404,
            );
        }

        $storedEmbedding = $user->face_embedding;

        $response = Http::attach('image', file_get_contents($file->getRealPath()), $file->getClientOriginalName())->post($face_url . '/verify', [
            'stored_embedding' => json_encode(json_decode($storedEmbedding)),
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal terhubung ke server Face API',
                ],
                500,
            );
        }

        $result = $response->json();

        $matched = $result['matched'] ?? false;
        $distance = $result['distance'] ?? null;
        $tanggal = date('Y-m-d');
        $jam = date('Y-m-d H:i:s');
        if ($matched) {
            if ($model == 'masuk') {
                $myShift = JamShift::find($request->shift_id);

                $shift_id = $myShift->id ?? null;
                $nama_shift = $myShift->name ?? null;
                $jam_masuk_shift = $myShift->jam_masuk ?? null;
                $jam_pulang_shift = $myShift->jam_pulang ?? null;
                Absensi::create(['tanggal' => $tanggal, 'satpam_id' => $userId, 'latitude' => $request->latitude, 'longitude' => $request->longitude, 'jam_masuk' => $jam, 'shift_id' => $shift_id, 'shift_name' => $nama_shift, 'jam_setting_masuk' => $jam_masuk_shift, 'jam_setting_pulang' => $jam_pulang_shift, 'status' => 1, 'description' => 'Absensi Berhasil', 'comid' => $user->comid]);
                $message = 'Absensi masuk berhasil.';
            } else {
                $absensi = Absensi::where('satpam_id', $userId)->where('status', 1)->whereNull('jam_keluar')->orderBy('id', 'desc')->first();

                if ($absensi) {
                    $absensi->jam_keluar = date('Y-m-d H:i:s');
                    $absensi->status = 2;
                    $absensi->save();
                    $message = 'Absensi pulang berhasil';
                } else {
                    $message = 'Belum ada absen masuk';
                }
            }

            return response()->json([
                'success' => true,
                'matched' => true,
                'distance' => $distance,
                'message' => $message,
            ]);
        }

        return response()->json(['success' => false, 'matched' => false, 'distance' => $distance, 'message' => 'Wajah tidak cocok. Absensi gagal.']);
    }

    public function absenActive(Request $request)
    {
        $input = $request->all();

        $data = Absensi::where('satpam_id', $input['satpam_id'])->orderBy('id', 'desc')->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => [
                    'tanggal' => date('Y-m-d H:i:s'),
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'status' => null,
                ],
            ]);
        }
    }

    public function getDataShift(Request $request)
    {
        $input = $request->all();
        $data = JamShift::where('comid', $input['comid'])->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function laporan_absensi(Request $request) {
        
        $limit = (int) $request->query('limit', 20);

        $query = Absensi::with(['satpam:id,name', 'company:id,company_name'])->where('satpam_id', $request->satpam_id);
        $data = $query->orderBy('jam_masuk', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
}

