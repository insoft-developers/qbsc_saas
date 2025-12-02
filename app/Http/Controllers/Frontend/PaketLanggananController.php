<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;

class PaketLanggananController extends Controller
{
    use CommonTrait;
    public function index() {
        $view = 'paket';
        $com = Company::find($this->comid());
        return view('frontend.paket.paket', compact('view', 'com'));
    }
}
