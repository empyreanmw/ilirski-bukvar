<?php

use Database\Seeders\Books\BooksSeeder;
use Database\Seeders\Series\CartoonSeriesSeeder;
use Database\Seeders\Series\MovieSeriesSeeder;
use Database\Seeders\Series\YoutubeSeriesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const CATEGORIES = [
        [
            'name' => 'history',
            'size' => 2900
        ],
        [
            'name' => 'theology',
            'size' => 40000
        ],
        [
            'name' => 'health',
            'size' => 9200
        ],
        [
            'name' => 'podcasts',
            'size' => 55000
        ],
        [
            'name' => 'movies',
            'size' => 64000
        ],
    ];
    private const SEEDERS = [
        MovieSeriesSeeder::class,
        YoutubeSeriesSeeder::class,
        CartoonSeriesSeeder::class,
        BooksSeeder::class,
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::CATEGORIES as $category) {
            DB::table('categories')->updateOrInsert([
                'name' => $category['name'],
                'size' => $category['size'],
            ],
                [
                    'name' => $category['name'],
                    'size' => $category['size'],
                ],
            );
        }

        DB::table('app_settings')->insert([
            'mode' => 'online',
            'content_per_page' => 8,
            'video_quality' => 720,
            'download_path' => null
        ]);

        foreach (self::SEEDERS as $seeder) {
            (new $seeder())->run();
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
