<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $courses = $this->apiService->getCourses();
            $user = $this->apiService->getCurrentUser();
            return view('admin.courses.index', compact('courses', 'user'));
        } catch (\Exception $e) {
            $courses = [];
            $user = null;
            return view('admin.courses.index', compact('courses', 'user'));
        }
    }

    public function create()
    {
        $user = $this->apiService->getCurrentUser();
        return view('admin.courses.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'preview_video_url' => 'nullable|url',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
            ];
            
            if ($request->preview_video_url) {
                $data['preview_video_url'] = $request->preview_video_url;
            }
            
            $this->apiService->createCourse($data, $request->file('thumbnail'), $request->file('preview_video'));
            
            return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        try {
            $course = $this->apiService->getCourse($id);
            $user = $this->apiService->getCurrentUser();
            return view('admin.courses.show', compact('course', 'user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.courses.index')->withErrors(['error' => 'Course not found']);
        }
    }

    public function edit($id)
    {
        try {
            $course = $this->apiService->getCourse($id);
            $user = $this->apiService->getCurrentUser();
            return view('admin.courses.edit', compact('course', 'user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.courses.index')->withErrors(['error' => 'Course not found']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'preview_video_url' => 'nullable|url',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
            ];
            
            if ($request->preview_video_url) {
                $data['preview_video_url'] = $request->preview_video_url;
            }
            
            $this->apiService->updateCourse($id, $data, $request->file('thumbnail'), $request->file('preview_video'));
            
            return redirect()->route('admin.courses.show', $id)->with('success', 'Course updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function addVideo(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB
            'order_index' => 'required|integer|min:0',
        ]);

        try {
            $this->apiService->addVideoToCourse($courseId, [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'order_index' => $request->order_index,
            ], $request->file('video'));
            
            return back()->with('success', 'Video added successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateVideo(Request $request, $courseId, $videoId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'order_index' => 'required|integer|min:0',
        ]);

        try {
            $this->apiService->updateVideo($courseId, $videoId, [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'order_index' => $request->order_index,
            ], $request->file('video'));
            
            return back()->with('success', 'Video updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteVideo($courseId, $videoId)
    {
        try {
            $this->apiService->deleteVideo($courseId, $videoId);
            return back()->with('success', 'Video deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

