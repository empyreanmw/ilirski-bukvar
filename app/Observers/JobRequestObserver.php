<?php

namespace App\Observers;

use App\Events\RequestStatusUpdated;
use App\Models\JobRequest;

class JobRequestObserver
{
    /**
     * Handle the JobRequest "updated" event.
     */
    public function updated(JobRequest $jobRequest): void
    {
        if ($jobRequest->wasChanged('job_status')) {
            RequestStatusUpdated::dispatch($jobRequest);
        }
    }
}
