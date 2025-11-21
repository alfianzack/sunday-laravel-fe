@extends('layouts.app')

@section('title', 'Admin Dashboard - Sunday Class')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">Admin Dashboard</h1>
                    <p class="text-white/90 text-lg">Selamat datang, <span class="font-semibold">{{ $user['name'] ?? 'Admin' }}</span></p>
                </div>
                <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Materi
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border-2 border-teal-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1 font-medium">Total Materi</p>
                        <p class="text-4xl font-bold text-teal-600">{{ $stats['totalCourses'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Materi tersedia</p>
                    </div>
                    <div class="bg-gradient-to-br from-teal-100 to-teal-200 p-4 rounded-2xl">
                        <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border-2 border-orange-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1 font-medium">Pending Orders</p>
                        <p class="text-4xl font-bold text-orange-600">{{ $stats['pendingOrders'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Menunggu konfirmasi</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-100 to-orange-200 p-4 rounded-2xl">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border-2 border-blue-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1 font-medium">Total Orders</p>
                        <p class="text-4xl font-bold text-blue-600">{{ $stats['totalOrders'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Semua pesanan</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-2xl">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="{{ route('admin.courses.index') }}" class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="bg-teal-100 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Kelola Materi</h3>
                        <p class="text-gray-600">Lihat dan kelola semua materi</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Kelola Pesanan</h3>
                        <p class="text-gray-600">Lihat dan konfirmasi pesanan</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Courses -->
        @if(count($courses) > 0)
            <div>
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Materi Terbaru</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach(array_slice($courses, 0, 6) as $course)
                        @include('components.course-card', ['course' => $course])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

