<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResellerHomeController extends Controller
{
    public function index() {
        $view = 'dashboard';
        return view('reseller.dashboard', compact('view'));
    }
}
