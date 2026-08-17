<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Google\Client;


class GoogleDriveController extends Controller
{
    public function connect()
    {
        $client = new Client();

        $client->setClientId(
            config('services.google_drive.client_id')
        );

        $client->setClientSecret(
            config('services.google_drive.client_secret')
        );

        $client->setRedirectUri(
            config('services.google_drive.redirect_uri')
        );

        $client->setAccessType('offline');

        $client->setPrompt('consent');

        $client->setScopes([
            'https://www.googleapis.com/auth/drive',
        ]);

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization code tidak ditemukan.',
                'error' => $request->get('error'),
            ], 400);
        }

        $client = new \Google\Client();

        $client->setClientId(
            config('services.google_drive.client_id')
        );

        $client->setClientSecret(
            config('services.google_drive.client_secret')
        );

        $client->setRedirectUri(
            config('services.google_drive.redirect_uri')
        );

        $client->setAccessType('offline');

        $client->setScopes([
            'https://www.googleapis.com/auth/drive',
        ]);

        $token = $client->fetchAccessTokenWithAuthCode(
            $request->get('code')
        );

        if (isset($token['error'])) {
            return response()->json([
                'success' => false,
                'message' => $token['error_description']
                    ?? $token['error'],
            ], 400);
        }

        if (empty($token['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Google tidak memberikan access token.',
            ], 400);
        }

        /*
    |--------------------------------------------------------------------------
    | Simpan token
    |--------------------------------------------------------------------------
    */

        $googleToken = \App\Models\GoogleDriveToken::first();

        $data = [
            'access_token' => encrypt(
                $token['access_token']
            ),

            'expires_at' => now()->addSeconds(
                $token['expires_in'] ?? 3600
            ),
        ];

        /*
    |--------------------------------------------------------------------------
    | Simpan refresh token jika Google memberikannya
    |--------------------------------------------------------------------------
    */

        if (!empty($token['refresh_token'])) {
            $data['refresh_token'] = encrypt(
                $token['refresh_token']
            );
        }

        if ($googleToken) {

            $googleToken->update($data);
        } else {

            \App\Models\GoogleDriveToken::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Google Drive berhasil terhubung.',
            'refresh_token_saved' => !empty($token['refresh_token']),
        ]);
    }

    private function googleClient(): \Google\Client
    {
        $token = \App\Models\GoogleDriveToken::firstOrFail();

        $client = new \Google\Client();

        $client->setClientId(
            config('services.google_drive.client_id')
        );

        $client->setClientSecret(
            config('services.google_drive.client_secret')
        );

        $client->setAccessType('offline');

        $client->setScopes([
            'https://www.googleapis.com/auth/drive',
        ]);

        $accessToken = decrypt($token->access_token);

        $client->setAccessToken([
            'access_token' => $accessToken,
            'expires_at' => $token->expires_at?->timestamp,
        ]);

        if ($client->isAccessTokenExpired()) {

            if (!$token->refresh_token) {
                throw new \Exception(
                    'Refresh token Google Drive tidak tersedia.'
                );
            }

            $refreshToken = decrypt($token->refresh_token);

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


    public function testDrive()
    {
        try {

            $client = $this->googleClient();

            $drive = new \Google\Service\Drive($client);

            $files = $drive->files->listFiles([
                'pageSize' => 10,
                'fields' => 'files(id,name,mimeType)',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Google Drive berhasil diakses.',
                'files' => $files->getFiles(),
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function testPatroliUpload()
    {
        try {

            /*
        |--------------------------------------------------------------------------
        | Ambil satu patroli
        |--------------------------------------------------------------------------
        */

            $patroli = \App\Models\Patroli::whereNotNull('photo_path')
                ->where('id', 89)
                ->firstOrFail();

            /*
        |--------------------------------------------------------------------------
        | Cari perusahaan
        |--------------------------------------------------------------------------
        */

            $company = \App\Models\Company::findOrFail(
                $patroli->comid
            );

            /*
        |--------------------------------------------------------------------------
        | Lokasi file
        |--------------------------------------------------------------------------
        */

            $filePath = storage_path(
                'app/public/' . $patroli->photo_path
            );

            if (!file_exists($filePath)) {

                return response()->json([
                    'success' => false,
                    'message' => 'File foto tidak ditemukan.',
                    'path' => $filePath,
                ], 404);
            }

            /*
        |--------------------------------------------------------------------------
        | Google Client
        |--------------------------------------------------------------------------
        */

            $client = $this->googleClient();

            $drive = new \Google\Service\Drive($client);

            /*
        |--------------------------------------------------------------------------
        | Folder ROOT
        |--------------------------------------------------------------------------
        */

            $rootFolderId = config(
                'services.google_drive.root_folder'
            );

            /*
        |--------------------------------------------------------------------------
        | Folder perusahaan
        |--------------------------------------------------------------------------
        */

            $companyFolder = $this->findOrCreateDriveFolder(
                $drive,
                $company->company_name,
                $rootFolderId
            );

            /*
        |--------------------------------------------------------------------------
        | Folder tahun
        |--------------------------------------------------------------------------
        */

            $yearFolder = $this->findOrCreateDriveFolder(
                $drive,
                $patroli->tanggal->format('Y'),
                $companyFolder->id
            );

            /*
        |--------------------------------------------------------------------------
        | Folder bulan
        |--------------------------------------------------------------------------
        */

            $monthName = $patroli->tanggal->translatedFormat('m - F');

            $monthFolder = $this->findOrCreateDriveFolder(
                $drive,
                $monthName,
                $yearFolder->id
            );

            /*
        |--------------------------------------------------------------------------
        | Upload
        |--------------------------------------------------------------------------
        */

            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => basename($filePath),
                'parents' => [$monthFolder->id],
            ]);

            $uploaded = $drive->files->create(
                $fileMetadata,
                [
                    'data' => file_get_contents($filePath),
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id,name,size,webViewLink',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Foto patroli berhasil diupload.',
                'patroli_id' => $patroli->id,
                'company' => $company->company_name,
                'tanggal' => $patroli->tanggal,
                'local_file' => $filePath,
                'google_drive' => [
                    'id' => $uploaded->id,
                    'name' => $uploaded->name,
                    'size' => $uploaded->size,
                    'url' => $uploaded->webViewLink,
                ],
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }


    private function findOrCreateDriveFolder(
        \Google\Service\Drive $drive,
        string $folderName,
        string $parentId
    ): \Google\Service\Drive\DriveFile {

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

        $result = $drive->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id,name,webViewLink)',
            'pageSize' => 1,
        ]);

        $folders = $result->getFiles();

        if (!empty($folders)) {
            return $folders[0];
        }

        $folderMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId],
        ]);

        return $drive->files->create(
            $folderMetadata,
            [
                'fields' => 'id,name,webViewLink',
            ]
        );
    }
}
