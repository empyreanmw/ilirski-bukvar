<?php

namespace Database\Seeders;

use App\Enums\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('app_settings')->insert([
            'mode' => 'online',
            'content_per_page' => 8,
            'video_quality' => 720,
            'download_path' => null
        ]);
    }
}
