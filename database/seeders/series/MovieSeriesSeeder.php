<?php

namespace Database\Seeders\Series;

use App\Enums\ContentType;
use App\Models\Content;
use App\Models\Series;
use Database\Seeders\ContentSeeder;
use Database\Seeders\interfaces\ContentWithDownloadableThumbnails;
use Database\Seeders\interfaces\SeriesContent;
use Database\Seeders\interfaces\PlayerType as PlayerTypeInterface;
use Database\Seeders\traits\DownloadableThumbnails;
use Database\Seeders\traits\SeriesCreation;
use Database\Seeders\traits\VariablePlayerType;

class MovieSeriesSeeder extends ContentSeeder implements SeriesContent, ContentWithDownloadableThumbnails, PlayerTypeInterface
{
    use SeriesCreation, DownloadableThumbnails, VariablePlayerType;
    private const SERIES_FILE_PATH = 'database\seeders\series\MovieSeries.php';

    protected function saveContent(array $contentData): void
    {
        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'url' => $contentData['url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => $contentData['series']->id,
                'author_id' => $contentData['series']->author_id,
                'thumbnail_url' => $this->downloadThumbnail($contentData['thumbnail_url'], $this->getFileName($contentData), $contentData['series']),
                'url' => $contentData['url'],
                'downloaded' => false,
                'parent_type' => Series::class,
                'download_url' => $contentData['download_url'],
                'local_url' => null,
                'is_favorite' => false,
                'size' => 0,
                'type' => ContentType::MOVIE,
                'player_type' => $this->determinePlayerType($contentData['url'])
            ]
        );
    }
}
