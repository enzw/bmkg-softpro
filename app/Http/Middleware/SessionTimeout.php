<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionTimeout = config('session.lifetime') * 60; // Convert to seconds
            $lastActivity = session('last_activity');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity) > $sessionTimeout) {
                // Session has expired
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect('/login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }

            // Update last activity
            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
