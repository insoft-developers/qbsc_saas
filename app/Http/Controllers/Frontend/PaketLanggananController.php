<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketLanggananController extends Controller
{
    use CommonTrait;
    public function index()
    {
        $view = 'paket';
        $com = Company::find($this->comid());
        return view('frontend.paket.paket', compact('view', 'com'));
    }

    public function whatsapp_payment(Request $request)
    {
        $input = $request->all();
        $user = User::with('company')->find(Auth::user()->id);
        $data = PaketLangganan::find($input['id']);
        return response()->json([
            'success' => true,
            'data' => $data,
            'user' => $user,
        ]);
    }


    public function paket_gratis(Request $request)
    {
        $comid = $this->comid();
        $company = Company::find($comid);
        if($company->has_trial == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Paket gratis sudah pernah digunakan',
            ]);
        }

        $paket = PaketLangganan::find($request->id);

        if ($paket) {
            $invoice = time();
            $pembelian = Pembelian::create([
                'invoice' => $invoice,
                'paket_id' => $paket->id,
                'userid' => Auth::user()->id,
                'comid' => $comid,
                'amount' => 0,
                'payment_amount' => 0,
                'payment_status' => 'PAID',
                'payment_with' => 'FREE TRIAL PACKAGE',
                'payment_date' => Carbon::Now(),
                'reference' => $invoice,
            ]);

            if ($pembelian) {
                $expired_date = Carbon::now()->addDays(14)->format('Y-m-d');
                
                $company->paket_id = $paket->id;
                $company->expired_date = $expired_date;
                $company->is_active = 1;
                $company->has_trial = 1;
                $company->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'sukses',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'gagal',
            ]);
        }
    }
}
