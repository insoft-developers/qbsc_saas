<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patroli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
class PatroliController extends Controller
{
    public function sendPatrolitoServer(Request $request)
    {
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
            'comid' => 'required',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                // Gunakan Intervention Image
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Resize otomatis jika terlalu besar
                if ($image->width() > 1280) {
                    $image->scale(width: 1280);
                }

                // Tentukan folder penyimpanan
                $folder = storage_path('app/public/patroli');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'patroli/' . $filename;
            }

            $patroli = Patroli::create([
                'uuid' => $request->id,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'location_id' => $request->location_id,
                'location_code' => $request->location_code,
                'satpam_id' => $request->satpam_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'note' => $request->note,
                'comid' => $request->comid,
                'photo_path' => $photoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }
}