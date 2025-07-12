<?php

use App\Models\Series;
use Database\Seeders\Series\YoutubeSeriesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $series = Series::where('name', 'Pragmatikos')->first();

        if ($series === null) {
            return;
        }

        $contentData = [
            [
                'name' => 'Pragmatikos #43',
                'id' => '3sixQReKE5Q',
                'url' => 'https://www.youtube.com/watch?v=3sixQReKE5Q',
                'series' => $series
            ],
            [
                'name' => 'Pragmatikos #44',
                'id' => 'DxG6cCaovLE',
                'url' => 'https://www.youtube.com/watch?v=DxG6cCaovLE',
                'series' => $series
            ],
            [
                'name' => 'Pragmatikos #45',
                'id' => '0S-ETbS6vPw',
                'url' => 'https://www.youtube.com/watch?v=0S-ETbS6vPw',
                'series' => $series
            ]
        ];

        $seriesSeeder = new YoutubeSeriesSeeder();

        foreach ($contentData as $content) {
            $seriesSeeder->saveContent($content);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
