<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use Illuminate\Http\Request;

class BosDocController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = DocChick::with(['satpam', 'company', 'ekspedisi'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER STATUS (1 = masuk, 2 = pulang)
        if ($request->filled('ekspedisi_id')) {
            $query->where('ekspedisi_id', $request->ekspedisi_id);
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

    public function ekspedisi(Request $request) {
        $comid = $request->comid;

        $data = Ekspedisi::where('comid', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }
}
