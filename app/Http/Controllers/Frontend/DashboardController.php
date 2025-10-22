<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $view = 'dashboard';
        return view('frontend.dashboard_new', compact('view'));
    }
}
