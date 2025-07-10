<?php

namespace App\Http\Controllers\Actions;

use App\Enums\AppModeSwitch;
use App\Http\Controllers\Controller;
use App\Services\AppModeService;
use Illuminate\Http\Request;

class AppModeController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request, AppModeService $appModeService)
    {
        $appModeService->setMode(AppModeSwitch::MANUAL, $request->mode);

        return redirect()->back();
    }
}
