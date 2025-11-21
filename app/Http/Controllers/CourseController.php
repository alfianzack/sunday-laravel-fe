<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class CourseController extends Controller
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
            
            // Redirect admin to admin courses page
            if ($user && $user['role'] === 'admin') {
                return redirect()->route('admin.courses.index');
            }
            
            return view('courses.index', compact('courses', 'user'));
        } catch (\Exception $e) {
            $courses = [];
            $user = null;
            return view('courses.index', compact('courses', 'user'));
        }
    }

    public function show($id)
    {
        try {
            $course = $this->apiService->getCourse($id);
            $user = $this->apiService->getCurrentUser();
            
            // Redirect admin to admin course detail page
            if ($user && $user['role'] === 'admin') {
                return redirect()->route('admin.courses.show', $id);
            }
            
            return view('courses.show', compact('course', 'user'));
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->withErrors(['error' => 'Course not found']);
        }
    }
}

