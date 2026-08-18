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

    /**
     * Queue khusus scanner.
     */

    /**
     * Maksimal waktu job.
     */
    public int $timeout = 3600;

    /**
     * Tidak perlu retry otomatis.
     */
    public int $tries = 1;

    /**
     * Jumlah file yang diproses per batch.
     */
    protected int $batchSize = 2000;


    public function __construct()
    {
        $this->onQueue('scan');
    }


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
        | Scan file fisik
        |--------------------------------------------------------------------------
        */

        $rows = [];

        $now = now();

        $totalFiles = 0;

        $exists = 0;

        $orphan = 0;


        /*
        |--------------------------------------------------------------------------
        | Recursive scanner
        |--------------------------------------------------------------------------
        */

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $folder,
                \FilesystemIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );


        foreach ($iterator as $file) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan file
            |--------------------------------------------------------------------------
            */

            if (!$file->isFile()) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Path relatif
            |--------------------------------------------------------------------------
            */

            $relativePath = 'patroli/' . ltrim(
                str_replace(
                    '\\',
                    '/',
                    str_replace(
                        $folder,
                        '',
                        $file->getPathname()
                    )
                ),
                '/'
            );


            /*
            |--------------------------------------------------------------------------
            | Tambahkan ke batch
            |--------------------------------------------------------------------------
            */

            $rows[] = [
                'file_name' => $file->getFilename(),

                'file_path' => $relativePath,

                'file_size' => $file->getSize(),

                'scanned_at' => $now,

                'created_at' => $now,

                'updated_at' => $now,
            ];


            $totalFiles++;


            /*
            |--------------------------------------------------------------------------
            | Proses setiap 2.000 file
            |--------------------------------------------------------------------------
            */

            if (count($rows) >= $this->batchSize) {

                $result = $this->processBatch(
                    $rows
                );


                $exists += $result['exists'];

                $orphan += $result['orphan'];


                $rows = [];

                gc_collect_cycles();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Proses sisa file
        |--------------------------------------------------------------------------
        */

        if (!empty($rows)) {

            $result = $this->processBatch(
                $rows
            );


            $exists += $result['exists'];

            $orphan += $result['orphan'];


            unset($rows);

            gc_collect_cycles();
        }


        /*
        |--------------------------------------------------------------------------
        | Log
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Scan file patroli selesai',
            [
                'total_files' => $totalFiles,
                'exists' => $exists,
                'orphan' => $orphan,
            ]
        );
    }


    /**
     * Proses satu batch file.
     */
    protected function processBatch(array $rows): array
    {
        if (empty($rows)) {

            return [
                'exists' => 0,
                'orphan' => 0,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil semua path
        |--------------------------------------------------------------------------
        */

        $paths = array_column(
            $rows,
            'file_path'
        );


        /*
        |--------------------------------------------------------------------------
        | Cari data patroli berdasarkan path
        |--------------------------------------------------------------------------
        |
        | Hanya 2.000 file sekali query.
        |
        */

        $patrolis = Patroli::query()
            ->with([
                'company:id,company_name'
            ])
            ->whereIn(
                'photo_path',
                $paths
            )
            ->select([
                'id',
                'comid',
                'photo_path',
            ])
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Buat mapping
        |--------------------------------------------------------------------------
        */

        $databaseFiles = [];


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
            | Kalau duplicate di DB
            |--------------------------------------------------------------------------
            |
            | Ambil data pertama.
            |
            */

            if (!isset($databaseFiles[$path])) {

                $databaseFiles[$path] = [

                    'patroli_id' => $patroli->id,

                    'company_id' => $patroli->comid,

                    'company_name' => optional(
                        $patroli->company
                    )->company_name,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Siapkan insert
        |--------------------------------------------------------------------------
        */

        $insertRows = [];

        $exists = 0;

        $orphan = 0;


        foreach ($rows as $row) {

            $databaseInfo =
                $databaseFiles[$row['file_path']]
                ?? null;


            if ($databaseInfo) {

                $exists++;

                $status = 'exists';

                $companyId =
                    $databaseInfo['company_id'];

                $companyName =
                    $databaseInfo['company_name'];

            } else {

                $orphan++;

                $status = 'orphan';

                $companyId = null;

                $companyName = null;
            }


            $insertRows[] = [

                'file_name' =>
                    $row['file_name'],

                'file_path' =>
                    $row['file_path'],

                'company_id' =>
                    $companyId,

                'company_name' =>
                    $companyName,

                'file_size' =>
                    $row['file_size'],

                'status' =>
                    $status,

                'scanned_at' =>
                    $row['scanned_at'],

                'created_at' =>
                    $row['created_at'],

                'updated_at' =>
                    $row['updated_at'],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Insert database
        |--------------------------------------------------------------------------
        */

        foreach (array_chunk($insertRows, 500) as $chunk) {

            PatroliFileScan::insert(
                $chunk
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Bersihkan memory
        |--------------------------------------------------------------------------
        */

        unset(
            $paths,
            $patrolis,
            $databaseFiles,
            $insertRows
        );

        gc_collect_cycles();


        return [

            'exists' => $exists,

            'orphan' => $orphan,
        ];
    }


    /**
     * Job gagal.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error(
            'ScanPatroliFiles gagal',
            [
                'message' =>
                    $exception->getMessage(),

                'file' =>
                    $exception->getFile(),

                'line' =>
                    $exception->getLine(),
            ]
        );
    }
}