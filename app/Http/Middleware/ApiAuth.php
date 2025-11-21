<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ApiService;

class ApiAuth
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }

        // Verify token is still valid by getting current user
        $user = $this->apiService->getCurrentUser();
        if (!$user) {
            session()->forget('api_token');
            session()->forget('user');
            return redirect()->route('login');
        }

        return $next($request);
    }
}

