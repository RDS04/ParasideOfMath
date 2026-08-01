<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FirebaseService
{
    /**
     * Kirim notifikasi FCM ke seluruh admin atau token terdaftar.
     */
    public function sendToAdmins($title, $message, $link = null)
    {
        // 1. Ambil semua token FCM milik Admin (role = admin di table users)
        $tokens = DB::table('fcm_tokens')
            ->join('users', 'fcm_tokens.user_id', '=', 'users.id')
            ->where('users.role', 'admin')
            ->pluck('fcm_tokens.token')
            ->toArray();

        if (empty($tokens)) {
            Log::info("FCM: Tidak ada token Admin terdaftar di database.");
        }

        // 2. Kirim notifikasi ke setiap token
        foreach ($tokens as $token) {
            $this->sendNotification($token, $title, $message, $link);
        }
    }

    /**
     * Kirim notifikasi ke single token FCM.
     */
    public function sendNotification($token, $title, $message, $link = null)
    {
        $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', ''));

        // Jika kredensial Firebase tidak dikonfigurasi atau file tidak ada, catat di log saja (mode fallback)
        if (empty(env('FIREBASE_CREDENTIALS')) || !file_exists($credentialsPath)) {
            Log::info("FCM (FALLBACK LOG): Token: $token | Title: $title | Message: $message | Link: $link");
            return false;
        }

        try {
            $accessToken = $this->getAccessToken($credentialsPath);
            if (!$accessToken) {
                Log::error("FCM: Gagal mendapatkan OAuth2 Access Token dari Google API.");
                return false;
            }

            $jsonCredentials = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $jsonCredentials['project_id'] ?? null;

            if (!$projectId) {
                Log::error("FCM: Project ID tidak ditemukan di kredensial JSON.");
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $message,
                    ],
                    'data' => [
                        'click_action' => $link ?? url('/'),
                        'link' => $link ?? url('/'),
                    ],
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error("FCM Send Error Code $httpCode. Response: " . $response);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("FCM Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buat JWT & Tukarkan ke Google OAuth2 untuk Access Token.
     */
    private function getAccessToken($credentialsPath)
    {
        $json = json_decode(file_get_contents($credentialsPath), true);
        if (!isset($json['private_key']) || !isset($json['client_email'])) {
            return null;
        }

        $privateKey = $json['private_key'];
        $clientEmail = $json['client_email'];

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = '';
        if (!openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, 'SHA256')) {
            return null;
        }
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        $responseDecoded = json_decode($response, true);
        return $responseDecoded['access_token'] ?? null;
    }
}
