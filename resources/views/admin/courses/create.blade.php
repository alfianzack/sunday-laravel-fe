@extends('layouts.app')

@section('title', 'Tambah Materi Baru - Admin - Sunday Class')

@section('content')
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

        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-8 max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 text-gray-900">Tambah Materi Baru</h1>
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Judul Materi *
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="Masukkan judul materi"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Deskripsi *
                        </label>
                        <textarea
                            name="description"
                            rows="5"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="Masukkan deskripsi materi"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Harga (Rp) *
                        </label>
                        <input
                            type="number"
                            name="price"
                            value="{{ old('price') }}"
                            min="0"
                            step="1000"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="0"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Thumbnail (JPG/PNG) *
                        </label>
                        <input
                            type="file"
                            name="thumbnail"
                            accept="image/*"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                        >
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF (Max: 2MB)</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Preview Video (File, URL, atau YouTube) - Opsional
                        </label>
                        <input
                            type="file"
                            name="preview_video"
                            accept="video/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 mb-2"
                        >
                        <p class="text-xs text-gray-600 mb-2 text-center">ATAU</p>
                        <input
                            type="url"
                            name="preview_video_url"
                            value="{{ old('preview_video_url') }}"
                            placeholder="https://example.com/video.mp4 atau https://youtube.com/watch?v=VIDEO_ID"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                        >
                        <p class="text-xs text-gray-500 mt-2">Dapat menggunakan file video, URL video, atau URL YouTube</p>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button
                            type="submit"
                            class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all"
                        >
                            Buat Materi
                        </button>
                        <a
                            href="{{ route('admin.courses.index') }}"
                            class="px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all"
                        >
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

