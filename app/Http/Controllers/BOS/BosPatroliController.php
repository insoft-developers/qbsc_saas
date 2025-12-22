<?php

namespace App\Http\Controllers\Bos;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\Patroli;
use Illuminate\Http\Request;

class BosPatroliController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Patroli::with(['lokasi','satpam', 'company'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER STATUS (1 = masuk, 2 = pulang)
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // 🔍 FILTER NAMA SATPAM
        if ($request->filled('satpam_id')) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function lokasi(Request $request) {
        $comid = $request->comid;

        $data = Lokasi::where('comid', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }
}
