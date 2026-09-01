<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\EmergencyList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BosEmergencyListController extends Controller
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

            $data = EmergencyList::where(
                'comid',
                $request->comid
            )
                ->orderBy('name')
                ->get();

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
    // STORE
    // ======================================================

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' =>
                    'required|string|max:255',

                'whatsapp' =>
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

        try {
            $data = EmergencyList::create([
                'name' =>
                    $request->name,

                'whatsapp' =>
                    $request->whatsapp,

                'comid' =>
                    $request->comid,
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'Kontak emergency berhasil ditambahkan.',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage(),
            ], 500);
        }
    }

    // ======================================================
    // SHOW
    // ======================================================

    public function show(
        Request $request,
        $id
    ) {
        $data = EmergencyList::where(
            'id',
            $id
        )
            ->where(
                'comid',
                $request->comid
            )
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Kontak emergency tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // ======================================================
    // UPDATE
    // ======================================================

    public function update(
        Request $request,
        $id
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' =>
                    'required|string|max:255',

                'whatsapp' =>
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

        try {
            $data = EmergencyList::where(
                'id',
                $id
            )
                ->where(
                    'comid',
                    $request->comid
                )
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Kontak emergency tidak ditemukan.',
                ], 404);
            }

            $data->update([
                'name' =>
                    $request->name,

                'whatsapp' =>
                    $request->whatsapp,
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'Kontak emergency berhasil diperbarui.',
                'data' => $data->fresh(),
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

    public function destroy(
        Request $request,
        $id
    ) {
        try {
            $data = EmergencyList::where(
                'id',
                $id
            )
                
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Kontak emergency tidak ditemukan.',
                ], 404);
            }

            $data->delete();

            return response()->json([
                'success' => true,
                'message' =>
                    'Kontak emergency berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage(),
            ], 500);
        }
    }
}