@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')
    
    <!-- 1. Catalog Section (Top) -->
    <div id="katalog" class="bg-ui-bg pt-16 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-unpad-blue tracking-tight">Eksplorasi Laboratorium</h2>
                <p class="mt-4 text-xl text-gray-600 font-medium max-w-2xl mx-auto">Pilih ruangan yang sesuai dengan kebutuhan spesifikasi dan kapasitas Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($labs as $lab)
                <div class="group cursor-pointer bg-white rounded-3xl p-3 shadow-card hover:shadow-soft transition-all duration-300">
                    <!-- Image Card -->
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden mb-4 bg-gray-100">
                        <!-- Image -->
                        @if($lab->image_path)
                            <img src="{{ Storage::url($lab->image_path) }}" alt="{{ $lab->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out">
                        @else
                            <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=1000&auto=format&fit=crop" alt="{{ $lab->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out">
                        @endif
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Availability Badge -->
                        <div class="absolute top-4 left-4">
                            <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold text-gray-800 shadow-sm flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-ui-success"></div>
                                Tersedia
                            </div>
                        </div>

                        <!-- Capacity Badge -->
                        <div class="absolute bottom-4 right-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <a href="{{ route('labs.show', $lab->id) }}" class="bg-white text-unpad-blue font-bold px-4 py-2 rounded-xl shadow-lg hover:bg-gray-50 text-sm flex items-center gap-2">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Content Info -->
                    <div class="px-2 pb-2">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-unpad-secondary transition-colors">{{ $lab->name }}</h3>
                            <span class="flex items-center text-sm font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-lg gap-1">
                                <svg class="w-4 h-4 text-unpad-secondary" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                                {{ $lab->capacity }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-3 truncate">{{ $lab->pc_specs }}</p>
                        
                        <!-- Spec Chips -->
                        <div class="flex flex-wrap gap-2">
                            @foreach(array_slice(explode(',', $lab->facilities), 0, 2) as $facility)
                            <span class="px-3 py-1 bg-blue-50 text-unpad-blue text-xs font-bold rounded-xl truncate">
                                {{ trim($facility) }}
                            </span>
                            @endforeach
                            @if(count(explode(',', $lab->facilities)) > 2)
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-xl">
                                +{{ count(explode(',', $lab->facilities)) - 2 }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2. How it Works (Booking Steps) -->
    <div class="bg-white py-24 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-unpad-blue tracking-tight">Cara Reservasi <span class="text-unpad-secondary">Lab</span></h2>
                <p class="mt-4 text-xl text-gray-600 font-medium max-w-2xl mx-auto">Empat langkah mudah untuk menggunakan fasilitas kami. Tanpa antre, tanpa repot.</p>
            </div>

            <!-- Steps Illustration -->
            <div class="relative max-w-5xl mx-auto">
                <!-- Connecting Dashed Line -->
                <div class="hidden md:block absolute top-12 left-[12%] right-[12%] h-0.5 border-t-2 border-dashed border-unpad-secondary/40 z-0"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    
                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-unpad-secondary to-orange-400 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30 mb-6 border-4 border-white">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-card border border-gray-100 text-center w-full min-h-[180px]">
                            <div class="text-unpad-secondary font-black text-4xl opacity-20 -mt-2 mb-2">01</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Pilih Lab</h3>
                            <p class="text-sm text-gray-500 font-medium">Masuk ke akun Anda dan jelajahi spesifikasi lab yang tersedia.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-white border-4 border-unpad-secondary rounded-full flex items-center justify-center shadow-lg mb-6">
                            <svg class="w-10 h-10 text-unpad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-card border border-gray-100 text-center w-full min-h-[180px]">
                            <div class="text-unpad-secondary font-black text-4xl opacity-20 -mt-2 mb-2">02</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Isi Wizard</h3>
                            <p class="text-sm text-gray-500 font-medium">Pilih jadwal kosong, isi tujuan peminjaman melalui form kami.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-white border-4 border-unpad-secondary rounded-full flex items-center justify-center shadow-lg mb-6">
                            <svg class="w-10 h-10 text-unpad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-card border border-gray-100 text-center w-full min-h-[180px]">
                            <div class="text-unpad-secondary font-black text-4xl opacity-20 -mt-2 mb-2">03</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Tunggu Approval</h3>
                            <p class="text-sm text-gray-500 font-medium">Operator akan menyetujui peminjaman Anda pada jam kerja.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-white border-4 border-unpad-secondary rounded-full flex items-center justify-center shadow-lg mb-6">
                            <svg class="w-10 h-10 text-unpad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-card border border-gray-100 text-center w-full min-h-[180px]">
                            <div class="text-unpad-secondary font-black text-4xl opacity-20 -mt-2 mb-2">04</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Gunakan Ruangan</h3>
                            <p class="text-sm text-gray-500 font-medium">Lab siap digunakan sesuai dengan jadwal yang telah disetujui.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 3. Global Calendar Section (Bottom, Smaller) -->
    <div class="bg-ui-bg py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-3">Informasi Jadwal Menyeluruh</h2>
                <p class="text-gray-600 font-medium">Warna <span class="text-unpad-secondary font-bold">Oranye</span> menandakan ruangan telah dipesan dan disetujui.</p>
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-soft">
                <div id="global-calendar" class="w-full min-h-[450px]"></div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('global-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            slotDuration: '01:00:00',
            allDaySlot: false,
            height: 550,
            expandRows: true,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            themeSystem: 'standard',
            events: '{{ route("api.labs.all_reservations") }}',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            eventDidMount: function(info) {
                if (info.event.extendedProps.pj) {
                    tippy(info.el, {
                        content: `
                            <div class="text-left text-sm p-1">
                                <div class="font-bold text-unpad-blue border-b border-gray-200 pb-1 mb-1">${info.event.title}</div>
                                <div class="mb-1"><span class="font-semibold text-gray-500">PJ:</span> ${info.event.extendedProps.pj}</div>
                                <div class="mb-1"><span class="font-semibold text-gray-500">Waktu:</span> ${info.event.extendedProps.time}</div>
                                <div class="text-xs text-gray-600 mt-2 italic">"${info.event.extendedProps.reason}"</div>
                            </div>
                        `,
                        allowHTML: true,
                        theme: 'light',
                        placement: 'top',
                    });
                }
            }
        });
        calendar.render();
    });
</script>
<style>
    /* Fullcalendar overrides for Unpad Theme */
    .fc .fc-toolbar-title {
        font-family: 'Nunito', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e3a8a;
    }
    .fc .fc-button-primary {
        background-color: #F3F4F6 !important;
        border-color: #E5E7EB !important;
        color: #4B5563 !important;
        font-weight: 700;
        border-radius: 0.75rem;
        text-transform: capitalize;
        font-family: 'Nunito', sans-serif;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #E5E7EB !important;
        border-color: #D1D5DB !important;
        color: #1e3a8a !important;
    }
    .fc .fc-col-header-cell-cushion {
        color: #4B5563;
        font-weight: 700;
        padding: 8px 0;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #F3F4F6;
    }
    .fc-event {
        border-radius: 6px;
        border: none !important;
        padding: 2px 4px;
        font-weight: 600;
    }
</style>
@endsection
