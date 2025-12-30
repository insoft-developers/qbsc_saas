<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PaketLangganan;
use App\Models\Satpam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $satpam = Satpam::with('company')->where('whatsapp', $request->username)->first();

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

        $paket = PaketLangganan::find($com->paket_id);
        return response()->json([
            'success' => true,
            'message' => 'Paket aktif!',
            'is_mobile_app' => $paket->is_mobile_app,
            'is_user_area' =>  $paket->is_user_area,
            'expired_date' => $com->expired_date,
            'data' => $paket
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


    public function updateSatpamProfile(Request $request) 
    {
            
        $satpam = Satpam::findOrFail($request->satpam_id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('satpams', 'whatsapp')->ignore($satpam->id)],

        ]);
        $satpam->name = $validated['name'];
        $satpam->whatsapp = $validated['whatsapp'];
        $satpam->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil diperbarui.',
        ]);
    }


    public function ubah_password(Request $request) {
        $request->validate([
            'old_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $satpam = Satpam::find($request->satpam_id);

        // cek password lama
        if (!Hash::check($request->old_password, $satpam->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['Password lama tidak sesuai'],
            ]);
        }

        // update password
        $satpam->password = Hash::make($request->new_password);
        $satpam->save();

        return response()->json([
            'success'  => true,
            'message' => 'Password berhasil diubah',
        ]);
    }
}