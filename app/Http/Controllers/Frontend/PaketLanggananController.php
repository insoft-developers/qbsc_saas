<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PaketLangganan;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketLanggananController extends Controller
{
    use CommonTrait;
    public function index() {
        $view = 'paket';
        $com = Company::find($this->comid());
        return view('frontend.paket.paket', compact('view', 'com'));
    }

    public function whatsapp_payment(Request $request) {
        $input = $request->all();
        $user = User::with('company')->find(Auth::user()->id);
        $data = PaketLangganan::find($input['id']);
        return response()->json([
            "success" => true,
            "data" => $data,
            "user" => $user
        ]);
    }
}
