@extends('layouts.frontend')

@section('title', 'Daftar Akun Peminjaman Lab')

@section('content')
<div class="min-h-screen bg-white flex -mt-20"> <!-- Negative margin to offset navbar padding -->
    
    <!-- Left: Illustration -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-unpad-blue overflow-hidden items-center justify-center">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-unpad-blue opacity-90 z-10"></div>
        <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
        
        <div class="relative z-20 max-w-lg px-12 text-white animate-slide-up">
            <div class="mb-8 p-4 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20 inline-block">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </div>
            <h1 class="text-4xl font-bold mb-6 leading-tight">Manajemen Laboratorium yang Efisien</h1>
            <p class="text-lg text-blue-100 leading-relaxed mb-8">Daftarkan diri Anda untuk mendapatkan akses penuh ke fasilitas lab terbaik di Universitas Padjadjaran.</p>
        </div>
    </div>

    <!-- Right: Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 animate-fade-in my-20">
        <div class="w-full max-w-md">
            
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buat Akun Baru</h2>
                <p class="text-gray-500 mt-2">Daftar untuk mulai melakukan reservasi.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input id="name" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Email</label>
                    <input id="email" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="user@unpad.ac.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">Kata Sandi</label>
                    <input id="password" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5">Konfirmasi Sandi</label>
                    <input id="password_confirmation" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue transition-colors" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-unpad-blue text-white font-bold text-lg py-4 rounded-xl hover:bg-blue-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Daftar Akun
                    </button>
                </div>
                
                <p class="text-center text-sm text-gray-600 mt-6">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-unpad-blue hover:text-blue-800 transition">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
