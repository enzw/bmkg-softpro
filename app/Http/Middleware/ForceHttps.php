<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS for production and development
        if (! $request->secure() && env('APP_URL') && str_starts_with(env('APP_URL'), 'https')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
