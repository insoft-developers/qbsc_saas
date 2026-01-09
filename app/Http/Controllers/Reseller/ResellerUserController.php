<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ResellerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function user_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::where('referal_code', Auth::guard('reseller')->user()->referal_code);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('comname', function ($row) {
                    return $row->company_name;
                })
                ->addColumn('owner_name', function ($row) {
                    return $row->owner == null ? '' : $row->owner->name ?? '';
                })
                ->addColumn('email', function ($row) {
                    return $row->owner == null ? '' : $row->owner->email ?? '';
                })
                ->addColumn('whatsapp', function ($row) {
                    return $row->owner == null ? '' : $row->owner->whatsapp ?? '';
                })
                ->addColumn('paket_id', function ($row) {
                    return $row->paket == null ? '' : $row->paket->nama_paket ?? '';
                })
                ->addColumn('expired_date', function ($row) {
                    return $row->paket == null ? '' : ($row->expired_date == null ? '': date('d-m-Y', strtotime($row->expired_date)));
                })
                ->addColumn('status_paket', function ($row) {
                    return $row->paket == null ? '' : ($row->is_active == 1 ? '<span class="badge bg-success">Aktif</span>': '<span class="badge bg-danger">Tdk Aktif</span>');
                })
                ->addColumn('register_date', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('profile_image', function ($row) {
                    if (!empty($row->owner->profile_image)) {
                        $url = asset('storage/' . $row->owner->profile_image);
                        return '<a href="' . asset('storage/' . $row->profile_image) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center>tdk ada foto</center>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '';
                })
                ->rawColumns(['action','status_paket','profile_image'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'user';
        return view('reseller.user.user', compact('view'));
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
