<?php

namespace App\Http\Middleware;

use App\Services\AppModeService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class AppMode
{
    public function __construct(private AppModeService $appModeService)
    {}
    /**
     * Handle an incoming request.
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->appModeService->setMode();

        return $next($request);
    }
}
