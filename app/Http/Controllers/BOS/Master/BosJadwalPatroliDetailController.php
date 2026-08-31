<?php

namespace App\Http\Controllers\BOS\Master;

use App\Http\Controllers\Controller;
use App\Models\JadwalPatroliDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BosJadwalPatroliDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        // =========================================================
        // VALIDASI
        // =========================================================

        $validated = $request->validate([
            'comid'       => ['required', 'integer'],
            'patroli_id'  => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
            'urutan'      => ['required', 'integer', 'min:1'],
            'jam_awal'    => ['required', 'date_format:H:i:s'],
            'jam_akhir'   => ['required', 'date_format:H:i:s'],
        ]);

        try {
            DB::beginTransaction();


            // =====================================================
            // CEK URUTAN
            // =====================================================

            $existingUrutan = JadwalPatroliDetail::where(
                'comid',
                $validated['comid']
            )
                ->where(
                    'patroli_id',
                    $validated['patroli_id']
                )
                ->where(
                    'urutan',
                    $validated['urutan']
                )
                ->exists();

            if ($existingUrutan) {
                throw ValidationException::withMessages([
                    'urutan' =>
                    'Urutan tersebut sudah digunakan.',
                ]);
            }

            // =====================================================
            // SIMPAN
            // =====================================================

            $detail = JadwalPatroliDetail::create([
                'patroli_id'  => $validated['patroli_id'],
                'location_id' => $validated['location_id'],
                'urutan'      => $validated['urutan'],
                'jam_awal'    => $validated['jam_awal'],
                'jam_akhir'   => $validated['jam_akhir'],
                'comid'       => $validated['comid'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lokasi patroli berhasil ditambahkan.',
                'data' => $detail,
            ], 201);
        } catch (ValidationException $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()
                    ->first(),
            ], 422);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data jadwal patroli.',
            ], 500);
        }
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
    public function update(Request $request)
    {
        // =========================================================
        // VALIDASI
        // =========================================================

        $validated = $request->validate([
            'id'          => ['required', 'integer'],
            'comid'       => ['required', 'integer'],
            'patroli_id'  => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
            'urutan'      => ['required', 'integer', 'min:1'],
            'jam_awal'    => ['required', 'date_format:H:i:s'],
            'jam_akhir'   => ['required', 'date_format:H:i:s'],
        ]);

        try {
            DB::beginTransaction();

            // =========================================================
            // AMBIL DATA YANG AKAN DIUPDATE
            // =========================================================

            $detail = JadwalPatroliDetail::where(
                'id',
                $validated['id']
            )
                ->where(
                    'comid',
                    $validated['comid']
                )
                ->where(
                    'patroli_id',
                    $validated['patroli_id']
                )
                ->first();

            if (!$detail) {
                throw ValidationException::withMessages([
                    'id' => 'Data detail jadwal patroli tidak ditemukan.',
                ]);
            }


            // =========================================================
            // URUTAN LAMA DAN BARU
            // =========================================================

            $oldUrutan = (int) $detail->urutan;

            // Jumlah detail dalam jadwal
            $totalData = JadwalPatroliDetail::where(
                'comid',
                $validated['comid']
            )
                ->where(
                    'patroli_id',
                    $validated['patroli_id']
                )
                ->count();

            // Jangan sampai urutan lebih besar dari jumlah data
            $newUrutan = min(
                (int) $validated['urutan'],
                max(1, $totalData)
            );

            // =========================================================
            // REORDER
            // =========================================================

            if ($newUrutan != $oldUrutan) {

                // -----------------------------------------------------
                // PINDAH KE ATAS
                //
                // Contoh:
                // 4 -> 2
                //
                // 2 -> 3
                // 3 -> 4
                // -----------------------------------------------------

                if ($newUrutan < $oldUrutan) {

                    JadwalPatroliDetail::where(
                        'comid',
                        $validated['comid']
                    )
                        ->where(
                            'patroli_id',
                            $validated['patroli_id']
                        )
                        ->where(
                            'id',
                            '!=',
                            $detail->id
                        )
                        ->whereBetween(
                            'urutan',
                            [
                                $newUrutan,
                                $oldUrutan - 1,
                            ]
                        )
                        ->increment('urutan');
                }

                // -----------------------------------------------------
                // PINDAH KE BAWAH
                //
                // Contoh:
                // 2 -> 4
                //
                // 3 -> 2
                // 4 -> 3
                // -----------------------------------------------------

                else {

                    JadwalPatroliDetail::where(
                        'comid',
                        $validated['comid']
                    )
                        ->where(
                            'patroli_id',
                            $validated['patroli_id']
                        )
                        ->where(
                            'id',
                            '!=',
                            $detail->id
                        )
                        ->whereBetween(
                            'urutan',
                            [
                                $oldUrutan + 1,
                                $newUrutan,
                            ]
                        )
                        ->decrement('urutan');
                }
            }

            // =========================================================
            // UPDATE DATA
            // =========================================================

            $detail->update([
                'location_id' => $validated['location_id'],
                'urutan'      => $newUrutan,
                'jam_awal'    => $validated['jam_awal'],
                'jam_akhir'   => $validated['jam_akhir'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lokasi patroli berhasil diperbarui.',
                'data' => $detail->fresh(),
            ]);
        }

        // =========================================================
        // VALIDATION ERROR
        // =========================================================

        catch (ValidationException $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        }

        // =========================================================
        // GENERAL ERROR
        // =========================================================

        catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data jadwal patroli.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // =========================================================
        // VALIDASI
        // =========================================================

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'comid' => ['required', 'integer'],
            'patroli_id' => ['required', 'integer'],
        ]);

        try {
            DB::beginTransaction();

            // =========================================================
            // CARI DATA
            // =========================================================

            $detail = JadwalPatroliDetail::where(
                'id',
                $validated['id']
            )
                ->where(
                    'comid',
                    $validated['comid']
                )
                ->where(
                    'patroli_id',
                    $validated['patroli_id']
                )
                ->first();

            if (!$detail) {
                throw ValidationException::withMessages([
                    'id' => 'Data detail jadwal patroli tidak ditemukan.',
                ]);
            }

            // Simpan urutan sebelum dihapus
            $deletedUrutan = (int) $detail->urutan;

            // =========================================================
            // HAPUS DATA
            // =========================================================

            $detail->delete();

            // =========================================================
            // RAPATKAN URUTAN
            //
            // Contoh:
            // 1
            // 2  <- dihapus
            // 3
            // 4
            //
            // menjadi:
            // 1
            // 2
            // 3
            // =========================================================

            JadwalPatroliDetail::where(
                'comid',
                $validated['comid']
            )
                ->where(
                    'patroli_id',
                    $validated['patroli_id']
                )
                ->where(
                    'urutan',
                    '>',
                    $deletedUrutan
                )
                ->decrement('urutan');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lokasi patroli berhasil dihapus.',
            ]);
        } catch (ValidationException $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {

            DB::rollBack();

          
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jadwal patroli.',
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        $input = $request->all();
        $comid = $input['comid'];
        $id = $input['id'];
        $data = JadwalPatroliDetail::with('jadwal', 'location', 'company')
            ->where('comid', $comid)
            ->where('patroli_id', $id)
            ->orderBy('urutan', 'asc')
            ->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }
}
