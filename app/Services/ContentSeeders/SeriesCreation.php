<?php
namespace App\Services\ContentSeeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Support\Str;

trait SeriesCreation
{
    use DownloadableThumbnails;
    
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
                'author_id' => $this->setAuthor($series),
                'thumbnail' => $this->createThumbnail($series),
                'category_id' => Category::where('name', $series['category']['name'])->first()->id ?? $series['category']['id']
            ]
        );
    }

    public function createThumbnail(array $series): string
    {
        if ($this->isThumbnailWindowsPath($series['thumbnail']) === true) {
            return str_replace('/', '\\', $series['thumbnail']);
        }

        return $this->downloadThumbnail($series['thumbnail'], $this->getFileName($series['name']), $series);
    }

    public function isThumbnailWindowsPath(string $thumbnail): bool
    {
        return Str::startsWith($thumbnail, '/thumbnails');
    }

    public function setAuthor(array $series)
    {
        if ($series['author'] === null) {
            return null;
        }

        return Author::firstOrCreate([
            'name'=> $series['author']['name'],
        ], [
            'name' => $series['author']['name'],
        ])->id;
    }
}
