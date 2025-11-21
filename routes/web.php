<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminOrderController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Courses
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

// Cart (Protected)
Route::middleware('api.auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{courseId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{courseId}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Enrollments
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{courseId}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::patch('/enrollments/{courseId}/progress', [EnrollmentController::class, 'updateProgress'])->name('enrollments.update-progress');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['api.auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin Courses
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}', [AdminCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::post('/courses/{courseId}/videos', [AdminCourseController::class, 'addVideo'])->name('courses.videos.add');
    Route::put('/courses/{courseId}/videos/{videoId}', [AdminCourseController::class, 'updateVideo'])->name('courses.videos.update');
    Route::delete('/courses/{courseId}/videos/{videoId}', [AdminCourseController::class, 'deleteVideo'])->name('courses.videos.delete');
    
    // Admin Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{orderId}/confirm', [AdminOrderController::class, 'confirm'])->name('orders.confirm');
});
