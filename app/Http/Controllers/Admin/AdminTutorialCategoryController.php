<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorialCategory;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Yajra\DataTables\Facades\DataTables;

class AdminTutorialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = TutorialCategory::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img style="width:100px;border-radius:4px;" src="' . asset('storage/' . $row->image) . '">';
                })

                ->addColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at == null ? '' : date('d-m-Y', strtotime($row->created_at));
                })

                ->addColumn('description', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . $row->description . '</div>';
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image', 'is_active', 'description'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'tutorial-category';
        return view('admin.tutorials.kategori.index', compact('view'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        $tutorialCategory = TutorialCategory::findOrFail($id);

        $input = $request->all();

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'category_name' => 'required|string',
            'category_slug' => 'required|unique:tutorial_categories,category_slug,' . $id,
            'description' => 'required',
            'is_active' => 'required',
            'urutan' => 'required|integer'
        ]);

        /*
    |--------------------------------------------------------------------------
    | Upload Image Baru
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Gunakan Intervention Image
            $manager = new ImageManager(new Driver());

            $image = $manager->read($file->getRealPath());

            // Crop + resize menjadi tepat 700 x 450
            $image->cover(700, 450);

            // Folder penyimpanan
            $folder = storage_path('app/public/tutorial_category');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            // Nama file baru
            $filename = time() . '_' . uniqid() . '.jpg';

            $savePath = $folder . '/' . $filename;

            // Simpan JPG kualitas 80%
            $image->toJpeg(80)->save($savePath);

            /*
        |--------------------------------------------------------------------------
        | Hapus gambar lama
        |--------------------------------------------------------------------------
        */

            if ($tutorialCategory->image) {

                $oldPath = storage_path(
                    'app/public/' . $tutorialCategory->image
                );

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Path gambar baru
            $input['image'] = 'tutorial_category/' . $filename;
        } else {

            /*
        |--------------------------------------------------------------------------
        | Tidak Upload Gambar Baru
        |--------------------------------------------------------------------------
        | Pertahankan gambar lama
        |--------------------------------------------------------------------------
        */

            $input['image'] = $tutorialCategory->image;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Data
    |--------------------------------------------------------------------------
    */

        $tutorialCategory->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Tutorial category berhasil diperbarui.'
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
        return TutorialCategory::find($id);
    }

  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
