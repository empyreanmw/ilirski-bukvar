<?php
namespace App\Services\ContentSeeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Series;

trait SeriesCreation
{
    public function createSeries(array $series): Series
    {
        return Series::updateOrCreate(
            [
                'name' => $series['name'],
                'slug' => $series['slug'],
            ],
            [
                'name' => $series['name'],
                'slug' => $series['slug'],
                'author_id' => Author::firstOrCreate([
                    'name'=> $series['author']['name'],
                ], [
                    'name' => $series['author']['name'],
                ])->id,
                'thumbnail' => $this->createThumbnail($series),
                'category_id' => Category::where('name', $series['category']['name'])->first()->id ?? $series['category']['id']
            ]
        );
    }

    public function createThumbnail(array $series): string
    {
        if (!$this->isThumbnailUrl($series['thumbnail'])) {
            return str_replace('/', '\\', $series['thumbnail']);
        }

        return $this->downloadThumbnail($series['thumbnail'], $this->getFileName($series), $series);
    }

    public function isThumbnailUrl(string $thumbnail): bool
    {
        $trimmed = trim($thumbnail);
        // 1) Strict HTTP(S) URL check
        // - filter_var says "valid URL"
        // - parse_url gives us scheme + host
        if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
            $parts = parse_url($trimmed);
            $scheme = strtolower($parts['scheme'] ?? '');
            $host   = $parts['host']   ?? '';

            if (in_array($scheme, ['http', 'https'], true) && $host !== '') {
                return $scheme === 'https' ? 'https' : 'http';
            }
        }
        
        return false;
    }
}
