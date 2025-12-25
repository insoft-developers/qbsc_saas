<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BosTamuController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Tamu::with(['satpam', 'company', 'user', 'satpam_pulang'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($request->satpam_id) {
            $query->where(function ($q) use ($request) {
                $q->where('satpam_id', $request->satpam_id)->orWhere('satpam_id_pulang', $request->satpam_id);
            });
        }

        if ($request->user_id) {
            if ($request->user_id == -1) {
                $query->whereNull('created_by');
            } else {
                $query->where('created_by', $request->user_id);
            }
        }

        $data = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function user(Request $request) {
        $comid = $request->comid;

        $data = User::where('company_id', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }
}
