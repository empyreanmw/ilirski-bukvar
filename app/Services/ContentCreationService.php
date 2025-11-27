<?php
namespace App\Services;

use App\Enums\PlayerType;
use App\Models\Content;
use App\Services\ContentSeeders\YoutubeContentSeeder;
use App\Services\ContentSeeders\BookContentSeeder;
use App\Services\ContentSeeders\VideoContentSeeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContentCreationService
{
    public function createContent(array $content)
    {
        match ($content['player_type']) {
            PlayerType::YOUTUBE->value => $this->seedYoutubeContent($content),
            PlayerType::PDF->value => $this->seedPdfContent($content),
            default => $this->seedVideoContent($content),
        };
    }

    public function deleteContent(array $content)
    {
        $content = Content::where('slug', $content['slug'])->first();

        if (File::exists(public_path($content->thumbnail_url))) {
            try {
                File::delete(public_path($content->thumbnail_url));
            } catch (Throwable $e) {
                report($e);
            }
        }
        
        if ($content) {
            $content->delete();
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