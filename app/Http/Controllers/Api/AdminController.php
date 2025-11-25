<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use App\Models\Order;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function orders()
    {
        $orders = Order::with('user', 'items.course')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ],
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

    public function confirmOrder($orderId)
    {
        $order = Order::with('items')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['status' => 'confirmed']);

        // Create enrollments for all courses in the order
        foreach ($order->items as $item) {
            // Check if enrollment already exists
            $existing = Enrollment::where('user_id', $order->user_id)
                ->where('course_id', $item->course_id)
                ->first();

            if (!$existing) {
                Enrollment::create([
                    'user_id' => $order->user_id,
                    'course_id' => $item->course_id,
                    'progress' => 0,
                ]);
            }
        }

        return response()->json(['message' => 'Order confirmed']);
    }

    public function createCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'preview_video_url' => 'nullable|url',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        $previewVideoPath = null;

        if ($request->hasFile('preview_video')) {
            $previewVideoPath = $request->file('preview_video')->store('preview_videos', 'public');
        }

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'thumbnail' => $thumbnailPath,
            'preview_video' => $previewVideoPath,
            'preview_video_url' => $request->preview_video_url,
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'thumbnail' => $course->thumbnail,
                'preview_video' => $course->preview_video,
                'preview_video_url' => $course->preview_video_url,
            ],
        ]);
    }

    public function updateCourse(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'preview_video_url' => 'nullable|url',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ];

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('preview_video')) {
            // Delete old preview video
            if ($course->preview_video) {
                Storage::disk('public')->delete($course->preview_video);
            }
            $data['preview_video'] = $request->file('preview_video')->store('preview_videos', 'public');
        }

        if ($request->preview_video_url) {
            $data['preview_video_url'] = $request->preview_video_url;
        }

        $course->update($data);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'thumbnail' => $course->thumbnail,
                'preview_video' => $course->preview_video,
                'preview_video_url' => $course->preview_video_url,
            ],
        ]);
    }

    public function addVideo(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'order_index' => 'required|integer|min:0',
        ]);

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        $video = Video::create([
            'course_id' => $courseId,
            'title' => $request->title,
            'description' => $request->description,
            'video' => $videoPath,
            'video_url' => $request->video_url,
            'order_index' => $request->order_index,
        ]);

        return response()->json([
            'message' => 'Video added successfully',
            'video' => [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'video' => $video->video,
                'video_url' => $video->video_url,
                'order_index' => $video->order_index,
            ],
        ]);
    }

    public function updateVideo(Request $request, $courseId, $videoId)
    {
        $video = Video::where('course_id', $courseId)
            ->where('id', $videoId)
            ->first();

        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'order_index' => 'required|integer|min:0',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'order_index' => $request->order_index,
        ];

        if ($request->hasFile('video')) {
            // Delete old video
            if ($video->video) {
                Storage::disk('public')->delete($video->video);
            }
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        $video->update($data);

        return response()->json([
            'message' => 'Video updated successfully',
            'video' => [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'video' => $video->video,
                'video_url' => $video->video_url,
                'order_index' => $video->order_index,
            ],
        ]);
    }

    public function deleteVideo($courseId, $videoId)
    {
        $video = Video::where('course_id', $courseId)
            ->where('id', $videoId)
            ->first();

        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }

        // Delete video file
        if ($video->video) {
            Storage::disk('public')->delete($video->video);
        }

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}
