<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Validation\ValidationException;


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

    public function profile(Request $request) {
        $request->validate([
            'userid' => 'required'
        ]);

        $data = User::with('company')->find($request->userid);
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
        
    }

    public function profile_update(Request $request) {
        $input = $request->all();
        $request->validate([
            'userid' => 'required',
            'whatsapp' => 'required',
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $data = User::find($input['userid']);
        $path = $data->profile_image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Gunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Resize otomatis jika terlalu besar
            if ($image->width() > 1280) {
                $image->scale(width: 1280);
            }

            // Tentukan folder penyimpanan
            $folder = storage_path('app/public/user');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'user/' . $filename;
        }

        // Simpan ke database
        
        $data->name = $input['name'];
        $data->whatsapp = $input['whatsapp'];
        $data->profile_image = $path;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }


    public function password_change(Request $request) {
        $request->validate([
            'old_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->userid);

        // cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['Password lama tidak sesuai'],
            ]);
        }

        // update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success'  => true,
            'message' => 'Password berhasil diubah',
        ]);
    }
}
