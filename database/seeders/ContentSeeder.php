<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

abstract class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getFileContent() as $contentData) {
            $this->saveContent($contentData);
        }
    }

    protected abstract function getFileContent(): array;
    protected abstract function saveContent(array $contentData): void;
}
