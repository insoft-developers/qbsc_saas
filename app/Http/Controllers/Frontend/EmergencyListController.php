<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EmergencyList;
use App\Models\JamShift;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmergencyListController extends Controller
{
    use CommonTrait;   
    public function emergency_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = EmergencyList::where('comid', $comid);
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
                
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
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
        $view = 'emergency';
        return view('frontend.emergency.emergency',compact('view'));
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
            'whatsapp' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            EmergencyList::create($input);
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
        $data = EmergencyList::find($id);
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
            'whatsapp' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $data = EmergencyList::find($id);
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
        return EmergencyList::destroy($id);
    }
}
