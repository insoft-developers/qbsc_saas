<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Lokasi;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

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
        $data = Lokasi::with('company:id,company_name')->where('comid', $comid)
            ->orderBy('id', 'desc')
            ->get();
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

        $jumlah_lokasi = Lokasi::where('comid', $comid)->where('is_active', 1)->count();
        if ($jumlah_lokasi >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah Lokasi sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah lokasi !!',
            ]);
        }

        // Simpan ke database
        try {
            $input['qrcode'] = $this->generateCode("LOC") . '-' . $comid;
            $input['comid'] = $comid;
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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $data = Lokasi::findorFail($id);
        $comid = $request->comid;

        $validated = $request->validate([
            'nama_lokasi' => 'required|max:100|min:3',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $comid;
            $input['nama_lokasi'] = strtoupper($request->nama_lokasi);
            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
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
    public function destroy(string $id)
    {
        try {
            Lokasi::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function ubahStatus(Request $request)
    {
        $input = $request->all();
        $user = Lokasi::find($input['id']);
        $user->is_active = $input['stat'];
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }

    public function absenLocationUpdate(Request $request)
    {
        $input = $request->all();
        try {
            $data = AbsenLocation::where('comid', $input['comid'])->firstorFail();
            $data->latitude = $input['latitude'];
            $data->longitude = $input['longitude'];
            $data->updated_at = Carbon::now();
            $data->update();

            return response()->json([
                "success" => true,
                "message" => "Sukses"
            ]);
        } catch (Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function getCurrentAbsenLocation(Request $request)
    {
        $input = $request->all();
        try {
            $data = AbsenLocation::where('comid', $input['comid'])->firstorFail();
            return response()->json([
                "success" => true,
                "data" => $data
            ]);
        } catch (Throwable $th) {
             return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
