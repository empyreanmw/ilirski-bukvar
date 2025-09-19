<?php
namespace App\Services\ContentSeeders;

use App\Enums\ContentType;
use App\Enums\PlayerType;
use App\Models\Category;
use App\Models\Content;
use App\Services\ContentSeeders\DownloadableThumbnails;

class BookContentSeeder
{
    use DownloadableThumbnails;

    public function saveContent(array $contentData): void
    {
        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'url' => $contentData['url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => Category::where('name', $contentData['category'])->first()->id ?? null,
                'author_id' => $contentData['author_id'] ?? null,
                'thumbnail_url' => $this->downloadThumbnail($contentData['thumbnail_url'], $this->getFileName($contentData)),
                'url' => $contentData['url'],
                'downloaded' => false,
                'parent_type' => Category::class,
                'download_url' => $contentData['download_url'],
                'local_url' => null,
                'is_favorite' => false,
                'size' => 0,
                'type' => ContentType::BOOK,
                'player_type' => PlayerType::PDF,
            ]
        );
    }
}