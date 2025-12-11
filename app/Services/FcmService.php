<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;

class FcmService
{
    public function getAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/firebase.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }

    public function sendToTopic($topic, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();

        $projectId = config('services.fcm.project_id');

        $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

        $payload = [
            "message" => [
                "topic" => $topic,   // contoh: comid_10
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ],
                "data" => $data
            ]
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        return $response->json();
    }
}
