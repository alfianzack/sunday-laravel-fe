<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)->with('course')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->course->price;
        });

        // Store payment proof
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'payment_proof' => $paymentProofPath,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $cartItem->course_id,
                'price' => $cartItem->course->price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => [
                'id' => $order->id,
                'total' => $order->total,
                'status' => $order->status,
            ],
        ]);
    }

    public function index()
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->with('items.course')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_proof' => $order->payment_proof,
                    'created_at' => $order->created_at,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->course->id,
                            'title' => $item->course->title,
                            'price' => $item->price,
                        ];
                    }),
                ];
            });

        return response()->json($orders);
    }

    protected function getUserFromSession()
    {
        $sessionUser = Session::get('user');
        if (!$sessionUser) {
            return null;
        }

        return \App\Models\User::find($sessionUser['id']);
    }
}
