<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $orders = $this->apiService->getOrders();
            $user = $this->apiService->getCurrentUser();
            return view('orders.index', compact('orders', 'user'));
        } catch (\Exception $e) {
            $orders = [];
            $user = null;
            return view('orders.index', compact('orders', 'user'));
        }
    }
}

