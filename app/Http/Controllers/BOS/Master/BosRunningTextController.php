<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BosRunningTextController extends Controller
{
    // ======================================================
    // GET
    // ======================================================

    public function index(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'comid' => 'required|integer',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                    $validator->errors()->first(),
                ], 422);
            }

            $data = RunningText::where(
                'comid',
                $request->comid
            )->first();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ======================================================
    // STORE / UPDATE
    // ======================================================

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'text' =>
                    'required|string|max:255',

                    'comid' =>
                    'required|integer',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                    $validator->errors()->first(),
                    'errors' =>
                    $validator->errors(),
                ], 422);
            }

            $data = RunningText::updateOrCreate(
                [
                    'comid' =>
                    $request->comid,
                ],
                [
                    'text' =>
                    $request->text,
                ]
            );

            return response()->json([
                'success' => true,
                'message' =>
                $data->wasRecentlyCreated
                    ? 'Running text berhasil disimpan.'
                    : 'Running text berhasil diperbarui.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' =>
                $e->getMessage(),
            ], 500);
        }
    }

    // ======================================================
    // DELETE
    // ======================================================

    public function destroy(Request $request,$id) {}
}
