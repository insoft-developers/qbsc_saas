<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patroli;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class PatroliController extends Controller
{
    public function sendPatrolitoServer(Request $request) {
        $request->validate([
            'id' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'location_id' => 'required',
            'location_code' => 'required',
            'satpam_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'note' => 'nullable|string',
            'comid' => 'required'
        ]);

        try {
            $patroli = Patroli::create([
                "uuid" => $request->id,
                "tanggal" => $request->tanggal,
                "jam" => $request->jam,
                "location_id" => $request->location_id,
                "location_code" => $request->location_code,
                "satpam_id" => $request->satpam_id,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "note" => $request->note,
                "comid" => $request->comid
            ]);

            return response()->json([
                "success" => true,
                "message" => "Data patroli tersimpan",
                "data" => $patroli
            ]);
        } catch (\Throwable $th) {
             return response()->json([
                "success" => false,
                "message" => "Gagal simpan data",
                "error" => $th->getMessage()
            ]);

        }
    }
}
