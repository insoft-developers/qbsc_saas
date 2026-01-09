<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Pembelian;
use App\Models\Reseller;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerHomeController extends Controller
{
    public function index() {
        $view = 'dashboard';

        $code = Auth::guard('reseller')->user()->referal_code;
        $active = Company::where('referal_code', $code)
            ->orderBy('created_at', 'desc')
            ->get();


        $recent = Company::where('referal_code', $code)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        $mycomid = [];

        foreach($active as $ac) {
            array_push($mycomid, $ac->id);
        }

        $pembelian = Pembelian::whereIn('comid', $mycomid)
            ->where('payment_status', 'PAID');

        $total_subscribe = $pembelian->sum('payment_amount');

        

        $subscriber = Company::whereNotNull('paket_id')
            ->whereNotIn('paket_id', [13, 14])
            ->where('is_active', 1)
            ->where('expired_date', '>=', now()->toDateString())
            ->get();

        $reseller = Reseller::find(Auth::guard('reseller')->user()->id);
        $poin = $reseller->poin_reward ?? 0;

        $withdraw = Withdraw::where('reseller_id', Auth::guard('reseller')->user()->id)
            ->where('payment_status', 'PAID');




        return view('reseller.dashboard', compact('view', 'active', 'subscriber', 'poin', 'reseller','total_subscribe','withdraw', 'recent'));
    }
}
