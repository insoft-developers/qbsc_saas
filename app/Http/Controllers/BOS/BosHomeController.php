<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\MainSlider;
use Illuminate\Http\Request;

class BosHomeController extends Controller
{
    public function slider(Request $request)
    {
        $data = MainSlider::where('is_active', 1)->orderBy('id', 'asc')->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function slider_new(Request $request)
    {
        $comid = $request->comid;

        $data = MainSlider::where('is_active', 1)
            ->where(function ($query) use ($comid) {

                // Slider untuk semua perusahaan
                $query->whereNull('comid')

                    // Slider untuk perusahaan tertentu
                    ->orWhereJsonContains('comid', (int) $comid);
            })
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
