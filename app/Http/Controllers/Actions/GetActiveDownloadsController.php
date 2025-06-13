<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\QueueJobRequestsRepository;

class GetActiveDownloadsController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        QueueJobRequestsRepository $jobRequestsRepository,
    )
    {
        return response()->json([
            'downloads' => $jobRequestsRepository->getActiveDownloads()
        ]);
    }
}
