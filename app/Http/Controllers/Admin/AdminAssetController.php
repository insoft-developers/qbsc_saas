<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetPage;
use App\Models\Company;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\Satpam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminAssetController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = AssetPage::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return '<img style="width:50px;" src="' . asset('images/' . $row->icon) . '">';
                })
                ->addColumn('asset_description', function ($row) {
                    return '<div style="white-space:normal;width:150px;">' . $row->asset_description . '</div>';
                })
                ->addColumn('android_link', function ($row) {
                    return '<div style="white-space:normal;width:250px;">' . $row->android_link . '</div>';
                })
                ->addColumn('ios_link', function ($row) {
                    return '<div style="white-space:normal;width:250px;">' . $row->ios_link . '</div>';
                })
                ->addColumn('updated_at', function ($row) {
                    return date('d-m-Y', strtotime($row->updated_at));
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'icon', 'asset_description', 'android_link', 'ios_link'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'asset';
        $pakets = PaketLangganan::all();
        $users = User::with('company:id,company_name')->where('level', 'owner')->get();
        return view('admin.assets.asset', compact('view', 'pakets', 'users'));
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
            'icon' => 'required',
            'asset_name' => 'required',
            'asset_description' => 'required',
            'android_link' => 'nullable',
            'ios_link' => 'nullable',
        ]);

        $path = null;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');

            // buat nama unik biar tidak bentrok
            $filename = time() . '_' . $file->getClientOriginalName();

            // pindahkan ke public/images
            $file->move(public_path('images'), $filename);

            // simpan ke database
            $path = $filename;
        }

        $input['icon'] = $path;
        $input['copy_number'] = 0;
        AssetPage::create($input);
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
    public function edit(string $id) {
        return AssetPage::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $input = $request->all();
        $data = AssetPage::find($id);
        $validated = $request->validate([
            'icon' => 'nullable',
            'asset_name' => 'required',
            'asset_description' => 'required',
            'android_link' => 'nullable',
            'ios_link' => 'nullable',
        ]);

        $path = $data->icon;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');

            // buat nama unik biar tidak bentrok
            $filename = time() . '_' . $file->getClientOriginalName();

            // pindahkan ke public/images
            $file->move(public_path('images'), $filename);

            // simpan ke database
            $path = $filename;
        }

        $input['icon'] = $path;
        $input['copy_number'] = 0;
        $input['updated_at'] = Carbon::now();
        
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
        return AssetPage::destroy($id);
    }

    
}
