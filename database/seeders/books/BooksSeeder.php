<?php

namespace Database\Seeders\Books;

use App\Enums\ContentType;
use App\Enums\PlayerType;
use App\Models\Author;
use App\Models\Category;
use App\Models\Content;
use Database\Seeders\ContentSeeder;
use Database\Seeders\interfaces\ContentWithDownloadableThumbnails;
use Database\Seeders\traits\DownloadableThumbnails;
use Exception;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BooksSeeder extends ContentSeeder implements ContentWithDownloadableThumbnails
{
    use DownloadableThumbnails;
    const DATA_FILES = [
        [
            'file' => '/data/theology.json',
            'category' => 'theology'
        ],
        [
            'file' => '/data/history.json',
            'category' => 'history'
        ],
        [
            'file' => '/data/health.json',
            'category' => 'health'
        ]
    ];

    protected function getFileContent(): array
    {
        $books = collect();

        foreach (self::DATA_FILES as $item) {
            try {
                $bookFile = File::get(__DIR__ . $item['file']);
                $books[] = json_decode($bookFile, true);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }

        return $books->collapse()->toArray();
    }

    protected function saveContent(array $contentData): void
    {
        Content::updateOrCreate(
            [
                'title' => $contentData['name'],
                'url' => $contentData['url'],
            ],
            [
                'title' => $contentData['name'],
                'parent_id' => Category::where('name', $contentData['category'])->first()->id,
                'author_id' => empty($contentData['author']) ? null : Author::firstOrCreate([
                    'name' => $contentData['author'],
                ], [
                    'name' => $contentData['author'],
                ])->id,
                'thumbnail_url' => $this->downloadThumbnail($contentData['thumbnail'], $this->getFileName($contentData['name'])),
                'url' => $contentData['url'],
                'downloaded' => false,
                'parent_type' => Category::class,
                'download_url' => $contentData['download_url'],
                'local_url' => null,
                'is_favorite' => false,
                'size' => 0,
                'type' => ContentType::BOOK,
                'player_type' => PlayerType::PDF
            ]
        );
    }
}
