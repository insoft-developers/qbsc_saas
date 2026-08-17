<?php

namespace App\Jobs;

use App\Models\Patroli;
use App\Models\PatroliArchive;
use App\Services\GoogleDrive\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UploadPatroliToGoogleDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        public int $patroliId
    ) {
    }

    public function handle(GoogleDriveService $googleDrive): void
    {
        $patroli = Patroli::find($this->patroliId);

        if (!$patroli) {
            return;
        }

        if (!$patroli->photo_path) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Cek apakah sudah berhasil diarsipkan
        |--------------------------------------------------------------------------
        */

        $archive = PatroliArchive::firstOrCreate(
            [
                'patroli_id' => $patroli->id,
            ],
            [
                'company_id' => $patroli->comid,
                'tanggal' => $patroli->tanggal,
                'original_path' => $patroli->photo_path,
                'status' => 'pending',
            ]
        );

        if ($archive->status === 'uploaded') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Lokasi file
        |--------------------------------------------------------------------------
        */

        $filePath = storage_path(
            'app/public/' . $patroli->photo_path
        );

        if (!is_file($filePath)) {

            $archive->update([
                'status' => 'failed',
                'error_message' => 'File tidak ditemukan: ' . $filePath,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Status uploading
        |--------------------------------------------------------------------------
        */

        $archive->update([
            'status' => 'uploading',
            'error_message' => null,
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            $company = $patroli->company;

            if (!$company) {
                throw new \Exception(
                    'Company tidak ditemukan untuk patroli ID ' .
                    $patroli->id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Google Drive folder
            |--------------------------------------------------------------------------
            */

            $companyFolder = $googleDrive->findOrCreateFolder(
                $company->company_name,
                config('services.google_drive.root_folder')
            );

            $yearFolder = $googleDrive->findOrCreateFolder(
                $patroli->tanggal->format('Y'),
                $companyFolder->id
            );

            $monthFolder = $googleDrive->findOrCreateFolder(
                $patroli->tanggal->format('m') .
                ' - ' .
                $patroli->tanggal->translatedFormat('F'),
                $yearFolder->id
            );

            /*
            |--------------------------------------------------------------------------
            | Upload
            |--------------------------------------------------------------------------
            */

            $uploaded = $googleDrive->uploadFile(
                $filePath,
                basename($filePath),
                $monthFolder->id
            );

            /*
            |--------------------------------------------------------------------------
            | Simpan hasil
            |--------------------------------------------------------------------------
            */

            $archive->update([
                'google_drive_file_id' => $uploaded->id,
                'google_drive_folder_id' => $monthFolder->id,
                'file_size' => filesize($filePath),
                'status' => 'uploaded',
                'uploaded_at' => now(),
                'error_message' => null,
            ]);

            Log::info(
                'Patroli berhasil diarsipkan ke Google Drive.',
                [
                    'patroli_id' => $patroli->id,
                    'drive_file_id' => $uploaded->id,
                ]
            );

        } catch (\Throwable $e) {

            $archive->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error(
                'Upload patroli ke Google Drive gagal.',
                [
                    'patroli_id' => $patroli->id,
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}