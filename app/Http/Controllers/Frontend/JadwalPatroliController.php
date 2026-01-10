<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JadwalPatroli;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JadwalPatroliController extends Controller
{
    use CommonTrait;   
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = JadwalPatroli::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('description', function($row){
                    return '<div style="white-space:normal;width:150px;">'.$row->description.'</div>';
                })
                ->addColumn('is_active', function($row){
                    return $row->is_active == 1 ? '<span class="badge bg-success">Aktif</span>':'<span class="badge bg-danger">Tidak Aktif</span>';
                })
                ->addColumn('action', function ($row) {
                    
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '<a href="'.url('/jadwal_patroli_detail/'.$row->id).'"><button style="margin-left:2px;" onclick="patroliDetail(this,' . $row->id . ')" title="Setting Jadwal Patroli" data-name="' . $row->name . '" class="me-0 btn btn-insoft btn-info"><i class="bi bi-gear"></i></button></a>';
                    $button .= '</center>';
                    return $button;
                })
                
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                
                ->rawColumns(['action','description','is_active'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'jadwal-patroli';
        return view('frontend.setting.jadwal_patroli.jadwal_patroli',compact('view'));
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
            'name' => 'required|max:100|min:3',
            'is_active' => 'required',
            'description' => 'nullable',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);

            if($request->is_active == 1) {
                JadwalPatroli::where('comid', $this->comid())
                    ->update(['is_active'=> 0]);
            }


            JadwalPatroli::create($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
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
        $data = JadwalPatroli::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'name' => 'required|max:100|min:3',
            'description' => 'nullable',
            'is_active' => 'required'
        ]);

        // Simpan ke database
        try {

            if($request->is_active == 1) {
                JadwalPatroli::where('comid', $this->comid())
                    ->update(['is_active'=> 0]);
            }

            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $data = JadwalPatroli::find($id);
            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $count = JadwalPatroli::where('comid', $this->comid())->count();
        if($count == 1) {
            return response()->json([
                "success" => false,
                "message" => "Harus ada 1 jadwal patroli aktif"
            ]);
        } 
        
        JadwalPatroli::destroy($id);
        return response()->json([
                "success" => true,
                "message" => "success"
        ]);
    }
}
