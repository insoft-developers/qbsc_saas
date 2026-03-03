<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BosUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CommonTrait;
    
    public function index(Request $request)
    {
        $input = $request->all();

        $users = User::where('company_id', $input['comid'])->with('company:id,company_name')
        ->orderBy('id','desc')
        ->get();
        return response()->json([
            "success" => true,
            "data" => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp',
            'password' => 'required|string|min:6',
            'is_mobile_admin' => 'required',
        ]);

        $comid = $request->comid;

        $paket = $this->what_paket($comid);
        $max = $paket['jumlah_user_admin'];

        $jumlah_user = User::where('company_id', $comid)->where('is_active', 1)->count();
        if ($jumlah_user >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah User sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah user !!',
            ]);
        }

        $user_area = $paket['is_user_area'];

        if ($user_area !== 1 && $request->is_area == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan upgrade paket anda untuk membuat user area',
            ]);
        }

        // Simpan foto ke storage
        $path = null;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('user', 'public');
        }

        // Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt($request->password),
            'is_active' => 1,
            'company_id' => $comid,
            'whatsapp' => $request->whatsapp,
            'level' => 'user',
            'profile_image' => $path,
            'is_area' => $request->is_area,
            'is_mobile_admin' => $request->is_mobile_admin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comid = $request->comid;
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp,' . $id,
            'password' => 'nullable|string|min:6',
            
        ]);

        $paket = $this->what_paket($comid);
        $user_area = $paket['is_user_area'];

        if ($user_area !== 1 && $request->is_area == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan upgrade paket anda untuk membuat user area',
            ]);
        }

        $path = $user->profile_image;

        // Jika ada foto baru diupload
        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Upload foto baru
            $path = $request->file('profile_image')->store('user', 'public');
        }

        // Update data user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'profile_image' => $path,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // Jika ada foto, hapus dari storage
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Satpam berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function ubahStatus(Request $request) {
        $input = $request->all();
        $user = User::find($input['id']);
        $user->is_active = $input['stat'];
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }
}
