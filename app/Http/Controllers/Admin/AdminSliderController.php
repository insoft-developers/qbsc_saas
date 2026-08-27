<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetPage;
use App\Models\Company;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Models\MainSlider;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\Satpam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class AdminSliderController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = MainSlider::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img style="width:50px;border-radius:4px;" src="' . asset('storage/' . $row->image) . '">';
                })

                ->addColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at == null ? '' : date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('comid', function ($row) {

                    if (empty($row->comid)) {
                        return '<span class="badge bg-success">
                    Semua Perusahaan
                </span>';
                    }

                    $companyIds = is_array($row->comid)
                        ? $row->comid
                        : json_decode($row->comid, true);

                    $companies = Company::whereIn('id', $companyIds)->get();

                    if ($companies->isEmpty()) {
                        return '<span class="badge bg-secondary">
                    Tidak ada perusahaan
                </span>';
                    }

                    $html = '';

                    foreach ($companies as $company) {
                        $html .= '<span class="badge bg-light text-dark border me-1 mb-1">'
                            . e($company->company_name)
                            . '</span>';
                    }

                    return '<div style="white-space:normal;width:200px;">'.$html.'</div>';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image', 'is_active','comid'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'slider';
        $companies = Company::where('is_active', 1)->orderBy('company_name')->get();
        return view('admin.sliders.slider', compact('view', 'companies'));
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
        // Debug sementara
        // dd($request->all());

        $validated = $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'content' => 'nullable',
            'is_active' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'comid' => 'nullable|array',
            'comid.*' => 'integer',
        ]);

        $path = null;

        if ($request->hasFile('image')) {

            $manager = new ImageManager(
                new Driver()
            );

            $image = $manager->read(
                $request->file('image')->getRealPath()
            );

            // Crop menjadi tepat 1740 x 904
            $image->cover(1740, 904);

            $filename = time() . '_' . uniqid() . '.jpg';

            $path = 'sliders/' . $filename;

            Storage::disk('public')->put(
                $path,
                $image->toJpeg(90)
            );
        }

        $validated['image'] = $path;

        // Jika tidak memilih perusahaan = semua perusahaan
        $validated['comid'] = !empty($validated['comid'])
            ? array_values($validated['comid'])
            : null;

        MainSlider::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
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
        return MainSlider::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = MainSlider::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'content' => 'nullable',
            'is_active' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'comid' => 'nullable|array',
            'comid.*' => 'integer',
        ]);

        // =====================================
        // GAMBAR
        // =====================================

        $path = $data->image;

        if ($request->hasFile('image')) {

            $manager = new ImageManager(
                new Driver()
            );

            $image = $manager->read(
                $request->file('image')->getRealPath()
            );

            // Crop + resize menjadi 1740 x 904
            $image->cover(1740, 904);

            // Nama file baru
            $filename = time() . '_' . uniqid() . '.jpg';

            $newPath = 'sliders/' . $filename;

            // Simpan gambar baru
            Storage::disk('public')->put(
                $newPath,
                $image->toJpeg(90)
            );

            // Hapus gambar lama
            if (
                $data->image &&
                Storage::disk('public')->exists($data->image)
            ) {
                Storage::disk('public')->delete($data->image);
            }

            $path = $newPath;
        }

        $validated['image'] = $path;

        // =====================================
        // TARGET PERUSAHAAN
        // =====================================

        // Tidak memilih perusahaan = semua perusahaan
        $validated['comid'] = !empty($validated['comid'])
            ? array_values($validated['comid'])
            : null;

        // =====================================
        // UPDATE DATABASE
        // =====================================

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = MainSlider::findOrFail($id);

        // Hapus file gambar
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        // Hapus data database
        $slider->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slider berhasil dihapus',
        ]);
    }
}
