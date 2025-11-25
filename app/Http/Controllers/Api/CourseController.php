<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('videos')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'thumbnail' => $course->thumbnail,
                'preview_video' => $course->preview_video,
                'preview_video_url' => $course->preview_video_url,
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
            ];
        });

        return response()->json($courses);
    }

    public function show($id)
    {
        $course = Course::with('videos')->find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'thumbnail' => $course->thumbnail,
            'preview_video' => $course->preview_video,
            'preview_video_url' => $course->preview_video_url,
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
}
