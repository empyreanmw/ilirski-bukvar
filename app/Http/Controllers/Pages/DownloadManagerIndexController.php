<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Repositories\QueueJobRequestsRepository;
use Inertia\Inertia;

class DownloadManagerIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(QueueJobRequestsRepository $jobRequestsRepository)
    {
        return Inertia::render('DownloadManager', [
            'jobRequests' => $jobRequestsRepository->getRequests()
        ]);
    }
}
