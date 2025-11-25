<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For API routes, check session token
        if ($request->expectsJson() || $request->is('api/*')) {
            if (!Session::has('api_token') || !Session::has('user')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            // For web routes, redirect to login
            if (!Session::has('api_token')) {
                return redirect()->route('login');
            }

            // Verify user exists
            $sessionUser = Session::get('user');
            if (!$sessionUser) {
                Session::forget('api_token');
                Session::forget('user');
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}

