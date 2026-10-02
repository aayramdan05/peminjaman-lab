@extends('layouts.frontend')

@section('title', 'Login Peminjaman Lab')

@section('content')
<div class="min-h-screen bg-white flex -mt-20"> <!-- Negative margin to offset navbar padding -->
    
    <!-- Left: Illustration -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-unpad-blue overflow-hidden items-center justify-center">
        <div class="absolute inset-0 bg-gradient-to-br from-unpad-blue to-blue-900 opacity-90 z-10"></div>
        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
        
        <div class="relative z-20 max-w-lg px-12 text-white animate-slide-up">
            <h1 class="text-4xl font-bold mb-6 leading-tight">Sistem Reservasi Laboratorium PPBS</h1>
            <p class="text-lg text-blue-100 leading-relaxed mb-8">Platform terintegrasi untuk pengelolaan dan peminjaman fasilitas laboratorium komputer di Universitas Padjadjaran.</p>
            
            <div class="flex items-center gap-4">
                <div class="flex -space-x-4">
                    <img class="w-10 h-10 rounded-full border-2 border-unpad-blue" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-unpad-blue" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-unpad-blue" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                </div>
                <div class="text-sm font-medium text-blue-200">Bergabung dengan 1,000+ civitas akademika lainnya.</div>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 animate-fade-in">
        <div class="w-full max-w-md">
            
            <div class="mb-10 text-center lg:text-left">
                <div class="w-12 h-12 bg-unpad-blue rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg mb-6 mx-auto lg:mx-0">U</div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-gray-500 mt-2">Silakan masuk menggunakan kredensial Anda.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                    <input id="email" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="user@unpad.ac.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-bold text-gray-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-unpad-blue hover:text-blue-800 font-semibold transition" href="{{ route('password.request') }}">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="password" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-unpad-blue shadow-sm focus:ring-unpad-blue" name="remember">
                    <span class="ms-2 text-sm text-gray-600 font-medium">Ingat saya</span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-unpad-blue text-white font-bold text-lg py-4 rounded-xl hover:bg-blue-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Masuk
                    </button>
                </div>
                
                @if (Route::has('register'))
                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-unpad-blue hover:text-blue-800 transition">Daftar sekarang</a>
                </p>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
