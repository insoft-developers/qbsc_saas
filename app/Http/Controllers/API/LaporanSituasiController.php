<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LaporanSituasi;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class LaporanSituasiController extends Controller
{
    public function laporan_situasi(Request $request) {
        $input = $request->all();
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required',
            'satpam_id' => 'required',
            'laporan' => 'required',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                // Gunakan Intervention Image
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Resize otomatis jika terlalu besar
                if ($image->width() > 1280) {
                    $image->scale(width: 1280);
                }

                // Tentukan folder penyimpanan
                $folder = storage_path('app/public/situasi');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'situasi/' . $filename;
            }

            $input['foto'] = $photoPath;
            $result = LaporanSituasi::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Data Doc tersimpan',
                'data' => $result,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gangguan Server/Offline Mode',
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }
}
