<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SatpamController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */

    public function satpam_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Satpam::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company', function ($row) {
                    return '';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/users/edit/' . $row->id . '" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function index()
    {
        $view = 'satpam';
        return view('frontend.satpam.satpam', compact('view'));
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
        dd($input);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
