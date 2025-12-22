<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class BosAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);
    
        $data = Absensi::with(['satpam', 'company'])
            ->where('comid', $request->comid)
            ->orderBy('id', 'desc')
            ->paginate($limit);
    
        return response()->json([
            "success" => true,
            "data"    => $data
        ]);
    }
}
