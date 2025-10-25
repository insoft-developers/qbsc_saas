<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'whatsapp' => 'required',
            'password' => 'required',
        ]);

        $satpam = Satpam::where('whatsapp', $request->username)->first();

        if (!$satpam || !Hash::check($request->password, $satpam->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.',
            ], 401);
        }

        // Hapus token lama (optional)
        $satpam->tokens()->delete();

        // Buat token baru
        $token = $satpam->createToken('satpam-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'satpam' => [
                'id' => $satpam->id,
                'name' => $satpam->name,
                'badge_id' => $satpam->badge_id,
                'whatsapp' => $satpam->whatsapp,
                'face_embedding' => $satpam->face_embedding,
                'face_photo_path' => asset('storage/' . $satpam->face_photo_path),
            ],
        ]);
    }
}
