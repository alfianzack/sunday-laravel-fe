<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->with('course')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->course->id,
                    'title' => $item->course->title,
                    'description' => $item->course->description,
                    'price' => $item->course->price,
                    'thumbnail' => $item->course->thumbnail,
                ];
            });

        return response()->json($cartItems);
    }

    public function add($courseId)
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        // Check if already in cart
        $existing = Cart::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Course already in cart'], 400);
        }

        Cart::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
        ]);

        return response()->json(['message' => 'Course added to cart']);
    }

    public function remove($courseId)
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Cart::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->delete();

        return response()->json(['message' => 'Course removed from cart']);
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
