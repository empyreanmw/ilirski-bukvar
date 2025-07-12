<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Native\Laravel\Facades\AutoUpdater;

class QuitAndUpdateController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
    )
    {
        AutoUpdater::quitAndInstall();
    }
}
