<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KandangController extends Controller
{
    use CommonTrait;   
    public function kandang_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Kandang::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->addColumn('is_empty', function ($row) {
                    return $row->is_empty === 1 ? '<center><span class="badge bg-danger rounded-pill">Kosong</span></center>' : '<center><span class="badge bg-success rounded-pill">Berisi</span></center>';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('pic', function ($row) {
                    return $row->pics->name ?? '';
                })
                ->rawColumns(['action','is_empty'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'kandang';
        $users = User::where('company_id', $this->comid())->get();
        return view('frontend.farm.kandang.kandang',compact('view','users'));
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
            'code' => 'required',
            'name' => 'required|max:100|min:3',
            'std_temp' => 'required',
            'fan_amount' => 'required',
            'is_empty' => 'required',
            'pic' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            Kandang::create($input);
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
        $data = Kandang::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'code' => 'required',
            'name' => 'required|max:100|min:3',
            'std_temp' => 'required',
            'fan_amount' => 'required',
            'is_empty' => 'required',
            'pic' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $data = Kandang::find($id);
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
        //
    }
}
