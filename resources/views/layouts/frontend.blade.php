<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Peminjaman Laboratorium PPBS D') - Universitas Padjadjaran</title>

    <!-- Fonts: Nunito (Rounded) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Tippy.js for tooltips -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />

    <!-- Tailwind CSS (via CDN fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Nunito"', 'sans-serif'],
                    },
                    colors: {
                        unpad: {
                            blue: '#1e3a8a', /* Navy Blue */
                            secondary: '#ea580c', /* Orange */
                        },
                        ui: {
                            bg: '#F7F9FC',
                            success: '#2ECC71',
                            danger: '#EF4444',
                            warning: '#F59E0B',
                            accent: '#FFC107'
                        }
                    },
                    boxShadow: {
                        'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F7F9FC; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="text-gray-800 antialiased font-sans selection:bg-unpad-blue selection:text-white">
    
    <!-- Top Navigation -->
    <nav class="glass-nav fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <!-- Placeholder UNPAD Logo -->
                    <div class="w-10 h-10 bg-unpad-blue rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">U</div>
                    <a href="/" class="text-xl font-bold text-gray-900 tracking-tight">PPBS <span class="text-unpad-blue">Lab</span></a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-sm font-semibold text-gray-600 hover:text-unpad-blue transition-colors">Home</a>
                    <a href="/#katalog" class="text-sm font-semibold text-gray-600 hover:text-unpad-blue transition-colors">Laboratorium</a>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4 ml-4 pl-4 border-l border-gray-200">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-unpad-blue transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-unpad-blue transition">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-unpad-blue text-white px-5 py-2.5 rounded-xl hover:bg-blue-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <!-- Mobile Menu Dropdown can be added here -->
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-unpad-blue rounded flex items-center justify-center text-white font-bold text-sm">U</div>
                        <span class="text-lg font-bold text-gray-900">PPBS Lab Unpad</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Sistem peminjaman laboratorium komputer modern yang dirancang untuk mendukung aktivitas akademik dan penelitian di Universitas Padjadjaran.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Tautan</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li><a href="/" class="hover:text-unpad-blue transition-colors">Beranda</a></li>
                        <li><a href="/#katalog" class="hover:text-unpad-blue transition-colors">Katalog Lab</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-unpad-blue transition-colors">Login Peminjam</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Bantuan</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li>Jam Operasional Operator: 08:00 - 16:00</li>
                        <li>Pengajuan Peminjaman: 24 Jam</li>
                        <li>Gedung PPBS D, Universitas Padjadjaran</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} Universitas Padjadjaran. Hak Cipta Dilindungi.
                </p>
                <div class="flex gap-4 mt-4 md:mt-0 text-gray-400">
                    <!-- Social icons placeholder -->
                    <svg class="w-5 h-5 hover:text-unpad-blue cursor-pointer transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
