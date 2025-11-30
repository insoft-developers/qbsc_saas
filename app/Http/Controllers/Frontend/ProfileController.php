<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $view = 'profile';

        $user = User::find(Auth::user()->id);
        return view('frontend.setting.profile.profile', compact('view', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:30',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update basic data
        $user->name = $request->name;
        $user->whatsapp = $request->whatsapp;

        // Jika ada foto diupload
        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Simpan foto baru
            $file = $request->file('profile_image');
            $fileName = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('user', $fileName, 'public');

            // Simpan ke database
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
