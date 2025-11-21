@php
    $isAdmin = session('user') && session('user')['role'] === 'admin';
    $href = $isAdmin ? route('admin.courses.show', $course['id']) : route('courses.show', $course['id']);
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

<a href="{{ $href }}" class="group">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-red-300 h-full flex flex-col transform hover:-translate-y-1">
        @if($thumbnailUrl)
            <div class="relative w-full h-48 bg-blue-100 overflow-hidden">
                <div class="absolute top-3 right-3 z-10">
                    <span class="bg-white/90 backdrop-blur-sm text-red-600 text-xs font-bold px-3 py-1 rounded-full shadow-md">
                        Kursus
                    </span>
                </div>
                <img
                    src="{{ $thumbnailUrl }}"
                    alt="{{ $course['title'] }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
        @endif
        <div class="p-6 flex-1 flex flex-col">
            <div class="mb-3">
                <h3 class="text-xl font-bold mb-2 text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2">
                    {{ $course['title'] }}
                </h3>
                <p class="text-gray-600 line-clamp-2 text-sm flex-1">{{ $course['description'] ?? '' }}</p>
            </div>
            
            <div class="mt-auto pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-4">
                        @if(isset($course['enrollment_count']))
                            <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $course['enrollment_count'] }} Siswa</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="font-medium">Terverifikasi</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-red-500">
                        Rp {{ number_format($course['price'], 0, ',', '.') }}
                    </span>
                    <div class="flex items-center gap-1 text-yellow-500">
                        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">4.8</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>

