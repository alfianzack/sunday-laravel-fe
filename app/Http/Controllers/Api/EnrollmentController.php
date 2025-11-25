<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get all confirmed orders
        $confirmedOrders = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->with('items.course')
            ->get();

        // Create enrollments from confirmed orders
        $enrollments = [];
        foreach ($confirmedOrders as $order) {
            foreach ($order->items as $item) {
                $course = $item->course;
                
                // Check if enrollment already exists
                $enrollment = Enrollment::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$enrollment) {
                    // Create enrollment
                    $enrollment = Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'progress' => 0,
                    ]);
                }

                $enrollments[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'price' => $course->price,
                    'thumbnail' => $course->thumbnail,
                    'progress' => $enrollment->progress,
                ];
            }
        }

        return response()->json($enrollments);
    }

    public function show($courseId)
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->with('course.videos')
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        }

        $course = $enrollment->course;

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'thumbnail' => $course->thumbnail,
            'preview_video' => $course->preview_video,
            'preview_video_url' => $course->preview_video_url,
            'progress' => $enrollment->progress,
            'videos' => $course->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'video' => $video->video,
                    'video_url' => $video->video_url,
                    'order_index' => $video->order_index,
                ];
            }),
        ]);
    }

    public function updateProgress(Request $request, $courseId)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $user = $this->getUserFromSession();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        }

        $enrollment->update([
            'progress' => $request->progress,
        ]);

        return response()->json([
            'message' => 'Progress updated',
            'progress' => $enrollment->progress,
        ]);
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
