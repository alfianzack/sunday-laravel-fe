<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $cart = $this->apiService->getCart();
            $user = $this->apiService->getCurrentUser();
            return view('cart.index', compact('cart', 'user'));
        } catch (\Exception $e) {
            $cart = [];
            $user = null;
            return view('cart.index', compact('cart', 'user'));
        }
    }

    public function add($courseId)
    {
        try {
            $this->apiService->addToCart($courseId);
            return back()->with('success', 'Course added to cart');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function remove($courseId)
    {
        try {
            $this->apiService->removeFromCart($courseId);
            return back()->with('success', 'Course removed from cart');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

