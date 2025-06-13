<?php

namespace Database\Seeders\Series;

use App\Enums\ContentType;
use App\Enums\PlayerType;
use App\Models\Content;
use App\Models\Series;
use Database\Seeders\ContentSeeder;
use Database\Seeders\interfaces\SeriesContent;
use Database\Seeders\traits\SeriesCreation;

class YoutubeSeriesSeeder extends ContentSeeder implements SeriesContent
{
    use SeriesCreation;
    private const SERIES_FILE_PATH = 'database\seeders\series\YoutubeSeries.php';

    protected function saveContent(array $contentData): void
    {
        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'download_url' => $contentData['url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => $contentData['series']->id,
                'author_id' => $contentData['series']->author_id,
                'thumbnail_url' => null,
                'url' => $contentData['id'],
                'downloaded' => false,
                'parent_type' => Series::class,
                'download_url' => $contentData['url'],
                'local_url' => null,
                'is_favorite' => false,
                'size' => 0,
                'type' => ContentType::VIDEO,
                'player_type' => PlayerType::YOUTUBE,
            ]
        );
    }
}
