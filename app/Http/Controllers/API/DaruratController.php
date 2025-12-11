<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmergencyList;
use Illuminate\Http\Request;

class DaruratController extends Controller
{
    public function index(Request $request) {
        $input = $request->all();

        $data = EmergencyList::where('comid', $input['comid'])->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }
}
