<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BosUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $users = User::where('company_id', $input['comid'])->with('company:id,company_name')->get();
        return response()->json([
            "success" => true,
            "data" => $users
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
        //
    }

    public function ubahStatus(Request $request) {
        $input = $request->all();
        $user = User::find($input['id']);
        $user->is_active = $input['stat'];
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "sukses"
        ]);
    }
}
