<?php
namespace Database\Seeders\traits;

use App\Models\Series;
use App\Services\Slugify;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

trait DownloadableThumbnails
{
    public function getFileName(array $contentData): string
    {
        return sprintf('%s.jpg', Slugify::run($contentData['name']));
    }

    public function downloadThumbnail(string $url, string $filename, ?Series $series = null): ?string
    {
        try {
            $response = Http::withOptions(['verify' => false])->get($url);
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            return null;
        }

        if ($response->failed()) {
            Log::error('Failed to download thumbnail: ' . $url);
            return null;
        }

        $thumbnailBasePath = $this->generateThumbnailBasePath($series);
        // Get the filename from the URL (or you can generate your own unique name)
        $folderPath = public_path($thumbnailBasePath);

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Full path where to save it
        $fullPath = $folderPath . $filename;

        // Save the image into your storage
        file_put_contents($fullPath, $response->body());

        return $thumbnailBasePath . $filename;
    }

    protected function generateThumbnailBasePath($series): string
    {
        if ($series !== null) {
            return sprintf('\thumbnails\\series\\%s\\',  Slugify::run($series->name));
        }

        return '\thumbnails\\books\\';
    }
}
