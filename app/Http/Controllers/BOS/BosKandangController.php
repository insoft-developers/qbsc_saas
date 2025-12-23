<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Models\KandangSuhu;
use Illuminate\Http\Request;

class BosKandangController extends Controller
{
    public function kandang(Request $request) {
        $data = Kandang::select('id', 'name')->where('comid', $request->comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }
    
    public function suhu(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = KandangSuhu::with(['satpam', 'company','kandang'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER STATUS (1 = masuk, 2 = pulang)
        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
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
}
