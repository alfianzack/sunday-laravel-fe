@extends('layouts.app')

@section('title', ($course['title'] ?? 'Course') . ' - Admin - Sunday Class')

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
    $previewVideoUrl = $course['preview_video_url'] ?? null;
@endphp

<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white py-12">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Materi
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-bold mb-2 text-gray-900">{{ $course['title'] ?? 'Course' }}</h1>
                    @if(isset($course['instructor_name']))
                        <p class="text-gray-600">Oleh: <span class="font-semibold">{{ $course['instructor_name'] }}</span></p>
                    @endif
                </div>
                <a href="{{ route('admin.courses.edit', $course['id']) }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Materi
                </a>
            </div>

            @if($thumbnailUrl)
                <div class="mb-6">
                    <img src="{{ $thumbnailUrl }}" alt="{{ $course['title'] }}" class="w-full max-w-2xl h-64 object-cover rounded-xl">
                </div>
            @endif

            <div class="mb-6">
                <h3 class="text-xl font-bold mb-2 text-gray-900">Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed">{{ $course['description'] ?? '' }}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-bold mb-2 text-gray-900">Harga</h3>
                <p class="text-2xl font-bold text-red-500">Rp {{ number_format($course['price'] ?? 0, 0, ',', '.') }}</p>
            </div>

            @if($previewVideoUrl)
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Preview Video</h3>
                    @php
                        $isYouTube = str_contains($previewVideoUrl, 'youtube.com') || str_contains($previewVideoUrl, 'youtu.be');
                        if ($isYouTube) {
                            if (str_contains($previewVideoUrl, 'youtube.com/watch?v=')) {
                                $videoId = explode('v=', $previewVideoUrl)[1] ?? '';
                                $videoId = explode('&', $videoId)[0];
                                $previewVideoUrl = 'https://www.youtube.com/embed/' . $videoId;
                            } elseif (str_contains($previewVideoUrl, 'youtu.be/')) {
                                $videoId = explode('youtu.be/', $previewVideoUrl)[1] ?? '';
                                $videoId = explode('?', $videoId)[0];
                                $previewVideoUrl = 'https://www.youtube.com/embed/' . $videoId;
                            }
                        } elseif (!str_starts_with($previewVideoUrl, 'http')) {
                            $previewVideoUrl = config('services.api.url', 'http://localhost:5000') . str_replace('/api', '', $previewVideoUrl);
                        }
                    @endphp
                    <div class="rounded-xl overflow-hidden shadow-lg bg-gray-900 max-w-4xl">
                        @if($isYouTube)
                            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                <iframe
                                    src="{{ $previewVideoUrl }}"
                                    title="Preview Video"
                                    class="absolute top-0 left-0 w-full h-full"
                                    style="min-height: 500px;"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <video src="{{ $previewVideoUrl }}" controls class="w-full" style="min-height: 500px;"></video>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Add Video Button -->
        <div class="mb-8">
            <button onclick="openAddVideoModal()" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Video Baru
            </button>
        </div>

        <!-- Videos List -->
        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Daftar Video ({{ count($course['videos'] ?? []) }})</h2>
            
            @if(empty($course['videos']))
                <div class="text-center py-12">
                    <p class="text-gray-600">Belum ada video untuk materi ini</p>
                </div>
            @else
                <div class="space-y-4" id="videos-list">
                    @foreach($course['videos'] ?? [] as $index => $video)
                        <div class="video-item bg-gray-50 rounded-xl p-6 border border-gray-200" data-video-id="{{ $video['id'] }}">
                            <!-- View Mode -->
                            <div class="video-view">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $video['title'] }}</h3>
                                        </div>
                                        @if(isset($video['description']))
                                            <p class="text-gray-600 text-sm mb-2 ml-13">{{ $video['description'] }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-sm text-gray-600 ml-13">
                                            @if(isset($video['duration']))
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Durasi: {{ floor($video['duration'] / 60) }}:{{ str_pad($video['duration'] % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                                </svg>
                                                <span>Urutan: {{ $video['order_index'] ?? $index + 1 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick="editVideo({{ $video['id'] }})" class="text-teal-500 hover:text-teal-700 hover:bg-teal-50 p-2 rounded-lg transition-all" title="Edit video">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.courses.videos.delete', [$course['id'], $video['id']]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus video ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Edit Mode (Hidden by default) -->
                            <div class="video-edit hidden">
                                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Video
                                    </h4>
                                    
                                    <form action="{{ route('admin.courses.videos.update', [$course['id'], $video['id']]) }}" method="POST" enctype="multipart/form-data" class="video-edit-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <label class="block text-gray-700 text-xs font-semibold mb-1">Judul Video *</label>
                                                <input type="text" name="title" value="{{ $video['title'] }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-gray-700 text-xs font-semibold mb-1">Deskripsi</label>
                                                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">{{ $video['description'] ?? '' }}</textarea>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-gray-700 text-xs font-semibold mb-1">Video (File atau URL) - Kosongkan jika tidak ingin mengubah</label>
                                                <input type="file" name="video" accept="video/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm mb-2">
                                                <input type="url" name="video_url" value="{{ $video['video_url'] ?? '' }}" placeholder="URL video atau YouTube" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-xs font-semibold mb-1">Durasi (detik)</label>
                                                <input type="number" name="duration" value="{{ $video['duration'] ?? '' }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-xs font-semibold mb-1">Urutan *</label>
                                                <input type="number" name="order_index" value="{{ $video['order_index'] ?? $index + 1 }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                            </div>
                                            <div class="md:col-span-2 flex gap-2 pt-2">
                                                <button type="submit" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Simpan
                                                </button>
                                                <button type="button" onclick="cancelEditVideo({{ $video['id'] }})" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Video Modal -->
<div id="addVideoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-2xl font-bold text-gray-900">Tambah Video Baru</h2>
            <button onclick="closeAddVideoModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.courses.videos.add', $course['id']) }}" method="POST" enctype="multipart/form-data" id="addVideoForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Judul Video *</label>
                        <input type="text" name="title" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Urutan</label>
                        <input type="number" name="order_index" value="{{ count($course['videos'] ?? []) + 1 }}" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Video URL (YouTube atau URL)</label>
                        <input type="url" name="video_url" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="https://youtube.com/...">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Atau Upload Video File</label>
                        <input type="file" name="video" accept="video/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="text-xs text-gray-500 mt-2">Format: MP4, AVI, MOV, WMV (Max: 100MB)</p>
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <button type="submit" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Tambah Video
                    </button>
                    <button type="button" onclick="closeAddVideoModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddVideoModal() {
        document.getElementById('addVideoModal').classList.remove('hidden');
        document.getElementById('addVideoModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAddVideoModal() {
        document.getElementById('addVideoModal').classList.add('hidden');
        document.getElementById('addVideoModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
        // Reset form
        document.getElementById('addVideoForm').reset();
    }

    // Close modal when clicking outside
    document.getElementById('addVideoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddVideoModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddVideoModal();
        }
    });

    function editVideo(videoId) {
        const videoItem = document.querySelector(`[data-video-id="${videoId}"]`);
        if (videoItem) {
            videoItem.querySelector('.video-view').classList.add('hidden');
            videoItem.querySelector('.video-edit').classList.remove('hidden');
        }
    }

    function cancelEditVideo(videoId) {
        const videoItem = document.querySelector(`[data-video-id="${videoId}"]`);
        if (videoItem) {
            videoItem.querySelector('.video-view').classList.remove('hidden');
            videoItem.querySelector('.video-edit').classList.add('hidden');
            // Reset form
            const form = videoItem.querySelector('.video-edit-form');
            if (form) {
                form.reset();
            }
        }
    }
</script>
@endpush
@endsection

