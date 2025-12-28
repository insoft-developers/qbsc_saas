<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenerateKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CommonTrait;
    public function index()
    {
        $level = Auth::user()->level;

        if($level !== 'owner') {
            return redirect()->route('dashboard')->with('error', 'Generate Key hanya bisa dilakukan di User yang berlevel "OWNER" ');
        }
        
        $view = 'generate-key';
        $company = Company::find($this->comid());
        return view('frontend.generate_key.generate_key', compact('view', 'company'));
    }


    public function generate(Request $request) {
        $comid = $this->comid();
        $uniqid = uniqid();
        $waktu = time();
        $text = "QBSC USER AREA";

        $gabung = $text.'-'.$waktu.'-'.$uniqid.'-'.$comid;
        $gabung = sha1($gabung);

        Company::where('id', $comid)->update([
            "user_key_id" => $gabung
        ]);

        return response()->json([
            "success" => true,
            "token" => $gabung
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }


}
