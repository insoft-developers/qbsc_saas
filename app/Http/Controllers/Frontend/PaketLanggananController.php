<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaketLanggananController extends Controller
{
    public function index() {
        $view = 'paket';
        return view('frontend.paket.paket', compact('view'));
    }
}
