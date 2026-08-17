<?php

namespace App\Services\GoogleDrive;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class FolderService
{
    protected Drive $drive;

    public function __construct(GoogleDriveService $google)
    {
        $this->drive = $google->drive();
    }

    /**
     * Cari folder berdasarkan nama dan parent.
     */
    public function findFolder(string $folderName, string $parentId): ?string
    {
        $query = sprintf(
            "name='%s' and '%s' in parents and mimeType='application/vnd.google-apps.folder' and trashed=false",
            addslashes($folderName),
            $parentId
        );

        $result = $this->drive->files->listFiles([
            'q' => $query,
            'fields' => 'files(id,name)',
            'pageSize' => 1,
        ]);

        if (count($result->getFiles()) > 0) {
            return $result->getFiles()[0]->getId();
        }

        return null;
    }

    /**
     * Membuat folder baru.
     */
    public function createFolder(string $folderName, string $parentId): string
    {
        $metadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId],
        ]);

        $folder = $this->drive->files->create($metadata, [
            'fields' => 'id,name',
        ]);

        return $folder->getId();
    }

    /**
     * Ambil folder jika sudah ada, jika belum maka buat.
     */
    public function getOrCreateFolder(string $folderName, string $parentId): string
    {
        $folderId = $this->findFolder($folderName, $parentId);

        if ($folderId) {
            return $folderId;
        }

        return $this->createFolder($folderName, $parentId);
    }
}