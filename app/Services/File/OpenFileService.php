<?php
namespace App\Services\File;

use App\Enums\AppMode;
use App\Enums\ContentType;
use App\Models\AppSettings;
use App\Models\Content;
use Native\Laravel\Facades\Shell;

class OpenFileService
{
    public function open(AppSettings $appSettings, Content $content): void
    {
        if (AppMode::OFFLINE === $appSettings->mode) {
            $contentPlayerPath = $this->getContentPlayerPath($content, $appSettings);

            // if there is no player exe selected, open in default player
            if ($contentPlayerPath === null) {
                Shell::openFile($content->local_url);

                return;
            }

            // use popen here because it just executres the command and it doesnt block the app
            // while executable is open lke shell_exec
            popen("start /B " .
                    $this->getCommand(
                        $this->getContentPlayerPath($content, $appSettings),
                        $content->local_url
                    ),
                "r");

            return;
        }

        // open file in online mode (books via browser)
        Shell::openFile($content->url);
    }

    protected function getContentPlayerPath(Content $content, AppSettings $appSettings): ?string
    {
        return match ($content->type) {
            ContentType::BOOK => $appSettings->book_reader_path,
            ContentType::VIDEO, ContentType::MOVIE, ContentType::CARTOON => $appSettings->video_player_path
        };
    }

    protected function getCommand(string $contentPlayerPath, string $contentUrl): string
    {
        return sprintf('start "" "%s" "%s"', $contentPlayerPath, $contentUrl);
    }
}
