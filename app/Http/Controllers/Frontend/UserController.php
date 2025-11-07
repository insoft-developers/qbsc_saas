<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use CommonTrait;
    public function user_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = User::where('company_id', $comid)->whereNot('level', 'owner')->with('company:id,company_name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })
                ->addColumn('company_id', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->addColumn('profile_image', function ($row) {
                    if (!empty($row->profile_image)) {
                        $url = asset('storage/' . $row->profile_image);
                        return '<a href="' . asset('storage/' . $row->profile_image) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center>-</center>';
                    }
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if (Auth::user()->level == 'owner') {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active', 'profile_image'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'user';
        return view('frontend.user.user', compact('view'));
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
            'is_active' => 'required',
        ]);

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
            'is_active' => $request->is_active,
            'company_id' => $this->comid(),
            'whatsapp' => $request->whatsapp,
            'level' => 'user',
            'profile_image' => $path,
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
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp,' . $id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'required',
        ]);

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
            'is_active' => $request->is_active,
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
        return User::destroy($id);
    }
}
