<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanAnggotaController extends Controller
{
    public function absensi(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        try {
            $satpams = DB::table('satpams as s')
                ->leftJoin('absensis as a', function ($join) {
                    $join->on(
                        'a.id',
                        '=',
                        DB::raw('(
            SELECT a2.id FROM absensis a2
            WHERE a2.satpam_id = s.id
            ORDER BY a2.created_at DESC, a2.id DESC
            LIMIT 1
        )'),
                    );
                })
                ->select('s.id', 's.name', 's.whatsapp', 's.face_photo_path', 'a.tanggal', 'a.jam_masuk','a.jam_keluar', 'a.status')
                ->where('s.comid', $request->comid)
                ->get();

            return response()->json([
                "success" => true,
                "data" => $satpams,

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
