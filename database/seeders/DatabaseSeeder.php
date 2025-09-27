<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Books\BooksSeeder;
use Database\Seeders\Series\CartoonSeriesSeeder;
use Database\Seeders\Series\MovieSeriesSeeder;
use Database\Seeders\Series\YoutubeSeriesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppSettingsSeeder::class,
        ]);
    }
}
