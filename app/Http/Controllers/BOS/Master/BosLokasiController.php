<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class BosLokasiController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */
    protected function generateCode($prefix)
    {
        $randomNumber = random_int(10000000, 99999999);
        return $prefix . $randomNumber;
    }


    public function index(Request $request)
    {
        $comid = $request->comid;
        $data = Lokasi::with('company:id,company_name')->where('comid', $comid)->get();
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
            'nama_lokasi' => 'required|max:100|min:3',
            
        ]);

        $paket = $this->what_paket($comid);
        $max = $paket['jumlah_lokasi'];

        $jumlah_lokasi = Lokasi::where('comid', $this->comid())->where('is_active', 1)->count();
        if ($jumlah_lokasi >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah Lokasi sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah lokasi !!',
            ]);
        }

        // Simpan ke database
        try {
            // $jam_awal_str = implode(",", $input['jam_awal']);
            // $jam_akhir_str = implode(",", $input['jam_akhir']);

            // $input['jam_awal'] = $jam_awal_str;
            // $input['jam_akhir'] = $jam_akhir_str;

            $input['comid'] = $this->comid();
            $input['nama_lokasi'] = strtoupper($request->nama_lokasi);

            Lokasi::create($input);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ubahStatus(Request $request) {
        $input = $request->all();
        $user = Lokasi::find($input['id']);
        $user->is_active = $input['stat'];
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }
}
