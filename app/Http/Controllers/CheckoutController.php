<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
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
            
            if (empty($cart)) {
                return redirect()->route('cart.index')->withErrors(['error' => 'Cart is empty']);
            }
            
            return view('checkout.index', compact('cart', 'user'));
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $cart = $this->apiService->getCart();
            
            if (empty($cart)) {
                return back()->withErrors(['error' => 'Cart is empty']);
            }
            
            $total = array_sum(array_column($cart, 'price'));
            
            $this->apiService->createOrder([
                'total' => $total,
            ], $request->file('payment_proof'));
            
            return redirect()->route('orders.index')->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}

