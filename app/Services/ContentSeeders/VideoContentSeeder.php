<?php
namespace App\Services\ContentSeeders;

use App\Models\Content;
use App\Models\Series;
use App\Services\ContentSeeders\DownloadableThumbnails;
use App\Services\ContentSeeders\VariablePlayerType;

class VideoContentSeeder
{
    use DownloadableThumbnails, VariablePlayerType, SeriesCreation;

    public function saveContent(array $contentData): void
    {
        if ($contentData['series'] !== null) {
            $series = $this->createSeries($contentData['series']);
        }

        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'url' => $contentData['url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => $series->id ?? null,
                'author_id' => $series->author_id ?? null,
                'thumbnail_url' => $this->downloadThumbnail($contentData['thumbnail_url'], $this->getFileName($contentData['name']), $contentData['series']),
                'url' => $contentData['url'],
                'parent_type' => Series::class,
                'download_url' => $contentData['download_url'],
                'type' => $contentData['type'],
                'player_type' => $this->determinePlayerType($contentData['url']),
            ]
        );
    }
}