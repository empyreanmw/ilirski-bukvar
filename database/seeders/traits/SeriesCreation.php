<?php

namespace Database\Seeders\traits;

use App\Models\Author;
use App\Models\Category;
use App\Models\Series;
use Exception;
use File;
use Illuminate\Support\Facades\Log;

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
                    'name' => $series['author'],
                ], [
                    'name' => $series['author'],
                ])->id,
                'thumbnail' => $series['thumbnail'],
                'category_id' => Category::firstOrCreate([
                    'name' => $series['category'],
                ], [
                    'name' => $series['category'],
                ])->id
            ]
        );
    }

    public function getFileContent(): array
    {
        $seriesList = require base_path(self::SERIES_FILE_PATH);
        $videos = [];

        foreach ($seriesList as $series) {
            $seriesModel = $this->createSeries($series);

            try {
                $seriesJson = File::get($series['path']);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                continue;
            }

            $videoList = json_decode($seriesJson, true);

            foreach ($videoList as $video) {
                //inject the seriesModel
                $video['series'] = $seriesModel;
                $videos[] = $video;
            }
        }

        return $videos;
    }
}
