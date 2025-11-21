<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $courses = $this->apiService->getCourses();
            $user = $this->apiService->getCurrentUser();
            
            // Redirect admin to admin dashboard
            if ($user && $user['role'] === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return view('home', compact('courses', 'user'));
        } catch (\Exception $e) {
            $courses = [];
            $user = null;
            return view('home', compact('courses', 'user'));
        }
    }
}

