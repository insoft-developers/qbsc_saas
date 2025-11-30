<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class SatpamController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */

    public function satpam_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Satpam::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    // di sini accessor dipanggil
                    $url = $row->face_photo_url;
                    $img = '';
                    $img .= '<center>';
                    if ($row->is_active == 1) {
                        $img .= '<span class="badge bg-success rounded-pill badge-insoft">Aktif</span>';
                    } else {
                        $img .= '<span class="badge bg-danger rounded-pill badge-insoft">Tdk Aktif</span>';
                    }
                    $img .= '</center>';
                    $img .= '<img src="' . $url . '" alt="Foto" width="60" height="60" style="border-radius:50%;">';
                    return $img;
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '': 'disabled'; 
                    $button = '';
                    $button .= '<center>';
                    
                    if ($row->is_active == 1) {
                        $button .= '<button '.$disabled.' onclick="activate(' . $row->id . ', 0)" title="Non Aktifkan" class="me-0 btn btn-insoft btn-danger"><i class="bi bi-x-lg"></i></button>';
                    } else {
                        $button .= '<button '.$disabled.' onclick="activate(' . $row->id . ', 1)" title="Aktifkan" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    }

                    $button .= '<button '.$disabled.' onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';

                    $button .= '<button '.$disabled.' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }

    public function index()
    {
        $view = 'satpam';
        $isOwner = $this->isOwner();
        return view('frontend.satpam.satpam', compact('view', 'isOwner'));
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
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20|unique:satpams,whatsapp',
            'password' => 'required|string|min:6',
        ]);
        $face_url = config('services.face_api.url');
        $response = Http::attach('image', file_get_contents($request->file('foto')), 'face.jpg')->post($face_url.'/encode');

        if (!$response->successful()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal membaca wajah, pastikan wajah terlihat jelas.',
                ],
                400,
            );
        }

        $embedding = $response->json('embedding');

        // Simpan foto ke storage
        $path = $request->file('foto')->store('satpam', 'public');

        // Simpan ke database
        $satpam = Satpam::create([
            'name' => $request->name,
            'badge_id' => $request->badge_id,
            'whatsapp' => $request->whatsapp,
            'password' => bcrypt($request->password),
            'face_photo_path' => $path,
            'comid' => $this->comid(),
            'face_embedding' => json_encode($embedding),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil disimpan.',
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
        $data = Satpam::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $satpam = Satpam::findOrFail($id);

        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:100',
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('satpams', 'whatsapp')->ignore($satpam->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $face_url = config('services.face_api.url');
        // update foto jika ada upload
        if ($request->hasFile('foto')) {
            // hapus foto lama
            if ($satpam->face_photo_path && Storage::disk('public')->exists($satpam->face_photo_path)) {
                Storage::disk('public')->delete($satpam->face_photo_path);
            }

            // simpan foto baru
            $path = $request->file('foto')->store('satpam', 'public');
            $satpam->face_photo_path = $path;

            try {
                // kirim ke API face recognition
                $response = Http::timeout(10)
                    ->attach('image', file_get_contents($request->file('foto')), 'face.jpg')
                    ->post($face_url.'/encode');

                if ($response->successful()) {
                    $satpam->face_embedding = json_encode($response->json('embedding'));
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Gagal membaca wajah dari server AI.',
                        ],
                        400,
                    );
                }
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Server AI tidak merespon.',
                        'error' => $e->getMessage(),
                    ],
                    500,
                );
            }
        }

        // Update data lain
        $satpam->name = $validated['name'];
        $satpam->whatsapp = $validated['whatsapp'];

        if (!empty($validated['password'])) {
            $satpam->password = bcrypt($validated['password']);
        }

        $satpam->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil diperbarui.',
            'data' => $satpam,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $satpam = Satpam::findOrFail($id);

            // Jika ada foto, hapus dari storage
            if ($satpam->face_photo_path && Storage::disk('public')->exists($satpam->face_photo_path)) {
                Storage::disk('public')->delete($satpam->face_photo_path);
            }

            $satpam->delete();

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

    public function activate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:satpams,id',
        ]);

        $satpam = Satpam::findOrFail($request->id);

        // toggle aktif/nonaktif
        $satpam->is_active = $satpam->is_active ? 0 : 1;
        $satpam->save();

        $message = $satpam->is_active ? 'Satpam berhasil diaktifkan.' : 'Satpam berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
