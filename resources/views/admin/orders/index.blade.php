@extends('layouts.app')

@section('title', 'Admin Orders - Sunday Class')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-8 text-gray-900">Kelola Pesanan</h1>
        
        @if(empty($orders))
            <div class="bg-white rounded-2xl shadow-lg border-4 border-teal-200 p-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-orange-100 to-teal-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-4">Belum Ada Pesanan</h3>
                <p class="text-gray-600 text-lg">Belum ada pesanan yang masuk</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Order #{{ $order['id'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}</p>
                                <p class="text-gray-600 text-sm mt-1">
                                    Oleh: <span class="font-semibold">{{ $order['user_name'] ?? 'N/A' }}</span>
                                    ({{ $order['user_email'] ?? 'N/A' }})
                                </p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($order['status'] === 'confirmed') bg-green-100 text-green-700
                                @elseif($order['status'] === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            @foreach($order['items'] ?? [] as $item)
                                <div class="flex justify-between text-gray-700">
                                    <span>{{ $item['title'] ?? 'Course' }}</span>
                                    <span class="font-semibold">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="font-bold text-xl">
                                <span>Total: </span>
                                <span class="text-orange-600">Rp {{ number_format($order['total'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if($order['status'] === 'pending')
                                <form action="{{ route('admin.orders.confirm', $order['id']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-xl transition-all">
                                        Konfirmasi Pembayaran
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

