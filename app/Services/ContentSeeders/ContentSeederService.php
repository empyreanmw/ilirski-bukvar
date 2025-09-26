<?php
namespace App\Services\ContentSeeders;

use App\Enums\PlayerType;
use App\Services\ContentSeeders\YoutubeContentSeeder;

class ContentSeederService
{
    /**
     * Seed the content for the application.
     */
    public function run(array $data): void
    {
        foreach ($data as $content) {
            match ($content['player_type']) {
                PlayerType::YOUTUBE->value => $this->seedYoutubeContent($content),
                PlayerType::PDF->value => $this->seedPdfContent($content),
                default => $this->seedVideoContent($content),
            };
        }
    }

    protected function seedYoutubeContent(array $content): void
    {
        (new YoutubeContentSeeder())->saveContent($content);
    }

    protected function seedPdfContent(array $content): void
    {
        (new BookContentSeeder())->saveContent($content);
    }

    protected function seedVideoContent(array $content): void
    {
        (new VideoContentSeeder())->saveContent($content);
    }
}