<?php

namespace App\Repositories;

use App\Enums\QueueJobRequestStatus;
use App\Models\JobRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QueueJobRequestsRepository
{
    public function getRequests()
    {
        $jobRequests = $this->getRequestsOrderedByStatus()
            ->smartPaginate();

        $result = $jobRequests->getCollection()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'job_reference' => $item->reference,
                    'job_status' => $item->job_status,
                    'job_reference_id' => $item->job_reference_id,
                    'progress' => $item->job_status === QueueJobRequestStatus::DONE ? 100 : 0,
                ];
            });
        return $jobRequests->setCollection($result);
    }

    public function getCancellableRequests(): Collection
    {
        return JobRequest::query()
            ->whereNotIn('job_status', [QueueJobRequestStatus::IN_PROGRESS, QueueJobRequestStatus::DONE, QueueJobRequestStatus::FAILED])
            ->get();
    }

    public function getActiveDownloads(): Collection
    {
        return $this->getRequestsOrderedByStatus()
            ->whereIn('job_status', [QueueJobRequestStatus::IN_PROGRESS, QueueJobRequestStatus::QUEUED])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'content' => $item->reference,
                    'job_reference_id' => $item->job_reference_id,
                    'progress' => $item->job_status === QueueJobRequestStatus::DONE ? 100 : 0,
                    'percent' => 0,
                    'total' => 0
                ];
            });
    }

    private function getRequestsOrderedByStatus(): Builder
    {
        return JobRequest::query()
            ->orderByRaw("
        CASE
            WHEN job_status = ? THEN 0
            WHEN job_status = ? THEN 1
            ELSE 2
        END
    ", [QueueJobRequestStatus::IN_PROGRESS, QueueJobRequestStatus::QUEUED])
            ->orderByDesc('created_at');
    }
}
