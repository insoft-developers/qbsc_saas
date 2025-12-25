<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\LaporanSituasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BosSituasiController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = LaporanSituasi::with(['satpam', 'company'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }

        // 🔍 FILTER NAMA SATPAM
        if ($request->filled('satpam_id')) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->orderBy('tanggal', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
