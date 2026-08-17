<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UploadPatroliToGoogleDrive;
use App\Models\Company;
use App\Models\Patroli;
use Illuminate\Http\Request;

class PatroliArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $view = 'patroli-archive';
        $companies = Company::orderBy('company_name')->get();

        $query = Patroli::query()
            ->whereNotNull('photo_path')
            ->where('photo_path', '!=', '');

        if ($request->filled('company_id')) {
            $query->where(
                'comid',
                $request->company_id
            );
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_akhir
            );
        }

        $totalFoto = (clone $query)->count();

        $sudahDiarsipkan = (clone $query)
            ->whereHas('archive', function ($q) {
                $q->where('status', 'uploaded');
            })
            ->count();

        $belumDiarsipkan = $totalFoto - $sudahDiarsipkan;

        /*
        |--------------------------------------------------------------------------
        | Total ukuran file lokal
        |--------------------------------------------------------------------------
        */

        $totalUkuran = 0;

        (clone $query)
            ->select([
                'id',
                'photo_path',
            ])
            ->orderBy('id')
            ->chunkById(500, function ($patrolis) use (&$totalUkuran) {

                foreach ($patrolis as $patroli) {

                    $path = storage_path(
                        'app/public/' . $patroli->photo_path
                    );

                    if (is_file($path)) {
                        $totalUkuran += filesize($path);
                    }
                }
            });

        return view(
            'admin.archive.patroli.index',
            compact(
                'companies',
                'totalFoto',
                'sudahDiarsipkan',
                'belumDiarsipkan',
                'totalUkuran',
                'view'
            )
        );
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


    public function archive(Request $request)
    {
        $validated = $request->validate([
            'company_id' => [
                'required',
                'exists:companies,id',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_akhir' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ]);

        $query = Patroli::query()
            ->whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->where('comid', $validated['company_id'])
            ->whereDate(
                'tanggal',
                '>=',
                $validated['tanggal_mulai']
            )
            ->whereDate(
                'tanggal',
                '<=',
                $validated['tanggal_akhir']
            )
            ->whereDoesntHave(
                'archive',
                function ($q) {
                    $q->where('status', 'uploaded');
                }
            );

        $jumlah = 0;

        $query->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($patrolis) use (&$jumlah) {

                foreach ($patrolis as $patroli) {

                    UploadPatroliToGoogleDrive::dispatch(
                        $patroli->id
                    );

                    $jumlah++;
                }
            });

        return redirect()
            ->route(
                'backadmin.patroli_archive.index',
                $request->query()
            )
            ->with(
                'success',
                "{$jumlah} foto dimasukkan ke antrean Google Drive."
            );
    }
}
