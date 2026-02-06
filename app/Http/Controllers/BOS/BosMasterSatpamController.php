<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


class BosMasterSatpamController extends Controller
{
    
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $satpams = Satpam::with('company:id,company_name')
            ->select('id','name', 'whatsapp', 'comid', 'face_photo_path', 'is_active', 'is_danru', 'last_latitude', 'last_longitude', 'last_seen_at', 'created_at')
            ->where('comid', $input['comid'])->get();
        return response()->json([
            "success" => true,
            "data" => $satpams
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
        $validated = $request->validate([
            'comid' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20|unique:satpams,whatsapp',
            'password' => 'required|string|min:6',
            'is_danru' => 'required'
        ]);

        $comid = $request->comid;

        $paket = $this->what_paket($comid);
        $max = $paket['jumlah_satpam'];

        $jumlah_satpam = Satpam::where('comid', $comid)->where('is_active', 1)->count();
        if ($jumlah_satpam >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah satpam sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah satpam !!',
            ]);
        }

        $face_url = config('services.face_api.url');
        $response = Http::attach('image', file_get_contents($request->file('foto')), 'face.jpg')->post($face_url . '/encode');

        if (!$response->successful()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal membaca wajah, pastikan wajah terlihat jelas.',
                ],
                400,
            );
        }

        $embedding = $response->json('embedding');

        // Simpan foto ke storage
        $path = $request->file('foto')->store('satpam', 'public');

        // Simpan ke database
        $satpam = Satpam::create([
            'name' => $request->name,
            'badge_id' => strtoupper(uniqid()),
            'whatsapp' => $request->whatsapp,
            'password' => bcrypt($request->password),
            'face_photo_path' => $path,
            'comid' => $comid,
            'face_embedding' => json_encode($embedding),
            'is_danru' => $request->is_danru
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil disimpan.',
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
