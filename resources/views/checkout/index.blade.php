@extends('layouts.app')

@section('title', 'Checkout - Sunday Class')

@section('content')
@php
    $total = array_sum(array_column($cart ?? [], 'price'));
@endphp

<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-8 text-gray-900">Checkout</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Upload Bukti Pembayaran</h2>
                    
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Bukti Pembayaran (Gambar)
                            </label>
                            <input
                                type="file"
                                name="payment_proof"
                                accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF (Max: 2MB)</p>
                        </div>
                        
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all"
                        >
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border-2 border-teal-100 p-6 sticky top-4">
                    <h2 class="text-2xl font-bold mb-6 text-blue-900">Ringkasan</h2>
                    <div class="space-y-4 mb-6">
                        @foreach($cart ?? [] as $item)
                            <div class="flex justify-between text-gray-700">
                                <span>{{ $item['title'] }}</span>
                                <span class="font-semibold">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-4 border-t-2 border-gray-200">
                        <div class="flex justify-between font-bold text-2xl">
                            <span class="text-gray-900">Total</span>
                            <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

