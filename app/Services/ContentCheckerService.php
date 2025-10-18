<?php
namespace App\Services;

use App\Repositories\AppSettingsRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class ContentCheckerService
{
    public function checkForContentUpdate(): void
    {
        $contentVersion = (new AppSettingsRepository())->getContentVersion();
        if ($contentVersion === 0 || $contentVersion === null) {
            Inertia::share([
                'contentVersion' => $contentVersion
            ]);

            return;
        }

        try {
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Avoid HTTP/2 reuse
                ],
            ])->get(
                'https://admin.ilirski-bukvar.rs/api/content/version',
            );
            
            $apiContentVersion = $response->json()['data']['content_version'] ?? null;
            
            if (!empty($apiContentVersion) && $apiContentVersion > $contentVersion) {
                Inertia::share([
                    'contentVersion' => $contentVersion
                ]);
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}

