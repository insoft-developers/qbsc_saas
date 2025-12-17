<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class TamuController extends Controller
{
    public function checkQrTamu(Request $request)
    {
        $input = $request->all();

        $data = Tamu::where('uuid', $input['qrcode'])->where('comid', $input['comid'])->where('is_status', '<', 3)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => [],
            ]);
        }
    }

    public function saveDataTamu(Request $request)
    {
        $input = $request->all();
        $is_masuk = $input['masuk'];
        $id = $input['id'];
        $satpam_id = $input['satpam_id'];

        $tamu = Tamu::find($id);

        if ($is_masuk == 'masuk') {
            $tamu->satpam_id = $satpam_id;
            $tamu->is_status = 2;
            $tamu->arrive_at = date('Y-m-d H:i:s');
        } else {
            $tamu->satpam_id_pulang = $satpam_id;
            $tamu->is_status = 3;
            $tamu->leave_at = date('Y-m-d H:i:s');
        }
        $tamu->save();

        return response()->json([
            'success' => true,
            'message' => 'berhasil',
        ]);
    }

    public function tambahDataTamu(Request $request)
    {
        $input = $request->all();
        $validated = $request->validate([
            'nama_tamu' => 'required|string|max:100',
            'jumlah_tamu' => 'required',
            'tujuan' => 'required',
            'whatsapp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        // Simpan foto ke storage
        $path = null;

        // if ($request->hasFile('foto')) {
        //     $path = $request->file('foto')->store('tamu', 'public');
        // }

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
            $folder = storage_path('app/public/tamu');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'tamu/' . $filename;
        }

        // Simpan ke database
        $input['foto'] = $path;
        $input['uuid'] = Str::uuid();
        $input['is_status'] = 2;
        $input['created_by'] = -1;
        $input['arrive_at'] = date('Y-m-d H:i:s');
        Tamu::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function getListTamu(Request $request)
    {
        $input = $request->all();

        $data = Tamu::where('comid', $input['comid'])->where('is_status', 2)->orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function updateStatusTamu(Request $request)
    {
        $input = $request->all();
        $data = Tamu::find($input['id']);
        $data->satpam_id_pulang = $input['satpam_id'];
        $data->leave_at = date('Y-m-d H:i:s');
        $data->is_status = 3;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'success',
        ]);
    }
}
