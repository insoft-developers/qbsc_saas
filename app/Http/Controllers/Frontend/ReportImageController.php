<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'report-image';
        $data = User::find(Auth::user()->id);
        return view('frontend.setting.report_image.index', compact('view','data'));
    }

    public function update(Request $request)
    {
        $input = $request->all();
        User::where('id', Auth::user()->id)->update([
            "export_report_with_image" => $input['report_image']
        ]);

        return response()->json(true);
    }

}
