@extends('layouts.app')

@section('title', 'Cart - Sunday Class')

@section('content')
@php
    $total = array_sum(array_column($cart ?? [], 'price'));
@endphp

<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <div class="bg-gradient-to-r from-orange-500 to-teal-500 text-white py-16 relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">Keranjang</h1>
                    <p class="text-white/90 text-lg">{{ count($cart ?? []) }} item dalam keranjang</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if(empty($cart))
            <div class="bg-white rounded-2xl shadow-lg border-4 border-teal-200 p-16 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-orange-100 to-teal-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-4">Keranjang Anda Kosong</h3>
                <p class="text-gray-600 text-xl mb-8">Mulai jelajahi dan tambahkan materi ke keranjang</p>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    Jelajahi Materi
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        @foreach($cart as $item)
                            @php
                                $thumbnailUrl = $item['thumbnail_url'] ?? null;
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
                            <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-6 flex gap-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                                @if($thumbnailUrl)
                                    <div class="relative w-40 h-28 bg-gradient-to-br from-teal-100 to-orange-100 rounded-xl overflow-hidden flex-shrink-0 border-2 border-teal-200">
                                        <img src="{{ $thumbnailUrl }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <a href="{{ route('courses.show', $item['id']) }}">
                                            <h3 class="text-xl font-bold hover:text-teal-600 transition-colors mb-2 text-gray-900">{{ $item['title'] }}</h3>
                                        </a>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $item['description'] ?? '' }}</p>
                                        <p class="text-2xl font-bold text-orange-600">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="self-start">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all border-2 border-red-200 hover:border-red-300" title="Hapus dari keranjang">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-teal-100 p-6 sticky top-4">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h2 class="text-2xl font-bold text-blue-900">Ringkasan</h2>
                        </div>
                        <div class="mb-6 space-y-4">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal ({{ count($cart) }} item)</span>
                                <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-4 border-t-2 border-gray-200">
                                <div class="flex justify-between font-bold text-2xl">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="block w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-4 px-6 rounded-xl text-center shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>Lanjutkan ke Checkout</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

