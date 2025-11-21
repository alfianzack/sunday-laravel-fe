@extends('layouts.app')

@section('title', 'Courses - Sunday Class')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold">Semua Materi</h1>
                </div>
                <p class="text-xl md:text-2xl text-white/90 leading-relaxed">
                    Jelajahi koleksi lengkap kursus pembelajaran video kami
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if(count($courses) > 0)
            <div class="mb-8 flex items-center justify-between bg-white rounded-xl shadow-md border-2 border-teal-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <p class="text-gray-700 font-medium">
                        Menampilkan <span class="font-bold text-teal-600">{{ count($courses) }}</span> materi tersedia
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                    @include('components.course-card', ['course' => $course])
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-lg border-4 border-teal-200">
                <div class="max-w-md mx-auto">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-orange-100 to-teal-100 rounded-full mb-6">
                        <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">Belum Ada Materi</h3>
                    <p class="text-gray-600 text-lg mb-8">
                        Belum ada materi tersedia saat ini. Silakan kembali lagi nanti.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

