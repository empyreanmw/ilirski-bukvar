<?php

namespace App\Http\Controllers\Actions;

use App\Dtos\DownloadOutput;
use App\Events\FileAddedToQueue;
use App\Http\Controllers\Controller;
use App\Jobs\DownloadJob;
use App\Models\Content;
use App\Services\Downloader\DownloadService;
use App\Services\JobService;
use App\Services\Utf8ize;
use Illuminate\Http\Request;

class ContentDownloadController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request, DownloadService $downloadService, JobService $dispatcherService)
    {
        $content = Content::find($request->id);
        $utf8izedContent = Utf8ize::run($content);

        $dispatcherService->dispatch(new DownloadJob($utf8izedContent));

        broadcast(new FileAddedToQueue(
            new DownloadOutput(
                0,
                0,
                0,
                $utf8izedContent->serializeData(),
            )
        ));

        return response(status: 200);
    }
}
