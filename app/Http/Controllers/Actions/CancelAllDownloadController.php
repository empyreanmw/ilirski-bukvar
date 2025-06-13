<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Repositories\QueueJobRequestsRepository;
use App\Services\JobService;

class CancelAllDownloadController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(JobRequest $jobRequest, JobService $jobService, QueueJobRequestsRepository $jobRequestsRepository)
    {
        $jobService->cancelAllJobs();

        return response()->json([
            'requests' => $jobRequestsRepository->getRequests()
        ]);
    }
}
