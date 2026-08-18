<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ScanPatroliFiles;
use App\Models\Company;
use App\Models\PatroliFileScan;
use Illuminate\Http\Request;

class PatroliFileScanController extends Controller
{
    public function index(Request $request)
    {
        $query = PatroliFileScan::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('file_name', 'like', "%{$search}%")
                    ->orWhere('file_path', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        if ($request->filled('company_id')) {

            $query->where(
                'company_id',
                $request->company_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalFiles = PatroliFileScan::count();

        $exists = PatroliFileScan::where(
            'status',
            'exists'
        )->count();

        $orphan = PatroliFileScan::where(
            'status',
            'orphan'
        )->count();

        $totalSize = $this->formatBytes(
            PatroliFileScan::sum('file_size')
        );

        $orphanSize = $this->formatBytes(
            PatroliFileScan::where('status', 'orphan')
                ->sum('file_size')
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $files = $query
            ->latest('id')
            ->paginate(500);

        $files->appends($request->query());

        $companies = Company::orderBy(
            'company_name'
        )->get();


        $view = 'scan-patroli';
        return view(
            'admin.archive.scan.index',
            compact(
                'files',
                'companies',
                'totalFiles',
                'exists',
                'orphan',
                'totalSize',
                'orphanSize',
                'view'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scan ulang
    |--------------------------------------------------------------------------
    */

    public function scan()
    {
        ScanPatroliFiles::dispatch();

        return back()->with(
            'success',
            'Scan file patroli telah dimasukkan ke queue.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete file
    |--------------------------------------------------------------------------
    */

    public function destroy(
        PatroliFileScan $patroliFileScan
    ) {

        if ($patroliFileScan->status !== 'orphan') {

            return back()->with(
                'error',
                'File yang masih terdaftar di database tidak dapat dihapus.'
            );
        }

        $path = storage_path(
            'app/public/' .
                $patroliFileScan->file_path
        );

        if (is_file($path)) {
            unlink($path);
        }

        $patroliFileScan->delete();

        return back()->with(
            'success',
            'File berhasil dihapus.'
        );
    }


    private function formatBytes($bytes)
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB'
        ];

        $power = floor(
            log($bytes, 1024)
        );

        $power = min(
            $power,
            count($units) - 1
        );

        return number_format(
            $bytes / pow(1024, $power),
            2
        ) . ' ' . $units[$power];
    }


    public function destroyPage(Request $request)
    {
        $perPage = 500;
        $page = (int) $request->input('page', 1);

        $orphans = PatroliFileScan::where('status', 'orphan')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);

        if ($orphans->isEmpty()) {
            return back()->with(
                'error',
                'Tidak ada file orphan pada halaman ini.'
            );
        }

        $deleted = 0;
        $failed = 0;

        foreach ($orphans as $patroliFileScan) {

            // Pastikan hanya orphan yang diproses
            if ($patroliFileScan->status !== 'orphan') {
                continue;
            }

            $path = storage_path(
                'app/public/' . $patroliFileScan->file_path
            );

            // Hapus file fisik
            if (is_file($path)) {

                if (!unlink($path)) {
                    $failed++;
                    continue;
                }
            }

            // Hapus record dari database
            $patroliFileScan->delete();

            $deleted++;
        }

        return back()->with(
            'success',
            "{$deleted} file orphan berhasil dihapus."
                . ($failed > 0
                    ? " {$failed} file gagal dihapus."
                    : '')
        );
    }
}
