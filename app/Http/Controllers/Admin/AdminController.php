<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function dashboard()
    {
        try {
            $orders = $this->apiService->getAdminOrders();
            $courses = $this->apiService->getCourses();
            $user = $this->apiService->getCurrentUser();
            
            $pendingOrders = array_filter($orders, fn($o) => $o['status'] === 'pending');
            
            $stats = [
                'totalCourses' => count($courses),
                'pendingOrders' => count($pendingOrders),
                'totalOrders' => count($orders),
            ];
            
            return view('admin.dashboard', compact('stats', 'courses', 'user'));
        } catch (\Exception $e) {
            $stats = [
                'totalCourses' => 0,
                'pendingOrders' => 0,
                'totalOrders' => 0,
            ];
            $courses = [];
            $user = null;
            return view('admin.dashboard', compact('stats', 'courses', 'user'));
        }
    }
}

