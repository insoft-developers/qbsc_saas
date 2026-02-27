<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class BosSatpamController extends Controller
{
    
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $satpams = Satpam::with('company:id,company_name')->where('comid', $input['comid'])->orderBy('id','desc')->get();
        return response()->json([
            'success' => true,
            'data' => $satpams,
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
        
        $request->validate([
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
        $badgeID = $this->generateCode("SEC");
        Satpam::create([
            'name' => $request->name,
            'badge_id' => $badgeID,
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
        

        $satpam = Satpam::findOrFail($id);

        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('satpams', 'whatsapp')->ignore($satpam->id)],
            'password' => 'nullable|string|min:6',
            'is_danru' => 'required'
        ]);

        $face_url = config('services.face_api.url');
        // update foto jika ada upload
        if ($request->hasFile('foto')) {
            // hapus foto lama
            if ($satpam->face_photo_path && Storage::disk('public')->exists($satpam->face_photo_path)) {
                Storage::disk('public')->delete($satpam->face_photo_path);
            }

            // simpan foto baru
            $path = $request->file('foto')->store('satpam', 'public');
            $satpam->face_photo_path = $path;

            try {
                // kirim ke API face recognition
                $response = Http::timeout(10)
                    ->attach('image', file_get_contents($request->file('foto')), 'face.jpg')
                    ->post($face_url . '/encode');

                if ($response->successful()) {
                    $satpam->face_embedding = json_encode($response->json('embedding'));
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Gagal membaca wajah dari server AI.',
                        ],
                        400,
                    );
                }
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Server AI tidak merespon.',
                        'error' => $e->getMessage(),
                    ],
                    500,
                );
            }
        }

        // Update data lain
        $satpam->name = $validated['name'];
        $satpam->whatsapp = $validated['whatsapp'];

        if (!empty($validated['password'])) {
            $satpam->password = bcrypt($validated['password']);
        }
        $satpam->is_danru = $request->is_danru;

        $satpam->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil diperbarui.',
            'data' => $satpam,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $satpam = Satpam::findOrFail($id);

            // Jika ada foto, hapus dari storage
            if ($satpam->face_photo_path && Storage::disk('public')->exists($satpam->face_photo_path)) {
                Storage::disk('public')->delete($satpam->face_photo_path);
            }

            $satpam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Satpam berhasil dihapus.',
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

    public function ubahStatus(Request $request) {
        $input = $request->all();
        $satpam = Satpam::find($input['id']);
        $satpam->is_active = $input['stat'];
        $satpam->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }

    protected function generateCode($prefix)
    {
        $randomNumber = random_int(10000000, 99999999);
        return $prefix . $randomNumber;
    }
}
