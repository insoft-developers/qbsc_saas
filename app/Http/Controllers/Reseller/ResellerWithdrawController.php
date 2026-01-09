<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Pembelian;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ResellerWithdrawController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Withdraw::where('reseller_id', Auth::guard('reseller')->user()->id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('reseller_id', function ($row) {
                    return $row->reseller->name ?? '';
                })
                ->addColumn('rekening', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . $row->rekening . '</div>';
                })
                ->addColumn('keterangan', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . $row->keterangan . '</div>';
                })
                ->addColumn('bukti_transfer', function ($row) {
                    if (!empty($row->bukti_transfer)) {
                        $url = asset('storage/' . $row->bukti_transfer);
                        return '<a href="' . asset('storage/' . $row->bukti_transfer) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center> - </center>';
                    }
                })
                ->addColumn('payment_status', function ($row) {
                    if ($row->payment_status == 'PROPOSED') {
                        return '<span class="badge bg-info">PROPOSED</span>';
                    } elseif ($row->payment_status == 'PAID') {
                        return '<span class="badge bg-success">PAID</span>';
                    } elseif ($row->payment_status == 'PROCESS') {
                        return '<span class="badge bg-warning">PROCESS</span>';
                    } elseif ($row->payment_status == 'REJECTED') {
                        return '<span class="badge bg-danger">REJECTED</span>';
                    }
                })
                ->addColumn('jumlah', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah);
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if ($row->payment_status == 'PROPOSED') {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                        $button .= '</center>';
                        return $button;
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                        $button .= '</center>';
                        return $button;
                    }
                })
                ->rawColumns(['action', 'jumlah', 'bukti_transfer', 'rekening', 'keterangan', 'payment_status'])
                ->make(true);
        }
    }

    public function index()
    {
        $view = 'withdraw';
        return view('reseller.withdraw.withdraw', compact('view'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "jumlah" => "required",
            "keterangan" => "required",
            "rekening" => "required",
        ]);

        $code = Auth::guard('reseller')->user()->referal_code;
        $active = Company::where('referal_code', $code)
            ->orderBy('created_at', 'desc')
            ->get();

        $mycomid = [];

        foreach($active as $ac) {
            array_push($mycomid, $ac->id);
        }

        $pembelian = Pembelian::whereIn('comid', $mycomid)
            ->where('payment_status', 'PAID');

        $total_subscribe = $pembelian->sum('payment_amount');
        $withdraw = Withdraw::where('reseller_id', Auth::guard('reseller')->user()->id)
            ->where('payment_status', 'PAID')->sum('jumlah');
        $sisa = $total_subscribe - $withdraw;

    

        if($request->jumlah > $sisa ) {
             return response()->json([
                "success" => false,
                "message" => 'Jumlah withdraw melebih poin reward Anda'
            ]);
        }


        try {
            Withdraw::create([
                "reseller_id" => Auth::guard('reseller')->user()->id,
                "invoice" => time(),
                "jumlah" => $request->jumlah,
                "rekening" => $request->rekening ?? Auth::guard('reseller')->user()->rekening,
                'payment_status' => "PROPOSED",
                'keterangan' => $request->keterangan
            ]);
            return response()->json([
                "success" => true,
                "message" => "Success"
            ]);
        }catch(\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
