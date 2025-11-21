@extends('layouts.app')

@section('title', 'My Enrollments - Sunday Class')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-8 text-gray-900">Materi Saya</h1>
        
        @if(empty($enrollments))
            <div class="bg-white rounded-2xl shadow-lg border-4 border-teal-200 p-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-orange-100 to-teal-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-4">Belum Ada Enrollment</h3>
                <p class="text-gray-600 text-lg mb-8">Anda belum terdaftar di materi manapun</p>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Jelajahi Materi
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($enrollments as $enrollment)
                    @php
                        // Enrollment data structure from API: fields are directly on enrollment object
                        $thumbnailUrl = $enrollment['thumbnail_url'] ?? null;
                        if ($thumbnailUrl && !str_starts_with($thumbnailUrl, 'http')) {
                            // Get base URL without /api
                            $apiUrl = config('services.api.url', 'http://localhost:5000/api');
                            $baseUrl = rtrim(str_replace('/api', '', $apiUrl), '/');
                            // Remove leading /api if present in thumbnail URL
                            $path = ltrim($thumbnailUrl, '/');
                            if (str_starts_with($path, 'api/')) {
                                $path = substr($path, 4);
                            }
                            $thumbnailUrl = $baseUrl . '/' . $path;
                        }
                        $progress = $enrollment['progress'] ?? 0;
                        $courseId = $enrollment['course_id'] ?? null;
                    @endphp
                    @if($courseId)
                    <a href="{{ route('enrollments.show', $courseId) }}" class="group">
                        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1">
                            @if($thumbnailUrl)
                                <div class="relative w-full h-48 bg-blue-100 overflow-hidden">
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $enrollment['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 group-hover:text-teal-600 transition-colors">
                                    {{ $enrollment['title'] ?? 'Course' }}
                                </h3>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Progress</span>
                                        <span class="font-semibold">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-teal-600 font-semibold">
                                    <span>Lanjutkan Belajar</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                    @else
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-900">Course tidak ditemukan</h3>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

