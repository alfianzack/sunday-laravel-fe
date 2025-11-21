@extends('layouts.app')

@section('title', 'Register - Sunday Class')

@section('content')
<div class="min-h-screen bg-yellow-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-2 text-gray-900">Buat Akun</h1>
                <p class="text-gray-600">Daftar sekarang dan mulai belajar</p>
            </div>
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('error') ?: $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                        placeholder="Nama Anda"
                        required
                    >
                </div>

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
                        minlength="6"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-2">Minimal 6 karakter</p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2"
                >
                    Daftar
                </button>
            </form>

            <p class="mt-6 text-center text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-semibold transition-colors">
                    Login di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

