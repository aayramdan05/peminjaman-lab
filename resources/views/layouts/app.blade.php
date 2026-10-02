<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Peminjaman Lab</title>

    <!-- Fonts: Nunito (Rounded) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Tippy.js for tooltips -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS CDN -->
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
                        'soft': '0 10px 40px -10px rgba(0,0,0,0.05)',
                        'card': '0 2px 10px rgba(0,0,0,0.02)',
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-ui-bg font-sans text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:block shadow-soft flex flex-col h-screen">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-gray-50">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-unpad-blue rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">U</div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">PPBS <span class="text-unpad-blue">Dashboard</span></span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-semibold">Dashboard</span>
            </a>

            <a href="{{ route('home') }}#katalog" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-unpad-blue transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-semibold">Booking Baru</span>
            </a>

            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-8 mb-4 px-2">Manajemen</div>
                
                <a href="{{ route('admin.labs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.labs.*') || request()->routeIs('labs.edit') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-bold">Laboratorium</span>
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.schedules.*') && !request()->routeIs('admin.reservations.create') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-bold">Jadwal Booking</span>
                </a>

                <a href="{{ route('admin.reservations.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.reservations.create') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                    <svg class="w-5 h-5 text-ui-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="font-bold">Buat Booking (Admin)</span>
                </a>
            @endif

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-bold">Pengguna</span>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-unpad-blue text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-unpad-blue' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="font-bold">Laporan (Reports)</span>
                </a>
            @endif
        </div>
        
        <!-- Sidebar Footer / User Profile -->
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-10 h-10 rounded-full bg-unpad-blue/10 flex items-center justify-center text-unpad-blue font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize truncate">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 font-semibold hover:bg-red-50 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:pl-72 min-h-screen transition-all duration-300">
        <!-- Topbar -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 rounded-lg bg-gray-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">@yield('header', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <!-- Notifications (Placeholder) -->
                <button class="relative p-2 text-gray-400 hover:text-unpad-blue transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-ui-danger rounded-full ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-ui-success/10 border border-ui-success/20 text-green-800 rounded-xl font-medium flex items-center gap-3 shadow-sm animate-fade-in">
                    <svg class="w-5 h-5 text-ui-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-medium shadow-sm animate-fade-in">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
    
    @yield('scripts')
    
    @if(session('conflict_confirm'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Jadwal Bentrok!',
                    text: "Jadwal ini bentrok dengan {{ session('conflict_confirm')['count'] }} jadwal lain yang sudah disetujui. Bagaimana Anda ingin menimpanya?",
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonColor: '#1e3a8a', // unpad-blue
                    denyButtonColor: '#ea580c', // orange
                    cancelButtonColor: '#6b7280', // gray
                    confirmButtonText: 'Timpa Lab yang Bentrok Saja',
                    denyButtonText: 'Kosongkan Seluruh Gedung di Waktu Tersebut',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed || result.isDenied) {
                        let replaceMode = result.isConfirmed ? 'partial' : 'all';
                        
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{!! session('conflict_confirm')['action'] !!}";
                        form.innerHTML = `
                            @csrf 
                            @method(session('conflict_confirm')['method'])
                            <input type="hidden" name="status" value="approved">
                            <input type="hidden" name="confirmed_replace" value="1">
                            <input type="hidden" name="replace_mode" value="${replaceMode}">
                        `;
                        
                        @if(!empty(session('conflict_confirm')['data']))
                            @foreach(session('conflict_confirm')['data'] as $key => $val)
                                @if(is_array($val))
                                    @foreach($val as $i => $v)
                                        form.innerHTML += `<input type="hidden" name="{{ $key }}[{{ $i }}]" value="{{ $v }}">`;
                                    @endforeach
                                @else
                                    form.innerHTML += `<input type="hidden" name="{{ $key }}" value="{{ $val }}">`;
                                @endif
                            @endforeach
                        @endif
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        </script>
    @endif
</body>
</html>
