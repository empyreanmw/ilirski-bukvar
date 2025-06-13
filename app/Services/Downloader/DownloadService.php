<?php

namespace App\Services\Downloader;

use App\Models\Content;
use Exception;

readonly class DownloadService
{
    public function __construct(private DownloaderFactory $downloaderFactory)
    {
    }

    /**
     * @throws Exception
     */
    public function download(Content $content): void
    {
        try {
            $this->downloaderFactory
                ->create($content)
                ->download($content);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
