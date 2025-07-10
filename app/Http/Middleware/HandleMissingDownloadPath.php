<?php

namespace App\Http\Middleware;

use App\Models\AppSettings;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

readonly class HandleMissingDownloadPath
{
    /**
     * Handle an incoming request.
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appSettings = AppSettings::first();

        Inertia::share([
            'downloadPathMissing' => $appSettings->download_path === null,
        ]);

        return $next($request);
    }
}
