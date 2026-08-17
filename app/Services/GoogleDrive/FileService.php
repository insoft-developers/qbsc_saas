<?php

namespace App\Services\GoogleDrive;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class FileService
{
    protected Drive $drive;

    public function __construct(GoogleDriveService $google)
    {
        $this->drive = $google->drive(); // atau drive() sesuai method Anda
    }

    /**
     * Upload file ke Google Drive
     */
    public function upload(string $localFile, string $folderId): array
    {
        if (!file_exists($localFile)) {
            throw new \Exception("File tidak ditemukan : ".$localFile);
        }

        $metadata = new DriveFile([
            'name' => basename($localFile),
            'parents' => [$folderId],
        ]);

        $mime = mime_content_type($localFile);

        $file = $this->drive->files->create(
            $metadata,
            [
                'data' => file_get_contents($localFile),
                'mimeType' => $mime,
                'uploadType' => 'multipart',
                'fields' => 'id,name,size,webViewLink'
            ]
        );

        return [
            'id' => $file->getId(),
            'name' => $file->getName(),
            'size' => $file->getSize(),
            'url' => $file->getWebViewLink(),
        ];
    }

    /**
     * Hapus file di Google Drive
     */
    public function delete(string $fileId): bool
    {
        $this->drive->files->delete($fileId);

        return true;
    }

    /**
     * Ambil metadata file
     */
    public function get(string $fileId)
    {
        return $this->drive->files->get(
            $fileId,
            [
                'fields' => '*'
            ]
        );
    }

    
}