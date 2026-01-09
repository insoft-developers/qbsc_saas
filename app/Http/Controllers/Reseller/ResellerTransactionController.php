<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ResellerTransactionController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $id = Company::where('referal_code', Auth::guard('reseller')->user()->referal_code)
                ->pluck('id')
                ->toArray();
            
            
            $data = Pembelian::whereIn('comid', $id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('comname', function($row){
                    return $row->company->company_name ?? '';
                })
                ->addColumn('paket_id', function($row){
                    return $row->paket == null ? '' : ($row->paket->nama_paket ?? '');
                })
                ->addColumn('userid', function($row){
                    return $row->user == null ? '' : ($row->user->name ?? '');
                })
                ->addColumn('amount', function($row){
                    return $row->amount == null ? '0' : number_format($row->amount); 
                })
                ->addColumn('payment_status', function($row){
                    return $row->payment_status == null ? '' : ($row->payment_status == 'PAID' ? '<span class="badge bg-success">PAID</span>' : '<span class="badge bg-danger">'.$row->payment_status.'</span>');
                })
                ->addColumn('action', function($row){
                    return '';
                })
                ->addColumn('description', function($row){
                    return $row->payment_with;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->rawColumns(['action','payment_status'])
                ->make(true);
        }
    }
    
    
    public function index() {
        $view = 'transaction';
        return view('reseller.transaction.transaction', compact('view'));
    }
}
