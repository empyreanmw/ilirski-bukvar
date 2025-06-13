<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Services\JobService;

class CancelDownloadController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(JobRequest $jobRequest, JobService $jobService)
    {
        $jobService->cancelJob($jobRequest);

        return response()->json([
            'request' => $jobRequest
        ]);
    }
}
