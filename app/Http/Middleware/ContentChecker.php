<?php

namespace App\Http\Middleware;

use App\Services\ContentCheckerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentChecker
{
    public function __construct(private ContentCheckerService $contentCheckerService)
    {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->contentCheckerService->checkForContentUpdate();

        return $next($request);
    }
}
