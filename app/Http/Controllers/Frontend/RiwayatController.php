<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JamShift;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatController extends Controller
{
    use CommonTrait;   
    public function riwayat_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Pembelian::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                   
                    $button = '';
                    $button .= '<center>';
                    $button .= '<a href="'.url('/print_invoice/'.$row->invoice).'"><button title="Print Invoice" class="btn btn-insoft btn-success"><i class="bi bi-printer"></i></button></a>';
                    $button .= '</center>';
                    return $button;
                })

                ->addColumn('paket_id', function($row){
                    return $row->paket->nama_paket ?? '';
                })
                
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('userid', function ($row) {
                    return $row->user->name ?? '';
                })

                ->addColumn('amount', function($row){
                    return number_format($row->amount);
                })

                ->addColumn('payment_amount', function($row){
                    return $row->payment_amount == null ? '-' : number_format($row->payment_amount);
                })
                ->addColumn('payment_date', function($row){
                    return $row->payment_date == null ? '-' : date('d-m-Y H:i', strtotime($row->payment_date));
                })

                ->addColumn('payment_status', function($row){
                    return $row->payment_status === 'PAID' ? '<span class="badge bg-success">PAID</span>' : '<span class="badge bg-danger">'.$row->payment_status.'</span>';
                })

                
                ->rawColumns(['action','payment_status'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'riwayat';
        $isOwner = $this->isOwner();
        $com = Company::find($this->comid());
        return view('frontend.paket.riwayat',compact('view','isOwner','com'));
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
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }

    public function print($invoice) {
        $view = 'invoice';
        $data = Pembelian::where('invoice', $invoice)->first();
        if($data && $data->comid === $this->comid()) {
            return view('frontend.paket.invoice', compact('view','data'));
        } else {
            abort(404, 'Halaman tidak ditemukan.');
        }
        
    }
}
