<?php

namespace App\Http\Controllers\Actions;

use App\Dtos\DownloadOutput;
use App\Events\FileAddedToQueue;
use App\Exceptions\InvalidDownloadPathException;
use App\Exceptions\NotEnoughDiskSpaceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\DownloadAllRequest;
use App\Jobs\DownloadJob;
use App\Models\JobRequest;
use App\Repositories\ContentRepository;
use App\Repositories\QueueJobRequestsRepository;
use App\Services\DiskFreeSpaceCheckerService;
use App\Services\JobService;

class DownloadAllController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        JobRequest $jobRequest,
        ContentRepository $repository,
        JobService $jobService,
        QueueJobRequestsRepository $jobRequestsRepository,
        DiskFreeSpaceCheckerService $spaceCheckerService,
        DownloadAllRequest $request
    )
    {
        $request->validated();

        $categories = $request->categories;
        $type = $request->type ?? null;

        try {
            $spaceCheckerService->checkFreeSpace($categories);
        } catch (InvalidDownloadPathException|NotEnoughDiskSpaceException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        foreach ($repository->getNonDownloadedContent($categories, $type) as $content) {
           $jobService->dispatch(new DownloadJob($content));

           broadcast(new FileAddedToQueue(
               new DownloadOutput(
                   0,
                   0,
                   0,
                   $content->toArray(),
               )
           ));
       }

        return response()->json([
            'requests' => $jobRequestsRepository->getRequests()
        ]);
    }
}
