<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\UserArea;
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
            $data = User::where('company_id', $comid)->with('company:id,company_name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })

                ->addColumn('is_area', function ($row) {
                    return $row->is_area === 1 ? '<center><span class="badge bg-success rounded-pill">Ya</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
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
                        if ($row->is_active == 1) {
                            $button .= '<button onclick="activate(' . $row->id . ', 0)" title="Non Aktifkan" class="me-0 btn btn-insoft btn-danger"><i class="bi bi-x-lg"></i></button>';
                        } else {
                            $button .= '<button onclick="activate(' . $row->id . ', 1)" title="Aktifkan" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                        }
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        if ($row->level == 'owner') {
                            $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                        } else {
                            $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                        }

                        if ($row->is_area == 1) {
                            $button .= '<button style="margin-left:2px;" onclick="areaData(this,' . $row->id . ')" title="Setting User Area" data-name="' . $row->name . '" class="me-0 btn btn-insoft btn-info"><i class="bi bi-people"></i></button>';
                        } else {
                            $button .= '<button disabled style="margin-left:2px;" title="Setting User Area" class="me-0 btn btn-insoft btn-info"><i class="bi bi-people"></i></button>';
                        }
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active', 'profile_image', 'is_area'])
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
        ]);

        $paket = $this->what_paket($this->comid());
        $max = $paket['jumlah_user_admin'];

        $jumlah_user = User::where('company_id', $this->comid())->where('is_active', 1)->count();
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
            'company_id' => $this->comid(),
            'whatsapp' => $request->whatsapp,
            'level' => 'user',
            'profile_image' => $path,
            'is_area' => $request->is_area,
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
        ]);

        $paket = $this->what_paket($this->comid());
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
            'is_area' => $request->is_area,
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

    public function activate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $data = User::findOrFail($request->id);
        if ($data->is_active !== 1) {
            $paket = $this->what_paket($this->comid());
            $max = $paket['jumlah_user_admin'];

            $jumlah_user_admin = User::where('company_id', $this->comid())->where('is_active', 1)->count();
            if ($jumlah_user_admin >= $max) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah user admin sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah user !!',
                ]);
            }
        }

        // toggle aktif/nonaktif
        $data->is_active = $data->is_active ? 0 : 1;
        $data->save();

        $message = $data->is_active ? 'Data berhasil diaktifkan.' : 'Data berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function tambah_user_area(Request $request)
    {
        $request->validate([
            'user_id_area' => 'required',
            'user_key_id' => 'required',
        ]);

        $cek = UserArea::where('user_key_id', $request->user_key_id)->where('userid', $request->user_id_area)->count();
        if ($cek > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Data ini sudah pernah ditambahkan',
            ]);
        }

        try {
            $company = Company::where('user_key_id', $request->user_key_id)->first();
            if ($company) {
                $owner_query = User::where('company_id', $company->id)->where('level', 'owner')->first();
                if ($owner_query) {
                    $owner_id = $owner_query->id;
                } else {
                    $owner_id = -1;
                }
                UserArea::create([
                    'userid' => $request->user_id_area,
                    'comid' => $this->comid(),
                    'monitoring_userid' => $owner_id,
                    'monitoring_comid' => $company->id,
                    'user_key_id' => $request->user_key_id,
                    'is_active' => 1,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Sukses Tamba Data',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function tampilkan_area_table(Request $request)
    {
        $userid = $request->userid;
        $data = UserArea::with('user:id,name', 'company:id,company_name', 'user_monitoring:id,name', 'company_monitoring:id,company_name')->where('userid', $userid)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function area_activate(Request $request)
    {
        UserArea::where('id', $request->id)->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'data' => 'Status berhasil diupdate',
        ]);
    }

    public function hapus_user_area(Request $request)
    {
        return UserArea::destroy($request->id);
    }
}
