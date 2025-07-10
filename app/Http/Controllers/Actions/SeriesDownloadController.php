<?php

namespace App\Http\Controllers\Actions;

use App\Dtos\DownloadOutput;
use App\Events\FileAddedToQueue;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\SeriesDownloadRequest;
use App\Jobs\DownloadJob;
use App\Models\Content;
use App\Services\Downloader\DownloadService;
use App\Services\JobService;

class SeriesDownloadController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(SeriesDownloadRequest $request, DownloadService $downloadService, JobService $jobService)
    {
        $request->validated();

        $downloadableContent = Content::query()
            ->where('parent_id', $request->id)
            ->where('downloaded', false)
            ->get();

        if ($downloadableContent->isEmpty()) {
            return response(status: 200);
        }

        foreach ($downloadableContent as $content) {
            $jobService->dispatch(new DownloadJob($content));
            broadcast(new FileAddedToQueue(
                new DownloadOutput(
                    0,
                    0,
                    0,
                    $content->serializeData(),
                )
            ));
        }

        return response(status: 200);
    }
}
