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
                'thumbnail' => str_replace('/', '\\', $series['thumbnail']),
                'category_id' => Category::where('name', $series['category']['name'])->first()->id ?? $series['category']['id']
            ]
        );
    }
}
