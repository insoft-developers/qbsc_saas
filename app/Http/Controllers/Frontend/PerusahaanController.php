<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Company;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    use CommonTrait;
    public function perusahaan_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::where('id', Auth::user()->company_id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company_address',function($data){
                    return '<div style="white-space:normal;width:200px;">'.$data->company_address.'</div>';
                })
                ->addColumn('is_peternakan', function($data){
                    return $data->is_peternakan == 1 ? 'Peternakan':'Perusahaan Lain';
                })
                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '': 'disabled'; 
                    $button = '';
                    $button .= '<center>';
        
                    $button .= '<button '.$disabled.' onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action','company_address'])
                ->make(true);

            // bi bi-trash3
        }
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'perusahaan';
        return view('frontend.setting.perusahaan.perusahaan', compact('view'));
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
        $data =Company::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'company_name' => 'required',
           
        ]);

        $data = Company::find($id);

        // Simpan ke database
        $data->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
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
