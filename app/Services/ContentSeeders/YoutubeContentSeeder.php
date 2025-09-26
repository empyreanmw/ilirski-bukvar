<?php

namespace App\Services\ContentSeeders;

use App\Enums\ContentType;
use App\Enums\PlayerType;
use App\Models\Content;
use App\Models\Series;
use App\Services\ContentSeeders\SeriesCreation;

class YoutubeContentSeeder
{
    use SeriesCreation;

    public function saveContent(array $contentData): void
    {
        if ($contentData['series'] !== null) {
            $series = $this->createSeries($contentData['series']);
        }

        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'download_url' => $contentData['download_url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => $series->id ?? null,
                'author_id' => $series->author_id ?? null,
                'thumbnail_url' => null,
                'url' => $contentData['url'],
                'parent_type' => Series::class,
                'download_url' => $contentData['download_url'],
                'type' => ContentType::VIDEO,
                'player_type' => PlayerType::YOUTUBE,
            ]
        );
    }
}