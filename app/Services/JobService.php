<?php
namespace App\Services;

use App\Enums\QueueJobRequestStatus;
use App\Events\RequestStatusUpdated;
use App\Interfaces\InteractsWithDatabase;
use App\Models\Content;
use App\Models\JobRequest;
use DB;
use Illuminate\Contracts\Queue\Job;
use App\Models\Job as JobModel;

class JobService
{
    public function dispatch(InteractsWithDatabase $job): void
    {
        dispatch($job);

        $this->createQueueJobRequest($job);
    }

    public function setQueueRequestToFail(Job $job): void
    {
        $jobRequest = JobRequest::query()
            ->where('job_id', $job->getJobId())
            ->with('reference')
            ->first();

        $jobRequest->update([
            'job_status' => QueueJobRequestStatus::FAILED,
        ]);

        RequestStatusUpdated::dispatch($jobRequest);
    }

    protected function createQueueJobRequest(InteractsWithDatabase $job): void
    {
        $jobRow = DB::table('jobs')->latest('id')->first();

        $this->deleteExistingJobRequest($job->content);

        JobRequest::create([
            'job_reference' => get_class($job->content),
            'job_reference_id' => $job->content->id,
            'job_id' => $jobRow->id,
            'job_status' => QueueJobRequestStatus::QUEUED,
            'job_type' => get_class($job),
        ]);
    }

    public function updateQueueJobRequestStatus(QueueJobRequestStatus $status, Job $job): void
    {
        $jobRequest = JobRequest::query()
            ->where('job_id', $job->getJobId())
            ->with('reference')
            ->first();
        if ($jobRequest === null) {
            return;
        }

        $jobRequest->job_status = $status;
        $jobRequest->save();

        RequestStatusUpdated::dispatch($jobRequest);
    }

    protected function deleteExistingJobRequest(Content $content): void
    {
        $jobRequest = JobRequest::query()
            ->where('job_reference_id', $content->id)
            ->first();

        if ($jobRequest !== null) {
            JobModel::query()->where('id', $jobRequest->job_id)->delete();
            $jobRequest->delete();
        }
    }

    public function cancelJob(JobRequest $jobRequest): void
    {
        DB::table('jobs')->where('id', $jobRequest->job_id)->delete();

        $jobRequest->job_status = QueueJobRequestStatus::CANCELLED;
        $jobRequest->save();
    }

    public function cancelAllJobs(): void
    {
        DB::table('jobs')
            ->whereNull('reserved_at') // get only jobs that are not in progress
            ->delete();

        JobRequest::query()
            ->whereNotIn('job_status', [QueueJobRequestStatus::IN_PROGRESS, QueueJobRequestStatus::DONE, QueueJobRequestStatus::FAILED])
            ->update(['job_status' => QueueJobRequestStatus::CANCELLED]);
    }
}
