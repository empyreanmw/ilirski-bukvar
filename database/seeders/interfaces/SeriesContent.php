<?php

namespace Database\Seeders\interfaces;

use App\Models\Series;

interface SeriesContent
{
    function createSeries(array $seriesData): Series;
    public function getFileContent(): array;
}
