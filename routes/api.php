<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AdminController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Protected routes
Route::middleware('api.auth')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{courseId}', [CartController::class, 'add']);
    Route::delete('/cart/{courseId}', [CartController::class, 'remove']);
    
    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    
    // Enrollments
    Route::get('/enrollments', [EnrollmentController::class, 'index']);
    Route::get('/enrollments/{courseId}', [EnrollmentController::class, 'show']);
    Route::patch('/enrollments/{courseId}/progress', [EnrollmentController::class, 'updateProgress']);
});

// Admin routes
Route::prefix('admin')->middleware(['api.auth', 'admin'])->group(function () {
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::patch('/orders/{orderId}/confirm', [AdminController::class, 'confirmOrder']);
    
    Route::post('/courses', [AdminController::class, 'createCourse']);
    Route::put('/courses/{courseId}', [AdminController::class, 'updateCourse']);
    Route::post('/courses/{courseId}/videos', [AdminController::class, 'addVideo']);
    Route::put('/courses/{courseId}/videos/{videoId}', [AdminController::class, 'updateVideo']);
    Route::delete('/courses/{courseId}/videos/{videoId}', [AdminController::class, 'deleteVideo']);
});

