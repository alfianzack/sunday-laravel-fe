@extends('layouts.app')

@section('title', 'Admin Courses - Sunday Class')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Kelola Materi</h1>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Materi
            </a>
        </div>

        @if(count($courses) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                    @include('components.course-card', ['course' => $course])
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg border-4 border-teal-200 p-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-orange-100 to-teal-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-4">Belum Ada Materi</h3>
                <p class="text-gray-600 text-lg mb-8">Mulai dengan membuat materi pertama Anda</p>
                <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Materi Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

