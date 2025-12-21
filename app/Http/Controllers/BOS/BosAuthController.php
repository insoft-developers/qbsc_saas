<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BosAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::with('company')->where('email', $request->email)->first();

        if ($user->is_active !== 1) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Akun anda tidak aktif.',
                ],
                401,
            );
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email atau password salah.',
                ],
                401,
            );
        }

        // Hapus token lama (optional)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('bos-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'data' => $user,
        ]);
    }
}
