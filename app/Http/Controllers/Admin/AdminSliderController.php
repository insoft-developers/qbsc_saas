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
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image', 'is_active'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'slider';
        return view('admin.sliders.slider', compact('view'));
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
            'title' => 'required',
            'is_active' => 'required',
            'image' => 'required',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
             $path = $request->file('image')->store('sliders', 'public');
        }

        $input['image'] = $path;
        MainSlider::create($input);
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
        $data = MainSlider::find($id);
        $input = $request->all();
        $validated = $request->validate([
            'title' => 'required',
            'is_active' => 'required',
            'image' => 'required',
        ]);

        $path = $data->image;
        if ($request->hasFile('image')) {
             $path = $request->file('image')->store('sliders', 'public');
        }

        $input['image'] = $path;
        $data->update($input);
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
        return MainSlider::destroy($id);
    }
}
