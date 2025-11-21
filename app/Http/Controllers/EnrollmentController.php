<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $enrollments = $this->apiService->getEnrollments();
            $user = $this->apiService->getCurrentUser();
            return view('enrollments.index', compact('enrollments', 'user'));
        } catch (\Exception $e) {
            $enrollments = [];
            $user = null;
            return view('enrollments.index', compact('enrollments', 'user'));
        }
    }

    public function show($courseId)
    {
        try {
            $enrollment = $this->apiService->getEnrollment($courseId);
            $user = $this->apiService->getCurrentUser();
            
            if (!$enrollment) {
                return redirect()->route('enrollments.index')->withErrors(['error' => 'Enrollment not found']);
            }
            
            return view('enrollments.show', compact('enrollment', 'user', 'courseId'));
        } catch (\Exception $e) {
            \Log::error('Enrollment show error: ' . $e->getMessage());
            return redirect()->route('enrollments.index')->withErrors(['error' => 'Enrollment not found: ' . $e->getMessage()]);
        }
    }

    public function updateProgress(Request $request, $courseId)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        try {
            $this->apiService->updateProgress($courseId, $request->progress);
            return back()->with('success', 'Progress updated');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

