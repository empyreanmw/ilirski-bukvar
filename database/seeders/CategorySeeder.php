<?php

namespace Database\Seeders;

use App\Enums\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
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

    /**
     * Run the database seeds.
     */
    public function run(): void
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
    }
}
