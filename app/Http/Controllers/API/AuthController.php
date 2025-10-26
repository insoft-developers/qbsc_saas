<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
