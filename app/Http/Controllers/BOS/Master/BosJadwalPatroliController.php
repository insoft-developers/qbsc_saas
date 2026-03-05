<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\JadwalPatroli;
use App\Models\JadwalPatroliDetail;
use Illuminate\Http\Request;

class BosJadwalPatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $comid = $request->comid;
        $data = JadwalPatroli::with('company:id,company_name')->where('comid', $comid)->orderBy('id','desc')->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $comid = $input['comid'];
        $validated = $request->validate([
            'name' => 'required|max:100|min:3',
            'description' => 'nullable',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $comid;
            $input['name'] = strtoupper($request->name);
            $input['is_active'] = 0;

            JadwalPatroli::create($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $comid = $input['comid'];
        $validated = $request->validate([
            'name' => 'required|max:100|min:3',
            'description' => 'nullable',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $comid;
            $input['name'] = strtoupper($request->name);
            $data = JadwalPatroli::find($id);
            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id, Request $request)
    {
        $input = $request->all();
        $comid = $input['comid'];
        $count = JadwalPatroli::where('comid', $comid)->count();
        if($count == 1) {
            return response()->json([
                "success" => false,
                "message" => "Harus ada 1 jadwal patroli aktif"
            ]);
        } 
        
        JadwalPatroli::destroy($input['id']);
        return response()->json([
                "success" => true,
                "message" => "success"
        ]);
    }


    public function ubahStatus(Request $request) {
        $input = $request->all();

        if($input['stat'] == 1) {
            JadwalPatroli::where('comid', $request->comid)->update([
                "is_active" => 0
            ]);
        }


        $jadwal = JadwalPatroli::find($input['id']);
        $jadwal->is_active = $input['stat'];
        $jadwal->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }


    public function detail(Request $request) {
        $input = $request->all();
        $comid = $input['comid'];
        $id = $input['id'];
        $data = JadwalPatroliDetail::with('jadwal','location','company')
        ->where('comid', $comid)
        ->where('patroli_id', $id)
        ->orderBy('urutan','asc')
        ->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }
}
