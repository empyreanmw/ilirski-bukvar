<?php

namespace App\Services\Downloader;

use App\Enums\ContentType;
use App\Models\Content;

class DownloaderFactory
{
    public function create(Content $content): Downloader
    {
        return match ($content->type) {
            ContentType::VIDEO => app(YoutubeDownloader::class),
            ContentType::CARTOON, ContentType::BOOK, ContentType::MOVIE => app(FileDownloader::class),
        };
    }
}
