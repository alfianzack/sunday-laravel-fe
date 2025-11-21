@extends('layouts.app')

@section('title', 'Home - Sunday Class')

@section('content')
<div class="min-h-screen bg-amber-50">
    @if(!$user || $user['role'] !== 'admin')
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50 min-h-[90vh] flex items-center">
            <!-- Decorative circles -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-rose-200 rounded-full opacity-40 blur-3xl"></div>
            <div class="absolute top-20 left-1/3 w-24 h-24 bg-emerald-200 rounded-full opacity-35 blur-3xl"></div>
            <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-cyan-200 rounded-full opacity-35 blur-3xl"></div>
            
            <div class="container mx-auto px-4 py-20 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md px-4 py-2 rounded-full text-sm font-semibold text-slate-700 mb-4 shadow-sm">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Studio Sunday
                        </div>
                        
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                            <span class="text-slate-800">Selamat Datang di</span>
                            <br>
                            <span class="text-rose-400">Studio</span> <span class="text-cyan-500">Sunday</span>
                        </h1>
                        
                        <p class="text-xl md:text-2xl text-slate-600 leading-relaxed max-w-xl">
                            Di mana imajinasi menjadi nyata melalui ilustrasi magis! Bergabunglah dengan kami dalam menciptakan dunia yang mempesona untuk anak-anak dari segala usia.
                        </p>
                        
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-400 to-cyan-500 hover:from-cyan-500 hover:to-cyan-600 text-white font-semibold px-8 py-4 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Jelajahi Galeri
                            </a>
                        </div>

                        <!-- Stats Section -->
                        <div class="grid grid-cols-3 gap-6 pt-8">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-slate-800">{{ count($courses) }}+</div>
                                <div class="text-sm text-slate-600 font-medium">Materi Tersedia</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-slate-800">1000+</div>
                                <div class="text-sm text-slate-600 font-medium">Siswa Aktif</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-slate-800">50+</div>
                                <div class="text-sm text-slate-600 font-medium">Instruktur</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Courses Section -->
    <div class="bg-gradient-to-b from-amber-50 via-white to-white py-16">
        <div class="container mx-auto px-4">
            @if(!$user || $user['role'] !== 'admin')
                <div class="mb-12 text-center">
                    <div class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-200 to-rose-300 text-rose-700 px-6 py-3 rounded-full text-sm font-semibold mb-6 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Materi Terpopuler
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                        Pilih Materi yang Ingin Anda Pelajari
                    </h2>
                    <p class="text-slate-600 text-lg max-w-2xl mx-auto">
                        Temukan kursus yang sesuai dengan minat dan kebutuhan Anda.
                    </p>
                </div>
            @endif

            @if(count($courses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        @include('components.course-card', ['course' => $course])
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white/80 backdrop-blur-sm rounded-3xl shadow-lg border-4 border-cyan-200/50">
                    <div class="max-w-md mx-auto">
                        <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-rose-100 to-cyan-100 rounded-full mb-6">
                            <svg class="w-16 h-16 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">
                            Belum Ada Materi Tersedia
                        </h3>
                        <p class="text-slate-600 text-lg mb-8">
                            Belum ada materi tersedia saat ini. Silakan kembali lagi nanti untuk melihat konten baru.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

