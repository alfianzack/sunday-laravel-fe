<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $orders = $this->apiService->getAdminOrders();
            $user = $this->apiService->getCurrentUser();
            return view('admin.orders.index', compact('orders', 'user'));
        } catch (\Exception $e) {
            $orders = [];
            $user = null;
            return view('admin.orders.index', compact('orders', 'user'));
        }
    }

    public function confirm($orderId)
    {
        try {
            $this->apiService->confirmPayment($orderId);
            return back()->with('success', 'Payment confirmed successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

