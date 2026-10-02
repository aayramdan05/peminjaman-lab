@extends('layouts.frontend')

@section('title', 'Detail Lab ' . $lab->name)

@section('content')
<div class="bg-ui-bg min-h-screen pb-24">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        
        <!-- Header & Action Button -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-soft">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-unpad-blue tracking-tight">{{ $lab->name }}</h1>
                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500 font-bold">
                    <span class="flex items-center gap-1 bg-gray-50 px-3 py-1 rounded-lg">
                        <svg class="w-4 h-4 text-unpad-secondary" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        Kapasitas {{ $lab->capacity }} Orang
                    </span>
                    <span class="flex items-center gap-1 bg-gray-50 px-3 py-1 rounded-lg">
                        <svg class="w-4 h-4 text-unpad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Gedung PPBS D
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if (Auth::check() && (Auth::user()->role === 'operator' || Auth::user()->role === 'admin'))
                    <a href="{{ route('labs.edit', $lab->id) }}" class="flex items-center justify-center gap-2 font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 px-5 py-3 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                @endif
                
                @auth
                    <a href="{{ route('labs.book', $lab->id) }}" class="flex items-center justify-center gap-2 bg-unpad-secondary text-white font-extrabold text-lg px-8 py-3 rounded-xl hover:bg-orange-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Mulai Reservasi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-unpad-blue text-white font-extrabold text-lg px-8 py-3 rounded-xl hover:bg-blue-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Login untuk Reservasi
                    </a>
                @endauth
            </div>
        </div>

        @if (session('success'))
            <div class="mb-8 p-4 bg-ui-success/10 border border-ui-success/20 text-green-800 rounded-2xl font-bold animate-fade-in flex items-center gap-3">
                <svg class="w-6 h-6 text-ui-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
            <!-- Left: Interactive Layout (Denah) -->
            <div class="flex flex-col gap-5" x-data="layoutDisplay()">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-full flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2.5 px-1">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-unpad-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        Denah Ruangan
                    </h3>
                    
                    <div x-show="!hasData" class="py-8 text-center text-sm text-gray-500 font-bold bg-gray-50 rounded-xl border border-dashed border-gray-200 flex-1 flex items-center justify-center">
                        Denah interaktif belum disetel.
                    </div>
                    
                    <div x-show="hasData" class="overflow-x-auto bg-gray-50 p-4 rounded-xl border border-gray-100 w-full relative flex items-center justify-center">
                        <div class="relative min-w-max mx-auto p-5 flex items-center justify-center">
                            <!-- Whiteboard Absolute Positioning -->
                            <div x-show="whiteboardPosition === 'top'" class="absolute top-1 left-1/2 -translate-x-1/2 w-16 h-3 bg-green-600 rounded shadow-sm border border-green-800 text-white text-[5px] font-bold flex items-center justify-center">PAPAN TULIS</div>
                            <div x-show="whiteboardPosition === 'bottom'" class="absolute bottom-1 left-1/2 -translate-x-1/2 w-16 h-3 bg-green-600 rounded shadow-sm border border-green-800 text-white text-[5px] font-bold flex items-center justify-center">PAPAN TULIS</div>
                            <div x-show="whiteboardPosition === 'left'" class="absolute left-1 top-1/2 -translate-y-1/2 w-3 h-16 bg-green-600 rounded shadow-sm border border-green-800 text-white text-[5px] font-bold flex items-center justify-center" style="writing-mode: vertical-rl;">PAPAN TULIS</div>
                            <div x-show="whiteboardPosition === 'right'" class="absolute right-1 top-1/2 -translate-y-1/2 w-3 h-16 bg-green-600 rounded shadow-sm border border-green-800 text-white text-[5px] font-bold flex items-center justify-center" style="writing-mode: vertical-rl;">PAPAN TULIS</div>

                            <!-- Door Absolute Positioning -->
                            <div x-show="doorPosition === 'bottom-right'" class="absolute bottom-2 right-4 w-8 h-1.5 bg-amber-800 rounded-t border border-amber-950 flex items-center justify-center"><div class="w-1 h-1 rounded-full bg-yellow-400 ml-4"></div></div>
                            <div x-show="doorPosition === 'bottom-left'" class="absolute bottom-2 left-4 w-8 h-1.5 bg-amber-800 rounded-t border border-amber-950 flex items-center justify-center"><div class="w-1 h-1 rounded-full bg-yellow-400 mr-4"></div></div>
                            <div x-show="doorPosition === 'top-right'" class="absolute top-2 right-4 w-8 h-1.5 bg-amber-800 rounded-b border border-amber-950 flex items-center justify-center"><div class="w-1 h-1 rounded-full bg-yellow-400 ml-4"></div></div>
                            <div x-show="doorPosition === 'top-left'" class="absolute top-2 left-4 w-8 h-1.5 bg-amber-800 rounded-b border border-amber-950 flex items-center justify-center"><div class="w-1 h-1 rounded-full bg-yellow-400 mr-4"></div></div>

                            <div class="grid gap-1" :style="`grid-template-columns: repeat(${cols}, minmax(0, 1fr));`">
                                <template x-for="(cell, index) in grid" :key="index">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 border border-gray-300 rounded flex items-center justify-center shadow-sm bg-white"
                                         :class="{
                                             'bg-blue-50 border-blue-400 shadow-blue-500/20': cell.type === 'pc', 
                                             'bg-orange-50 border-orange-400 shadow-orange-500/20': cell.type === 'desk', 
                                         }">
                                         <div :style="`transform: rotate(${cell.rotation}deg);`" class="flex items-center justify-center w-full h-full">
                                             <div x-show="cell.type === 'pc'" class="flex flex-col items-center">
                                                 <div class="w-5 h-1 bg-gray-400 rounded-sm mb-0.5 shadow-sm"></div>
                                                 <div class="w-6 h-3 bg-blue-600 rounded-sm flex items-center justify-center shadow-sm">
                                                    <span class="text-white text-[5px] font-bold tracking-wider">PC</span>
                                                 </div>
                                             </div>
                                             <div x-show="cell.type === 'desk'" class="w-7 h-4 bg-orange-500 rounded-sm text-white text-[5px] font-bold flex items-center justify-center shadow-sm border border-orange-600">DOSEN</div>
                                         </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Specs & Facilities -->
            <div class="flex flex-col gap-5">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm h-fit">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-unpad-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        Spesifikasi Komputer
                    </h3>
                    <div class="text-gray-600 text-sm font-medium leading-relaxed whitespace-pre-line bg-gray-50 p-3 rounded-xl">
                        {{ $lab->pc_specs ?: 'Belum ada data spesifikasi.' }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm h-fit">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-unpad-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        Fasilitas Ruangan
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @if($lab->facilities)
                            @foreach(explode(',', $lab->facilities) as $facility)
                            <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-ui-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ trim($facility) }}
                            </div>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm font-medium">Belum ada data fasilitas.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Gallery Slider -->
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4 px-1">
                <h2 class="text-xl font-extrabold text-gray-900">Galeri Ruangan Lab</h2>
            </div>
            
            @php
                $allImages = [];
                if($lab->image_path) $allImages[] = $lab->image_path;
                if($lab->gallery_images && is_array($lab->gallery_images)) {
                    $allImages = array_merge($allImages, $lab->gallery_images);
                }
            @endphp
            
            @if(count($allImages) > 0)
                <div x-data="gallerySlider({{ count($allImages) }})" class="relative group bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    
                    <div class="overflow-hidden w-full relative h-[160px] md:h-[200px]">
                        <!-- Wrapper track that moves -->
                        <div class="flex transition-transform duration-700 ease-in-out h-full" 
                             :style="`transform: translateX(-${currentIndex * (100 / 3)}%); width: ${(total > 3 ? total : 3) * (100/3)}%;`">
                             
                            @foreach($allImages as $img)
                                <div class="w-1/3 px-2 h-full shrink-0">
                                    <div class="w-full h-full rounded-xl overflow-hidden bg-gray-200">
                                        <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(count($allImages) < 3)
                                @for($i = count($allImages); $i < 3; $i++)
                                <div class="w-1/3 px-2 h-full shrink-0">
                                    <div class="w-full h-full rounded-xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center">
                                        <span class="text-gray-400 font-bold text-xs">Slot Kosong</span>
                                    </div>
                                </div>
                                @endfor
                            @endif
                            
                        </div>
                    </div>
                    
                    <!-- Prev Button -->
                    <button @click="prev()" x-show="total > 3" class="absolute left-1 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white text-gray-700 shadow-md border border-gray-200 flex items-center justify-center hover:bg-unpad-blue hover:text-white hover:border-unpad-blue transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <!-- Next Button -->
                    <button @click="next()" x-show="total > 3" class="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white text-gray-700 shadow-md border border-gray-200 flex items-center justify-center hover:bg-unpad-blue hover:text-white hover:border-unpad-blue transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    
                </div>
            @else
                <div class="h-[180px] rounded-2xl bg-gray-50 flex items-center justify-center border border-dashed border-gray-200">
                    <span class="text-gray-500 text-sm font-bold">Belum Ada Foto Ruangan</span>
                </div>
            @endif
        </section>

        <!-- SECTION 4: Calendar & Reservations (Bottom) -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Calendar (Small/Compact) -->
            <div class="lg:col-span-5 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Jadwal Kalender</h2>
                    <span class="text-[10px] font-bold bg-orange-50 text-unpad-secondary px-2 py-1 rounded border border-orange-100">
                        Live 24 Jam
                    </span>
                </div>
                <!-- Small calendar styling via JS/CSS -->
                <div id="calendar" class="w-full text-xs"></div>
            </div>
            
            <!-- Reservation Info List -->
            <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-unpad-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Informasi Kegiatan Peminjam
                </h2>
                
                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @php
                        $upcomingReservations = $lab->reservations()
                            ->where('status', 'approved')
                            ->orderBy('start_time', 'asc')
                            ->get()
                            ->filter(function($r) {
                                return $r->end_time > now()->subDays(1); // include today and future
                            });
                    @endphp

                    @forelse($upcomingReservations as $res)
                        <div class="p-3.5 rounded-xl border {{ $res->start_time <= now() && $res->end_time >= now() ? 'border-orange-300 bg-orange-50' : 'border-gray-100 bg-gray-50' }} hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $res->pj_nama ?: 'Pengguna Sistem' }}</h4>
                                    <p class="text-xs font-medium text-gray-600 mb-1.5">{{ $res->reason ?: 'Kegiatan Akademik' }}</p>
                                    @if($res->start_time <= now() && $res->end_time >= now())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                            Sedang Berlangsung
                                        </span>
                                    @endif
                                </div>
                                <div class="text-left sm:text-right shrink-0">
                                    <div class="text-[10px] font-bold text-gray-400 mb-0.5">Mulai:</div>
                                    <div class="text-xs font-bold text-unpad-blue">{{ $res->start_time->format('d M Y, H:i') }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 mt-1.5 mb-0.5">Selesai:</div>
                                    <div class="text-xs font-bold text-gray-700">{{ $res->end_time->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center flex flex-col items-center justify-center border border-dashed border-gray-200 rounded-xl">
                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-gray-500 text-sm font-bold">Belum ada agenda terdekat.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('gallerySlider', (totalImages) => ({
            total: totalImages,
            currentIndex: 0,
            interval: null,
            init() {
                if(this.total > 3) {
                    this.startAutoSlide();
                }
            },
            startAutoSlide() {
                this.interval = setInterval(() => {
                    this.next();
                }, 3000);
            },
            next() {
                // If we display 3 images, the max index is total - 3
                let maxIndex = this.total - 3;
                if(maxIndex < 0) maxIndex = 0;
                
                if(this.currentIndex < maxIndex) {
                    this.currentIndex++;
                } else {
                    this.currentIndex = 0; // loop back
                }
            },
            prev() {
                if(this.currentIndex > 0) {
                    this.currentIndex--;
                } else {
                    let maxIndex = this.total - 3;
                    this.currentIndex = maxIndex > 0 ? maxIndex : 0; // loop to end
                }
            }
        }));

        Alpine.data('layoutDisplay', () => ({
            hasData: false,
            rows: 0,
            cols: 0,
            grid: [],
            doorPosition: 'none',
            whiteboardPosition: 'none',
            
            init() {
                let existing = '{!! $lab->layout_data ? addslashes(json_encode($lab->layout_data)) : "" !!}';
                if (existing) {
                    try {
                        let data = JSON.parse(existing);
                        if (data && data.rows && data.cols && data.grid.length > 0) {
                            this.hasData = true;
                            this.rows = data.rows;
                            this.cols = data.cols;
                            this.grid = data.grid;
                            if (data.doorPosition) this.doorPosition = data.doorPosition;
                            if (data.whiteboardPosition) this.whiteboardPosition = data.whiteboardPosition;
                        }
                    } catch (e) { console.error("Failed to parse layout data"); }
                }
            }
        }));
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
            allDaySlot: false,
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            themeSystem: 'standard',
            events: '{{ route("api.labs.reservations", $lab->id) }}',
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
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
