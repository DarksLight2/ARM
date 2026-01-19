<?php

namespace DarksLight2\AiRequestsMonitoring\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if(! config('ai-monitor.enabled')) abort(Response::HTTP_NOT_FOUND);
        return $next($request);
    }
}
