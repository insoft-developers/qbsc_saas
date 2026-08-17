<?php

namespace App\Jobs;

use App\Models\PatroliArchive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteLocalPatroliPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $archiveId
    ) {
    }

    public function handle(): void
    {
        $archive = PatroliArchive::with('patroli')
            ->find($this->archiveId);

        if (!$archive) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Pengaman 1
        |--------------------------------------------------------------------------
        | Hanya boleh menghapus kalau Google Drive sudah berhasil.
        */

        if ($archive->status !== 'uploaded') {
            Log::warning(
                'Patroli tidak dihapus karena belum uploaded',
                [
                    'archive_id' => $archive->id,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Pengaman 2
        |--------------------------------------------------------------------------
        | Harus mempunyai Google Drive File ID.
        */

        if (!$archive->google_drive_file_id) {
            Log::warning(
                'Patroli tidak dihapus karena Google Drive File ID kosong',
                [
                    'archive_id' => $archive->id,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Pengaman 3
        |--------------------------------------------------------------------------
        | Kalau sudah pernah dihapus, jangan lakukan lagi.
        */

        if ($archive->local_deleted_at) {
            return;
        }

        $patroli = $archive->patroli;

        if (!$patroli) {
            return;
        }

        if (!$patroli->photo_path) {
            return;
        }

        $filePath = storage_path(
            'app/public/' . $patroli->photo_path
        );

        /*
        |--------------------------------------------------------------------------
        | Hapus file lokal
        |--------------------------------------------------------------------------
        */

        if (is_file($filePath)) {

            if (!unlink($filePath)) {

                throw new \RuntimeException(
                    'Gagal menghapus file: ' . $filePath
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Tandai sudah dihapus
        |--------------------------------------------------------------------------
        */

        $archive->update([
            'local_deleted_at' => now(),
        ]);

        Log::info(
            'Foto patroli berhasil dihapus dari server',
            [
                'archive_id' => $archive->id,
                'patroli_id' => $patroli->id,
                'file' => $filePath,
            ]
        );
    }
}