<?php

namespace App\Jobs;

use App\Models\Content;
use App\Services\Downloader\DownloadService;

class DownloadJob extends BaseJob
{
    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Content $content)
    {
        //
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(DownloadService $downloadService): void
    {
        $downloadService->download($this->content);
    }
}
