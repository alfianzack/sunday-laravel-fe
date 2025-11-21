@extends('layouts.app')

@section('title', ($course['title'] ?? 'Course') . ' - Sunday Class')

@section('content')
@php
    $thumbnailUrl = $course['thumbnail_url'] ?? null;
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
@endphp

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    @if($thumbnailUrl)
                        <div class="relative w-full h-80 bg-blue-100 overflow-hidden">
                            <img src="{{ $thumbnailUrl }}" alt="{{ $course['title'] }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-8">
                        <h1 class="text-4xl font-bold mb-4 text-gray-900">{{ $course['title'] }}</h1>
                        
                        @if(isset($course['instructor_name']))
                            <p class="text-gray-600 mb-6">Oleh: <span class="font-semibold">{{ $course['instructor_name'] }}</span></p>
                        @endif
                        
                        <div class="prose max-w-none mb-8">
                            <p class="text-gray-700 text-lg leading-relaxed">{{ $course['description'] ?? '' }}</p>
                        </div>

                        @if(isset($course['videos']) && count($course['videos']) > 0)
                            <div>
                                <h2 class="text-2xl font-bold mb-4 text-gray-900">Daftar Video ({{ count($course['videos']) }})</h2>
                                <div class="space-y-3">
                                    @foreach($course['videos'] as $index => $video)
                                        <div class="bg-gray-50 hover:bg-gray-100 p-4 rounded-xl transition-colors border border-gray-200">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-bold">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900 mb-1">{{ $video['title'] }}</p>
                                                    @if(isset($video['description']))
                                                        <p class="text-gray-600 text-sm mb-2">{{ $video['description'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-6 sticky top-4">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-red-500 mb-2">
                            Rp {{ number_format($course['price'], 0, ',', '.') }}
                        </div>
                        @if(isset($course['enrollment_count']))
                            <p class="text-gray-600 text-sm">{{ $course['enrollment_count'] }} siswa terdaftar</p>
                        @endif
                    </div>

                    @if($user)
                        @if(isset($course['is_enrolled']) && $course['is_enrolled'] && isset($course['id']))
                            <a href="{{ route('enrollments.show', $course['id']) }}" class="block w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-4 px-6 rounded-xl text-center shadow-lg hover:shadow-xl transition-all mb-4">
                                Lanjutkan Belajar
                            </a>
                        @elseif(isset($course['id']))
                            <form action="{{ route('cart.add', $course['id']) }}" method="POST" class="mb-4">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-4 px-6 rounded-xl text-center shadow-lg hover:shadow-xl transition-all">
                            Login untuk Membeli
                        </a>
                    @endif

                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Apa yang akan Anda pelajari:</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Materi lengkap dan terstruktur</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Akses seumur hidup</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sertifikat penyelesaian</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

