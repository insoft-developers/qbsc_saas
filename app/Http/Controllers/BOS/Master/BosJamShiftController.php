<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\JamShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BosJamShiftController extends Controller
{
    /**
     * GET DATA
     */
    public function index(Request $request)
    {
        try {
            $comid = $request->comid;

            if (!$comid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company ID wajib diisi.',
                ], 422);
            }

            $data = JamShift::where('comid', $comid)
                ->orderBy('jam_masuk')
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

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'jam_masuk' => 'required|date_format:H:i:s',
                'jam_pulang' => 'required|date_format:H:i:s',

                'jam_masuk_awal' =>
                'nullable|date_format:H:i:s',

                'jam_masuk_akhir' =>
                'nullable|date_format:H:i:s',

                'jam_pulang_awal' =>
                'nullable|date_format:H:i:s',

                'jam_pulang_akhir' =>
                'nullable|date_format:H:i:s',

                'comid' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator
                    ->errors()
                    ->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $jamMasuk =
                Carbon::createFromFormat(
                    'H:i:s',
                    $this->normalizeTime(
                        $request->jam_masuk
                    )
                );

            $jamPulang =
                Carbon::createFromFormat(
                    'H:i:s',
                    $this->normalizeTime(
                        $request->jam_pulang
                    )
                );

            /*
             * Jika kosong:
             *
             * Masuk awal  = masuk - 1 jam
             * Masuk akhir = masuk + 1 jam
             *
             * Pulang awal  = pulang - 1 jam
             * Pulang akhir = pulang + 2 jam
             */

            $jamMasukAwal =
                $request->filled('jam_masuk_awal')
                ? $this->normalizeTime(
                    $request->jam_masuk_awal
                )
                : $jamMasuk
                ->copy()
                ->subHour()
                ->format('H:i:s');

            $jamMasukAkhir =
                $request->filled('jam_masuk_akhir')
                ? $this->normalizeTime(
                    $request->jam_masuk_akhir
                )
                : $jamMasuk
                ->copy()
                ->addHour()
                ->format('H:i:s');

            $jamPulangAwal =
                $request->filled('jam_pulang_awal')
                ? $this->normalizeTime(
                    $request->jam_pulang_awal
                )
                : $jamPulang
                ->copy()
                ->subHour()
                ->format('H:i:s');

            $jamPulangAkhir =
                $request->filled('jam_pulang_akhir')
                ? $this->normalizeTime(
                    $request->jam_pulang_akhir
                )
                : $jamPulang
                ->copy()
                ->addHours(2)
                ->format('H:i:s');

            $data = JamShift::create([
                'name' =>
                $request->name,

                'jam_masuk_awal' =>
                $jamMasukAwal,

                'jam_masuk' =>
                $jamMasuk->format('H:i:s'),

                'jam_masuk_akhir' =>
                $jamMasukAkhir,

                'jam_pulang_awal' =>
                $jamPulangAwal,

                'jam_pulang' =>
                $jamPulang->format('H:i:s'),

                'jam_pulang_akhir' =>
                $jamPulangAkhir,

                'comid' =>
                $request->comid,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Jam shift berhasil ditambahkan.',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' =>
                $e->getMessage(),
            ], 500);
        }
    }

    /**
     * SHOW
     */
    public function show(
        Request $request,
        $id
    ) {
        $data = JamShift::where('id', $id)
            ->where('comid', $request->comid)
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' =>
                'Jam shift tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * UPDATE
     */
    public function update(
        Request $request,
        $id
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' =>
                'required|string|max:255',

                'jam_masuk' =>
                'required|date_format:H:i:s',

                'jam_pulang' =>
                'required|date_format:H:i:s',

                'jam_masuk_awal' =>
                'nullable|date_format:H:i:s',

                'jam_masuk_akhir' =>
                'nullable|date_format:H:i:s',

                'jam_pulang_awal' =>
                'nullable|date_format:H:i:s',

                'jam_pulang_akhir' =>
                'nullable|date_format:H:i:s',

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

        $data = JamShift::where('id', $id)
            ->where('comid', $request->comid)
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' =>
                'Jam shift tidak ditemukan.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            $jamMasuk =
                Carbon::createFromFormat(
                    'H:i:s',
                    $this->normalizeTime(
                        $request->jam_masuk
                    )
                );

            $jamPulang =
                Carbon::createFromFormat(
                    'H:i:s',
                    $this->normalizeTime(
                        $request->jam_pulang
                    )
                );

            $data->update([
                'name' =>
                $request->name,

                'jam_masuk_awal' =>
                $request->filled(
                    'jam_masuk_awal'
                )
                    ? $this->normalizeTime(
                        $request->jam_masuk_awal
                    )
                    : $jamMasuk
                    ->copy()
                    ->subHour()
                    ->format('H:i:s'),

                'jam_masuk' =>
                $jamMasuk->format('H:i:s'),

                'jam_masuk_akhir' =>
                $request->filled(
                    'jam_masuk_akhir'
                )
                    ? $this->normalizeTime(
                        $request->jam_masuk_akhir
                    )
                    : $jamMasuk
                    ->copy()
                    ->addHour()
                    ->format('H:i:s'),

                'jam_pulang_awal' =>
                $request->filled(
                    'jam_pulang_awal'
                )
                    ? $this->normalizeTime(
                        $request->jam_pulang_awal
                    )
                    : $jamPulang
                    ->copy()
                    ->subHour()
                    ->format('H:i:s'),

                'jam_pulang' =>
                $jamPulang->format('H:i:s'),

                'jam_pulang_akhir' =>
                $request->filled(
                    'jam_pulang_akhir'
                )
                    ? $this->normalizeTime(
                        $request->jam_pulang_akhir
                    )
                    : $jamPulang
                    ->copy()
                    ->addHours(2)
                    ->format('H:i:s'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Jam shift berhasil diperbarui.',
                'data' => $data->fresh(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' =>
                $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE
     */
    public function destroy(
        Request $request,
        $id
    ) {
        $data = JamShift::where('id', $id)

            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' =>
                'Jam shift tidak ditemukan.',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' =>
            'Jam shift berhasil dihapus.',
        ]);
    }

    /**
     * NORMALIZE TIME
     *
     * 08:00    -> 08:00:00
     * 08:00:00 -> 08:00:00
     */
    private function normalizeTime(
        string $time
    ): string {
        return strlen($time) === 5
            ? $time . ':00'
            : $time;
    }
}
