<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\PaketLangganan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })

                ->addColumn('content', function ($row) {
                    $html = '';
                    $html .= $row->title.'<br>';
                    $html .= '<center><img style="height:210px;" class="img-fluid" src="'.asset('storage/'.$row->image).'"></center><br>';
                    $html .= '<div style="white-space:normal;width:700px;">' . $row->content . '</div>';
                    $html .= date('D, d F Y - H:i:s', strtotime($row->created_at)).' - '. $row->admin->name;
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active', 'content'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'blogs';
        $pakets = PaketLangganan::all();
        return view('admin.blog.index', compact('view', 'pakets'));
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
        $input = $request->all();
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'title' => 'required|string',
            'slug' => 'required|unique:blogs,slug',
            'content' => 'required',
            'is_active' => 'required'
        ]);

        // Simpan foto ke storage
        try {
            $path = null;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('blogs', 'public');
            }

            // Simpan ke database
            $input['image'] = $path;
            $input['created_by'] = Auth::guard('admin')->user()->id;

            Blog::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Buat Blog berhasi.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
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
        $data = Blog::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
                'title' => 'required|string|max:255',
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('blogs', 'slug')->ignore($blog->id),
                ],
                'content' => 'required',
                'is_active' => 'required',
            ]);

            $input = $validated;

            /*
         * Jika ada gambar baru:
         * 1. Simpan gambar baru
         * 2. Hapus gambar lama
         */
            if ($request->hasFile('image')) {
                $newPath = $request->file('image')
                    ->store('blogs', 'public');

                if (!$newPath) {
                    throw new \Exception('Gambar baru gagal disimpan.');
                }

                if (
                    $blog->image &&
                    Storage::disk('public')->exists($blog->image)
                ) {
                    Storage::disk('public')->delete($blog->image);
                }

                $input['image'] = $newPath;
            } else {
                /*
             * Jangan mengubah kolom image jika tidak ada
             * gambar baru yang diunggah.
             */
                unset($input['image']);
            }

            $input['updated_by'] = Auth::guard('admin')->id();

            $blog->update($input);

            return response()->json([
                'success' => true,
                'message' => 'Blog berhasil diperbarui.',
                'data' => $blog->fresh(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Simpan lokasi gambar sebelum data dihapus
            $imagePath = $blog->image;

            // Hapus data blog
            $blog->delete();

            // Hapus gambar dari storage
            if (
                $imagePath &&
                Storage::disk('public')->exists($imagePath)
            ) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Blog berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
