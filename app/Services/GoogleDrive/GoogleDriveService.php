<?php

namespace App\Services\GoogleDrive;


use App\Models\GoogleDriveToken;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected Client $client;

    protected Drive $drive;

    public function __construct()
    {
        $this->client = $this->createClient();

        $this->drive = new Drive($this->client);
    }

    private function createClient(): Client
    {
        $token = GoogleDriveToken::firstOrFail();

        $client = new Client();

        $client->setClientId(
            config('services.google_drive.client_id')
        );

        $client->setClientSecret(
            config('services.google_drive.client_secret')
        );

        $client->setAccessType('offline');

        $client->setScopes([
            Drive::DRIVE,
        ]);

        $client->setAccessToken([
            'access_token' => decrypt(
                $token->access_token
            ),
            'expires_at' => $token->expires_at?->timestamp,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Refresh otomatis
        |--------------------------------------------------------------------------
        */

        if ($client->isAccessTokenExpired()) {

            if (!$token->refresh_token) {
                throw new \Exception(
                    'Refresh token Google Drive tidak tersedia.'
                );
            }

            $refreshToken = decrypt(
                $token->refresh_token
            );

            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $refreshToken
            );

            if (isset($newToken['error'])) {
                throw new \Exception(
                    $newToken['error_description']
                    ?? $newToken['error']
                );
            }

            $client->setAccessToken($newToken);

            $token->update([
                'access_token' => encrypt(
                    $newToken['access_token']
                ),
                'expires_at' => now()->addSeconds(
                    $newToken['expires_in'] ?? 3600
                ),
            ]);
        }

        return $client;
    }

    public function findOrCreateFolder(
        string $folderName,
        string $parentId
    ): DriveFile {

        $escapedName = str_replace(
            "'",
            "\\'",
            $folderName
        );

        $query = sprintf(
            "name = '%s'
            and mimeType = 'application/vnd.google-apps.folder'
            and '%s' in parents
            and trashed = false",
            $escapedName,
            $parentId
        );

        $result = $this->drive->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id,name,webViewLink)',
            'pageSize' => 1,
        ]);

        $folders = $result->getFiles();

        if (!empty($folders)) {
            return $folders[0];
        }

        $metadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId],
        ]);

        return $this->drive->files->create(
            $metadata,
            [
                'fields' => 'id,name,webViewLink',
            ]
        );
    }

    public function uploadFile(
        string $filePath,
        string $fileName,
        string $folderId
    ): DriveFile {

        $metadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId],
        ]);

        $mimeType = mime_content_type($filePath);

        /*
        |--------------------------------------------------------------------------
        | Multipart upload
        |--------------------------------------------------------------------------
        */

        return $this->drive->files->create(
            $metadata,
            [
                'data' => file_get_contents($filePath),
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id,name,size,mimeType,webViewLink',
            ]
        );
    }
}