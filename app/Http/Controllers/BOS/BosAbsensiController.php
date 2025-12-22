<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class BosAbsensiController extends Controller
{
    public function index(Request $request) {
        
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 20);

        $offset = ($page - 1) * $limit;
        
        $data = Absensi::with('satpam','company')->where('comid', $request->comid)
        ->orderBy('id', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

        $total = Absensi::with('satpam','company')->where('comid', $request->comid)->count();

        return response()->json([
            "success" => true,
            "data" => $data,
            "page" => (int)$page,
            "total" => $total
        ]);

    }
}
