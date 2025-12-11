<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Satpam;
use Carbon\Carbon;
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
}
