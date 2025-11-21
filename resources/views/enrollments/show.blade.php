@extends('layouts.app')

@section('title', ($enrollment['title'] ?? 'Enrollment') . ' - Sunday Class')

@section('content')
@php
    // API returns course data with nested enrollment object
    $progressPercent = $enrollment['enrollment']['progress'] ?? 0;
    $totalVideos = count($enrollment['videos'] ?? []);
    $watchedCount = $totalVideos > 0 ? floor(($progressPercent / 100) * $totalVideos) : 0;
    
    // Get video index from URL parameter if exists
    $videoParam = request()->query('video');
    $currentVideoIndex = $videoParam !== null && is_numeric($videoParam) 
        ? min(max(0, (int)$videoParam), max(0, $totalVideos - 1))
        : min($watchedCount, max(0, $totalVideos - 1));
    
    $currentVideo = ($enrollment['videos'] ?? [])[$currentVideoIndex] ?? null;
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Progress Bar at Top -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h1 class="text-lg font-bold text-gray-900 truncate">{{ $enrollment['title'] ?? 'Course' }}</h1>
                </div>
                <div class="flex items-center gap-4 min-w-[200px]">
                    <div class="flex-1 min-w-[120px]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-gray-600">Progress</span>
                            <span class="text-xs font-bold text-teal-600">{{ $progressPercent }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-600 whitespace-nowrap">
                        {{ $watchedCount }}/{{ $totalVideos }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-[calc(100vh-73px)]">
        <!-- Sidebar - Video List -->
        <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Daftar Video</h2>
                <div class="space-y-2" id="video-list">
                    @foreach($enrollment['videos'] ?? [] as $index => $video)
                        @php
                            $isWatched = $index < $watchedCount;
                            $isActive = $index === $currentVideoIndex;
                        @endphp
                        <button
                            onclick="selectVideo({{ $index }})"
                            class="w-full text-left p-4 rounded-xl transition-all video-item {{ $isActive ? 'bg-teal-500 text-white shadow-lg' : ($isWatched ? 'bg-green-50 hover:bg-green-100 text-gray-900 border border-green-200' : 'bg-gray-50 hover:bg-gray-100 text-gray-900 border border-gray-200') }}"
                            data-index="{{ $index }}"
                        >
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm {{ $isActive ? 'bg-white/20 text-white' : ($isWatched ? 'bg-green-500 text-white' : 'bg-teal-100 text-teal-600') }}">
                                    @if($isWatched && !$isActive)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span>{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm mb-1 line-clamp-2 {{ $isActive ? 'text-white' : 'text-gray-900' }}">
                                        {{ $video['title'] }}
                                    </p>
                                    @if(isset($video['duration']))
                                        <div class="flex items-center gap-1 text-xs {{ $isActive ? 'text-white/80' : 'text-gray-600' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ floor($video['duration'] / 60) }}:{{ str_pad($video['duration'] % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content - Video Player -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            @if($currentVideo)
                <div class="max-w-5xl mx-auto p-8">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Video Header -->
                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-8 py-6 border-b border-gray-200">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg shadow-md {{ $currentVideoIndex < $watchedCount ? 'bg-green-500 text-white' : 'bg-teal-500 text-white' }}">
                                    @if($currentVideoIndex < $watchedCount)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span>{{ $currentVideoIndex + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h2 class="text-2xl font-bold text-gray-900">{{ $currentVideo['title'] }}</h2>
                                        @if($currentVideoIndex < $watchedCount)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                    @if(isset($currentVideo['description']))
                                        <p class="text-gray-600 mb-3">{{ $currentVideo['description'] }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        @if(isset($currentVideo['duration']))
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Durasi: {{ floor($currentVideo['duration'] / 60) }}:{{ str_pad($currentVideo['duration'] % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Video {{ $currentVideoIndex + 1 }} dari {{ $totalVideos }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video Player -->
                        <div class="p-8">
                            <div class="rounded-xl overflow-hidden shadow-lg bg-gray-900">
                                @php
                                    $videoUrl = $currentVideo['video_url'] ?? '';
                                    $isYouTube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
                                    if (!$isYouTube && $videoUrl && !str_starts_with($videoUrl, 'http')) {
                                        $videoUrl = config('services.api.url', 'http://localhost:5000') . str_replace('/api', '', $videoUrl);
                                    }
                                @endphp
                                
                                @if($isYouTube)
                                    @php
                                        // Convert YouTube URL to embed format
                                        if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                            $videoId = explode('v=', $videoUrl)[1] ?? '';
                                            $videoId = explode('&', $videoId)[0];
                                            $videoUrl = 'https://www.youtube.com/embed/' . $videoId;
                                        } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                            $videoId = explode('youtu.be/', $videoUrl)[1] ?? '';
                                            $videoId = explode('?', $videoId)[0];
                                            $videoUrl = 'https://www.youtube.com/embed/' . $videoId;
                                        }
                                    @endphp
                                    <div class="relative bg-gray-900 w-full" style="padding-bottom: 56.25%; height: 0;">
                                        <div class="absolute top-0 left-0 w-full" style="height: 100%; min-height: 650px;">
                                            <iframe
                                                src="{{ $videoUrl }}"
                                                title="{{ $currentVideo['title'] }}"
                                                class="w-full h-full"
                                                style="min-height: 650px;"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen
                                            ></iframe>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-900 w-full">
                                        <video
                                            src="{{ $videoUrl }}"
                                            controls
                                            class="w-full"
                                            style="min-height: 650px; height: auto; max-height: 80vh;"
                                            id="video-player"
                                            onended="handleVideoEnd()"
                                        ></video>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-600 text-lg">Tidak ada video tersedia</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    const courseId = {{ request()->route('courseId') }};
    const videos = @json($enrollment['videos'] ?? []);
    let currentVideoIndex = {{ $currentVideoIndex }};
    let watchedCount = {{ $watchedCount }};
    const totalVideos = {{ $totalVideos }};

    function selectVideo(index) {
        currentVideoIndex = index;
        updateVideoDisplay();
        updateVideoList();
    }

    function updateVideoDisplay() {
        const currentVideo = videos[currentVideoIndex];
        if (!currentVideo) return;

        // Update video player
        const videoUrl = currentVideo.video_url;
        const isYouTube = videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be');
        
        let embedUrl = videoUrl;
        if (isYouTube) {
            if (videoUrl.includes('youtube.com/watch?v=')) {
                const videoId = videoUrl.split('v=')[1]?.split('&')[0];
                embedUrl = 'https://www.youtube.com/embed/' + videoId;
            } else if (videoUrl.includes('youtu.be/')) {
                const videoId = videoUrl.split('youtu.be/')[1]?.split('?')[0];
                embedUrl = 'https://www.youtube.com/embed/' + videoId;
            }
            
            // Reload page to show new video
            window.location.href = '{{ route("enrollments.show", request()->route("courseId")) }}?video=' + index;
        } else {
            const videoPlayer = document.getElementById('video-player');
            if (videoPlayer) {
                const fullUrl = videoUrl.startsWith('http') ? videoUrl : '{{ config("services.api.url", "http://localhost:5000") }}' + videoUrl.replace('/api', '');
                videoPlayer.src = fullUrl;
            }
        }
    }

    function updateVideoList() {
        document.querySelectorAll('.video-item').forEach((item, index) => {
            const isWatched = index < watchedCount;
            const isActive = index === currentVideoIndex;
            
            item.className = 'w-full text-left p-4 rounded-xl transition-all video-item ' + 
                (isActive ? 'bg-teal-500 text-white shadow-lg' : 
                 (isWatched ? 'bg-green-50 hover:bg-green-100 text-gray-900 border border-green-200' : 
                  'bg-gray-50 hover:bg-gray-100 text-gray-900 border border-gray-200'));
        });
    }

    async function handleVideoEnd() {
        if (currentVideoIndex >= watchedCount) {
            watchedCount = currentVideoIndex + 1;
            const newProgress = Math.min(100, Math.round((watchedCount / totalVideos) * 100));
            
            try {
                const response = await fetch('{{ route("enrollments.update-progress", request()->route("courseId")) }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ progress: newProgress })
                });
                
                if (response.ok) {
                    // Auto move to next video if available
                    if (currentVideoIndex < totalVideos - 1) {
                        selectVideo(currentVideoIndex + 1);
                    }
                    // Reload page to update progress
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error updating progress:', error);
            }
        }
    }

    // Handle video parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const videoParam = urlParams.get('video');
    if (videoParam !== null) {
        const videoIndex = parseInt(videoParam);
        if (videoIndex >= 0 && videoIndex < totalVideos) {
            currentVideoIndex = videoIndex;
        }
    }
</script>
@endpush
@endsection

