<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AbsenLocationController extends Controller
{
    use CommonTrait;
    public function absen_location_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = AbsenLocation::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
        
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);

            // bi bi-trash3
        }
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'absen-location';
        return view('frontend.setting.absen.absen', compact('view'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $data = AbsenLocation::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'location_name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'max_distance' => 'required',
        ]);

        $data = AbsenLocation::find($id);

        // Simpan ke database
        $data->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Data Satpam berhasil disimpan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
