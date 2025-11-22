<?php
namespace App\Services;

use App\Repositories\AppSettingsRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;
use App\Models\Series;
use File;

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

    public function checkForMissingContentThumbnails($series): void
    {
        foreach ($series as $serie) {
            $thumbRel = (string) $serie->thumbnail;
            // Normalize to forward slashes
            $thumbRel = str_replace('\\', '/', $thumbRel);
            // Remove any leading slashes (forward or backward)
            $thumbRel = ltrim($thumbRel, '/\\');
            // Get the absolute filesystem path
            $thumbAbs = public_path($thumbRel);

            if (! File::exists($thumbAbs)) {
                $donwloadService = new ThumbnailDownloadService();
                $filename = $donwloadService->getFileName($serie->name);
                $url = $this->getSeriesThumbnailUrl($serie);
                $donwloadService->downloadThumbnail($url, $filename, $serie->toArray());
            }
        }
    }

    public function getSeriesThumbnailUrl(Series $series)
    {
        try {
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Avoid HTTP/2 reuse
                ],
            ])->get(
                'https://admin.ilirski-bukvar.rs/api/series',
                [
                    'name' => $series->name
                ]
            );
        } catch (Throwable $e) {
            Log::error('Could not retrieve thumbnail url for series ' . $series->name);
        }
        
        return $response->json()['data']['series']['thumbnail'] ?? null;
    }
}

