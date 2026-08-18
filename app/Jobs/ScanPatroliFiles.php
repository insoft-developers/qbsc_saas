<?php

namespace App\Jobs;

use App\Models\Patroli;
use App\Models\PatroliFileScan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScanPatroliFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;

    public int $tries = 1;

    public function handle(): void
    {
        $folder = storage_path('app/public/patroli');

        if (!is_dir($folder)) {
            Log::warning(
                'Folder patroli tidak ditemukan',
                [
                    'folder' => $folder,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus hasil scan sebelumnya
        |--------------------------------------------------------------------------
        */

        PatroliFileScan::truncate();

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh photo_path dari database
        |--------------------------------------------------------------------------
        |
        | Key array = photo_path
        | Value = informasi perusahaan
        |
        */

        $databaseFiles = [];

        Patroli::query()
            ->with([
                'company:id,company_name'
            ])
            ->whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->select([
                'id',
                'comid',
                'photo_path',
            ])
            ->chunkById(5000, function ($patrolis) use (&$databaseFiles) {

                foreach ($patrolis as $patroli) {

                    $path = ltrim(
                        str_replace(
                            '\\',
                            '/',
                            $patroli->photo_path
                        ),
                        '/'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Kalau photo_path duplicate di DB
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($databaseFiles[$path])) {

                        $databaseFiles[$path] = [
                            'company_id' => $patroli->comid,
                            'company_name' => optional(
                                $patroli->company
                            )->company_name,
                        ];
                    }
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Scan file fisik
        |--------------------------------------------------------------------------
        */

        $now = now();

        $rows = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $folder,
                \FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {

            if (!$file->isFile()) {
                continue;
            }

            $relativePath = 'patroli/' . ltrim(
                str_replace(
                    '\\',
                    '/',
                    str_replace($folder, '', $file->getPathname())
                ),
                '/'
            );

            $databaseInfo = $databaseFiles[$relativePath] ?? null;

            $rows[] = [
                'file_name' => $file->getFilename(),

                'file_path' => $relativePath,

                'company_id' => $databaseInfo['company_id']
                    ?? null,

                'company_name' => $databaseInfo['company_name']
                    ?? null,

                'file_size' => $file->getSize(),

                'status' => $databaseInfo
                    ? 'exists'
                    : 'orphan',

                'scanned_at' => $now,

                'created_at' => $now,

                'updated_at' => $now,
            ];

            if (count($rows) >= 1000) {

                PatroliFileScan::insert($rows);

                $rows = [];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sisa data
        |--------------------------------------------------------------------------
        */

        if (!empty($rows)) {
            PatroliFileScan::insert($rows);
        }

        Log::info(
            'Scan file patroli selesai'
        );
    }
}
