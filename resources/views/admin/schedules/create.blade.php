@extends('layouts.app')
@section('header', 'Buat Peminjaman Baru')
@section('content')

<div class="mb-6">
    <a href="{{ route('admin.schedules.index') }}" class="text-sm font-bold text-gray-500 hover:text-unpad-blue transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Jadwal
    </a>
</div>

<div class="bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden mb-8" x-data="adminBooking()" x-init="initCalendar()">
    <div class="bg-unpad-blue px-6 py-4 border-b border-gray-100">
        <h2 class="text-xl font-extrabold text-white">Formulir Tambah Booking (Admin)</h2>
    </div>

    <div class="p-6 md:p-8">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-bold flex flex-col gap-1">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.reservations.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data" @submit.prevent="validateForm() ? $event.target.submit() : null">
            @csrf

            <!-- Hidden inputs dynamically generated for schedules -->
            <template x-for="(date, index) in selectedDates" :key="index">
                <div>
                    <input type="hidden" :name="'schedules['+index+'][start_time]'" :value="getStartTimeForDate(index)">
                    <input type="hidden" :name="'schedules['+index+'][end_time]'" :value="getEndTimeForDate(index)">
                </div>
            </template>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Left Column: Lab & Dates -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-unpad-blue text-white flex items-center justify-center text-xs">1</span> 
                            Pilih Laboratorium
                        </h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lab Utama</label>
                            <select name="lab_id" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white">
                                <option value="">-- Pilih Lab Utama --</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->name }} (Kapasitas: {{ $lab->capacity }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lab Tambahan (Opsional)</label>
                            <select name="additional_labs[]" multiple class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white min-h-[100px]">
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Tekan CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-unpad-blue text-white flex items-center justify-center text-xs">2</span> 
                            Pilih Tanggal & Waktu
                        </h3>
                        
                        <div class="mb-4 bg-white p-3 rounded-xl border border-gray-100">
                            <div id="calendar" class="w-full text-xs"></div>
                        </div>
                        
                        <p class="text-sm font-bold text-unpad-blue mb-4" x-text="selectedDates.length > 0 ? selectedDates.length + ' Hari Terpilih' : 'Belum ada hari terpilih'"></p>

                        <div x-show="selectedDates.length > 0" x-transition>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jam Mulai (Semua Hari)</label>
                                    <input type="time" x-model="globalStartTime" class="w-full rounded-lg border-gray-200 text-sm font-bold focus:ring-unpad-blue">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jam Selesai (Semua Hari)</label>
                                    <input type="time" x-model="globalEndTime" class="w-full rounded-lg border-gray-200 text-sm font-bold focus:ring-unpad-blue">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="sameTimeEveryday" class="rounded border-gray-300 text-unpad-blue focus:ring-unpad-blue mr-2">
                                    <span class="text-xs font-bold text-gray-700">Gunakan jam yang sama untuk setiap hari</span>
                                </label>
                            </div>

                            <div x-show="!sameTimeEveryday && selectedDates.length > 1" class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(date, index) in selectedDates" :key="index">
                                    <div class="flex items-center justify-between bg-white p-2 rounded-lg border border-gray-100">
                                        <span class="text-xs font-bold text-gray-700" x-text="formatDate(date)"></span>
                                        <div class="flex items-center gap-2">
                                            <input type="time" x-model="dailyTimes[index].start" class="w-24 rounded border-gray-200 text-xs py-1">
                                            <span>-</span>
                                            <input type="time" x-model="dailyTimes[index].end" class="w-24 rounded border-gray-200 text-xs py-1">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form Details -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 h-full">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-unpad-blue text-white flex items-center justify-center text-xs">3</span> 
                            Detail Peminjam & Status
                        </h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Penanggung Jawab</label>
                                    <input type="text" name="pj_nama" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">No. HP / WA</label>
                                    <input type="text" name="pj_hp" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Keperluan Kegiatan</label>
                                <textarea name="reason" rows="3" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Surat Peminjaman (Opsional)</label>
                                <input type="file" name="surat" accept=".pdf,image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 transition-colors bg-white border border-gray-200 rounded-xl">
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Status Persetujuan</label>
                                <select name="status" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-unpad-blue focus:border-unpad-blue bg-white text-unpad-blue">
                                    <option value="approved">Disetujui Langsung (Approved)</option>
                                    <option value="pending">Menunggu (Pending)</option>
                                    <option value="rejected">Ditolak (Rejected)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Karena Anda admin, Anda bisa langsung menyetujui peminjaman ini.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('admin.schedules.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-100 transition-colors">Batal</a>
                            <button type="submit" class="bg-ui-success text-white font-extrabold px-8 py-3 rounded-xl hover:bg-green-600 shadow-lg shadow-green-500/20 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminBooking', () => ({
            selectedDates: [],
            
            sameTimeEveryday: true,
            globalStartTime: '08:00',
            globalEndTime: '12:00',
            dailyTimes: [],
            
            getStartTimeForDate(index) {
                const date = this.selectedDates[index];
                const time = (this.sameTimeEveryday || this.selectedDates.length === 1) ? this.globalStartTime : this.dailyTimes[index].start;
                return date + 'T' + time;
            },
            
            getEndTimeForDate(index) {
                const date = this.selectedDates[index];
                const time = (this.sameTimeEveryday || this.selectedDates.length === 1) ? this.globalEndTime : this.dailyTimes[index].end;
                return date + 'T' + time;
            },

            formatDate(dateStr) {
                if(!dateStr) return '-';
                const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                return new Date(dateStr).toLocaleDateString('id-ID', options);
            },

            validateForm() {
                if (this.selectedDates.length === 0) {
                    alert('Silakan pilih minimal 1 tanggal pada kalender!');
                    return false;
                }
                
                if (this.sameTimeEveryday || this.selectedDates.length === 1) {
                    if(this.globalStartTime >= this.globalEndTime) {
                        alert("Waktu selesai harus lebih besar dari waktu mulai.");
                        return false;
                    }
                } else {
                    for (let i = 0; i < this.selectedDates.length; i++) {
                        if (this.dailyTimes[i].start >= this.dailyTimes[i].end) {
                            alert(`Waktu tidak valid untuk tanggal ${this.formatDate(this.selectedDates[i])}`);
                            return false;
                        }
                    }
                }
                return true;
            },

            initCalendar() {
                var calendarEl = document.getElementById('calendar');
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    height: 400,
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    },
                    events: '{{ route("api.labs.all_reservations") }}',
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
                    },
                    selectable: true,
                    select: (info) => {
                        let startDate = new Date(info.start);
                        let endDate = new Date(info.end);
                        let dayDiff = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
                        
                        if (dayDiff === 1) {
                            let d = new Date(startDate);
                            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                            let dateStr = d.toISOString().split('T')[0];
                            
                            if (this.selectedDates.includes(dateStr)) {
                                this.selectedDates = this.selectedDates.filter(d => d !== dateStr);
                            } else {
                                this.selectedDates.push(dateStr);
                            }
                        } else {
                            let dates = [];
                            let currentDate = new Date(startDate);
                            while(currentDate < endDate) {
                                let d = new Date(currentDate);
                                d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                                let dateStr = d.toISOString().split('T')[0];
                                dates.push(dateStr);
                                currentDate.setDate(currentDate.getDate() + 1);
                            }
                            this.selectedDates = [...new Set([...this.selectedDates, ...dates])];
                        }
                        
                        this.selectedDates.sort();
                        this.updateDailyTimes();
                        this.renderSelections();
                        this.calendar.unselect();
                    }
                });
                this.calendar.render();
            },
            
            updateDailyTimes() {
                this.dailyTimes = this.selectedDates.map(() => ({
                    start: this.globalStartTime,
                    end: this.globalEndTime
                }));
            },
            
            renderSelections() {
                this.calendar.getEvents().forEach(event => {
                    if (event.extendedProps.isSelection) event.remove();
                });
                this.selectedDates.forEach(date => {
                    this.calendar.addEvent({
                        start: date,
                        display: 'background',
                        backgroundColor: '#2ECC71',
                        extendedProps: { isSelection: true }
                    });
                });
            }
        }));
    });
</script>
<style>
    .fc .fc-highlight {
        background: #2ECC71 !important;
        opacity: 0.3;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f3f4f6;
    }
    .fc-day-today {
        background-color: #eff6ff !important;
    }
    .fc .fc-toolbar-title {
        font-family: 'Nunito', sans-serif;
        font-size: 1rem;
        font-weight: 800;
        color: #1e3a8a;
    }
    .fc .fc-button-primary {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>
@endsection
