@extends('layouts.app')

@section('title', 'Login - Sunday Class')

@section('content')
<div class="min-h-screen bg-blue-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-2 text-gray-900">Selamat Datang</h1>
                <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
            </div>
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('error') ?: $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                        placeholder="nama@email.com"
                        required
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2"
                >
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-semibold transition-colors">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

