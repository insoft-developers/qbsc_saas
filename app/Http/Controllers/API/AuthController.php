<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Satpam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $satpam = Satpam::where('whatsapp', $request->username)->first();

        if ($satpam->is_active !== 1) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Akun anda tidak aktif.',
                ],
                401,
            );
        }

        if (!$satpam || !Hash::check($request->password, $satpam->password)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Username atau password salah.',
                ],
                401,
            );
        }

        // Hapus token lama (optional)
        $satpam->tokens()->delete();

        // Buat token baru
        $token = $satpam->createToken('satpam-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'data' => $satpam,
        ]);
    }

    public function checkPaket(Request $request)
    {
        $input = $request->all();

        $com = Company::find($input['comid']);

        // Jika user tidak punya paket
        if (!$com->paket_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki paket!',
            ]);
        }

        // Jika paket tidak aktif
        if ($com->is_active != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Anda anda tidak aktif!',
            ]);
        }

        // Jika paket expired
        if ($com->expired_date && Carbon::now()->gt($com->expired_date)) {
            return response()->json([
                'success' => false,
                'message' => 'paket anda sudah expired!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paket aktif!',
        ]);
    }

    public function profile(Request $request) {
        $input = $request->all();

        $data = Satpam::find($input['satpam_id']);
        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }


    public function updateSatpamProfile(Request $request) {
        
        
        $satpam = Satpam::findOrFail($request->satpam_id);

        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('satpams', 'whatsapp')->ignore($satpam->id)],

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
        $satpam->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil diperbarui.',
        ]);
    }
}
