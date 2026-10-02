@extends('layouts.frontend')

@section('title', 'Wizard Peminjaman Lab ' . $lab->name)

@section('content')
<div class="bg-ui-bg min-h-screen py-12" x-data="bookingWizard()" x-init="initCalendar()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('labs.show', $lab->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-unpad-blue transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Detail Lab
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden">
            
            <!-- Wizard Header (Steps) -->
            <div class="bg-gray-50 border-b border-gray-100 px-4 md:px-8 py-6 overflow-x-auto">
                <div class="flex items-center justify-between min-w-[500px]">
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center flex-1 last:flex-none">
                            <div class="flex flex-col items-center">
                                <div :class="{'bg-unpad-blue text-white shadow-lg shadow-blue-900/20 scale-110': step === i, 'bg-unpad-blue/20 text-unpad-blue': step > i, 'bg-gray-200 text-gray-400': step < i}" class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2 transition-all duration-300" x-text="i"></div>
                                <span class="text-xs font-bold uppercase tracking-wider transition-colors duration-300" :class="{'text-unpad-blue': step >= i, 'text-gray-400': step < i}" x-text="stepLabels[i-1]"></span>
                            </div>
                            <div x-show="i < 4" class="flex-1 h-1 bg-gray-200 rounded-full mx-2 md:mx-4 overflow-hidden relative top-[-10px]">
                                <div class="h-full bg-unpad-blue transition-all duration-500" :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="p-8 md:p-12">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-bold flex flex-col gap-1">
                        @foreach($errors->all() as $error)
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('reservations.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lab_id" value="{{ $lab->id }}">
                    
                    <!-- Hidden inputs dynamically generated for schedules -->
                    <template x-for="(date, index) in selectedDates" :key="index">
                        <div>
                            <input type="hidden" :name="'schedules['+index+'][start_time]'" :value="getStartTimeForDate(index)">
                            <input type="hidden" :name="'schedules['+index+'][end_time]'" :value="getEndTimeForDate(index)">
                        </div>
                    </template>
                    
                    <!-- Step 1: Pilih Tanggal (Calendar) & Lab Tambahan -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms style="display: none;">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Pilih Lab Tambahan & Tanggal</h2>
                        <p class="text-gray-500 mb-6">Anda sedang meminjam Lab <span class="font-bold text-unpad-blue">{{ $lab->name }}</span>. Anda juga dapat memilih lab lain jika diperlukan, kemudian pilih tanggal di kalender.</p>

                        <div class="mb-6 bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tambah Ruang Lab Lainnya (Opsional)</label>
                            <p class="text-xs text-gray-500 mb-3">Tekan CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu lab.</p>
                            <select name="additional_labs[]" x-model="selectedAdditionalLabs" multiple class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-white p-3 min-h-[100px] shadow-sm">
                                @foreach($allLabs as $otherLab)
                                    <option value="{{ $otherLab->id }}">{{ $otherLab->name }} (Kapasitas: {{ $otherLab->capacity }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <button type="button" @click="showRecurringPanel = !showRecurringPanel" class="text-sm font-bold flex items-center gap-2 text-unpad-blue hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-lg transition-colors border border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Buat Jadwal Rutin (Praktikum)
                            </button>
                            
                            <div x-show="showRecurringPanel" x-transition.opacity class="mt-3 bg-white p-5 rounded-xl border border-gray-200 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 items-end" style="display: none;">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Hari Rutin</label>
                                    <select x-model="recurringDay" class="w-full rounded-lg border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue">
                                        <option value="1">Senin</option>
                                        <option value="2">Selasa</option>
                                        <option value="3">Rabu</option>
                                        <option value="4">Kamis</option>
                                        <option value="5">Jumat</option>
                                        <option value="6">Sabtu</option>
                                        <option value="0">Minggu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Tanggal Mulai</label>
                                    <input type="date" x-model="recurringStart" class="w-full rounded-lg border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Tanggal Selesai</label>
                                    <input type="date" x-model="recurringEnd" class="w-full rounded-lg border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue">
                                </div>
                                <div>
                                    <button type="button" @click="generateRecurring()" class="w-full bg-unpad-secondary text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors shadow-md">
                                        Tandai Otomatis
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50 shadow-inner mb-6">
                            <div id="calendar"></div>
                        </div>

                        <div class="flex justify-between items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <p class="text-sm font-bold text-unpad-blue" x-text="selectedDates.length > 0 ? selectedDates.length + ' Hari Terpilih' : 'Belum ada hari terpilih'"></p>
                            <button type="button" @click="selectedDates.length > 0 ? step = 2 : alert('Silakan pilih tanggal terlebih dahulu!')" class="bg-unpad-blue text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all">
                                Lanjutkan &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Pilih Jam -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Pengaturan Waktu & Jam</h2>
                        <p class="text-gray-500 mb-6">Tentukan jam mulai dan selesai untuk <span class="font-bold text-unpad-blue" x-text="selectedDates.length"></span> hari yang Anda pilih.</p>
                        
                        <template x-if="selectedDates.length > 1">
                            <div class="mb-8 p-4 bg-orange-50 border border-orange-100 rounded-2xl flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Gunakan jam yang sama setiap harinya?</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Jika dimatikan, Anda bisa mengatur jam secara manual per hari.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="sameTimeEveryday" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-unpad-secondary"></div>
                                </label>
                            </div>
                        </template>

                        <!-- Single Time Settings (If sameTimeEveryday is true OR only 1 day selected) -->
                        <div x-show="sameTimeEveryday || selectedDates.length === 1" class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:border-unpad-blue/30 transition-colors">
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-4">
                                    <svg class="w-5 h-5 text-unpad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Jam Mulai (Semua Hari)
                                </label>
                                <input type="time" x-model="globalStartTime" required class="block w-full rounded-xl border-gray-200 bg-white px-4 py-4 text-xl font-extrabold text-center text-unpad-blue focus:ring-2 focus:ring-unpad-blue transition-all shadow-sm">
                            </div>
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:border-unpad-blue/30 transition-colors">
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-4">
                                    <svg class="w-5 h-5 text-ui-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Jam Selesai (Semua Hari)
                                </label>
                                <input type="time" x-model="globalEndTime" required class="block w-full rounded-xl border-gray-200 bg-white px-4 py-4 text-xl font-extrabold text-center text-unpad-blue focus:ring-2 focus:ring-unpad-blue transition-all shadow-sm">
                            </div>
                        </div>

                        <!-- Multi Time Settings (If sameTimeEveryday is false AND > 1 day selected) -->
                        <div x-show="!sameTimeEveryday && selectedDates.length > 1" class="space-y-4 mb-10 max-h-[40vh] overflow-y-auto pr-2">
                            <template x-for="(date, index) in selectedDates" :key="index">
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="font-bold text-gray-900 w-1/3 flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-unpad-blue/10 text-unpad-blue flex items-center justify-center text-xs" x-text="index + 1"></div>
                                        <span x-text="formatDate(date)"></span>
                                    </div>
                                    <div class="flex items-center gap-3 flex-1">
                                        <input type="time" x-model="dailyTimes[index].start" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 text-sm font-bold text-center text-gray-700 focus:ring-2 focus:ring-unpad-blue transition-all shadow-sm">
                                        <span class="text-gray-400 font-bold">-</span>
                                        <input type="time" x-model="dailyTimes[index].end" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 text-sm font-bold text-center text-gray-700 focus:ring-2 focus:ring-unpad-blue transition-all shadow-sm">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between mt-10">
                            <button type="button" @click="step = 1" class="text-gray-500 font-bold px-6 py-3 hover:text-gray-800 transition rounded-xl hover:bg-gray-100">
                                &larr; Kembali
                            </button>
                            <button type="button" @click="validateTime() ? step = 3 : null" class="bg-unpad-blue text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                                Lanjutkan <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Tujuan & Berkas -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Detail Peminjam & Berkas</h2>
                        <p class="text-gray-500 mb-8">Lengkapi data penanggung jawab, tujuan kegiatan, dan unggah surat permohonan resmi Anda.</p>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Penanggung Jawab</label>
                                    <input type="text" name="pj_nama" x-model="pj_nama" required class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-2 focus:ring-unpad-blue transition-all" placeholder="Masukkan nama lengkap">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">No. HP / WhatsApp (Aktif)</label>
                                    <input type="text" name="pj_hp" x-model="pj_hp" required class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-2 focus:ring-unpad-blue transition-all" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Tujuan / Keperluan Kegiatan</label>
                                <textarea name="reason" x-model="reason" rows="3" required class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:bg-white focus:border-unpad-blue focus:ring-2 focus:ring-unpad-blue transition-all" placeholder="Misal: Pelatihan Asisten Laboratorium..."></textarea>
                            </div>

                            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 border-dashed">
                                <label class="block text-sm font-bold text-unpad-blue mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Unggah Surat Peminjaman Resmi
                                </label>
                                <p class="text-xs text-gray-500 mb-4">Surat bertanda tangan kaprodi/dosen pembimbing. Format PDF, JPG, atau PNG (Maks 2MB).</p>
                                <input type="file" name="surat" id="suratInput" accept=".pdf,image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-unpad-blue file:text-white hover:file:bg-blue-900 transition-colors cursor-pointer bg-white border border-gray-200 rounded-xl shadow-sm">
                            </div>
                        </div>

                        <div class="flex justify-between mt-10">
                            <button type="button" @click="step = 2" class="text-gray-500 font-bold px-6 py-3 hover:text-gray-800 transition rounded-xl hover:bg-gray-100">
                                &larr; Kembali
                            </button>
                            <button type="button" @click="validateForm() ? step = 4 : null" class="bg-unpad-blue text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                                Cek Konfirmasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Konfirmasi -->
                    <div x-show="step === 4" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-green-100 text-ui-success rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-gray-900">Konfirmasi Pengajuan</h2>
                            <p class="text-gray-500 mt-2">Pastikan seluruh data di bawah ini sudah benar sebelum mengirimkan.</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 mb-8 shadow-inner">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                <div class="col-span-full border-b border-gray-200 pb-4 mb-2">
                                    <p class="text-xs font-bold text-unpad-blue uppercase tracking-wider mb-1">Ruangan Lab Terpilih</p>
                                    <p class="font-extrabold text-gray-900 text-xl">{{ $lab->name }}</p>
                                    <template x-if="selectedAdditionalLabs.length > 0">
                                        <p class="text-sm font-bold text-gray-600 mt-1">
                                            + <span x-text="selectedAdditionalLabs.length"></span> Lab Tambahan
                                        </p>
                                    </template>
                                </div>
                                
                                <div class="col-span-full">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jadwal Peminjaman (<span x-text="selectedDates.length"></span> Hari)</p>
                                    <div class="flex flex-col gap-2 max-h-40 overflow-y-auto">
                                        <template x-for="(date, index) in selectedDates" :key="index">
                                            <div class="flex justify-between items-center bg-white p-2 px-3 rounded-lg border border-gray-100 text-sm">
                                                <span class="font-bold text-gray-700" x-text="formatDate(date)"></span>
                                                <span class="font-bold text-unpad-blue"><span x-text="getStartTimeForDate(index).split('T')[1]"></span> - <span x-text="getEndTimeForDate(index).split('T')[1]"></span> WIB</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="col-span-full mt-2">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Penanggung Jawab</p>
                                    <p class="font-bold text-gray-900"><span x-text="pj_nama"></span> (HP: <span x-text="pj_hp"></span>)</p>
                                </div>

                                <div class="col-span-full">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tujuan / Keperluan</p>
                                    <p class="text-gray-900 text-sm italic font-medium">"<span x-text="reason"></span>"</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-50 border border-orange-100 rounded-2xl p-5 flex items-start gap-4">
                            <svg class="w-6 h-6 text-unpad-secondary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-orange-800 leading-relaxed font-semibold">
                                Dengan menekan tombol "Kirim Pengajuan", Anda menyetujui seluruh tata tertib penggunaan laboratorium. Setiap hari yang dipilih akan diproses sebagai jadwal yang sah.
                            </p>
                        </div>

                        <div class="mt-10 flex justify-between">
                            <button type="button" @click="step = 3" class="text-gray-500 font-bold px-6 py-3 hover:text-gray-800 transition rounded-xl hover:bg-gray-100">
                                &larr; Edit Data
                            </button>
                            <button type="submit" class="bg-ui-success text-white font-extrabold px-10 py-4 rounded-xl hover:bg-green-600 shadow-xl shadow-green-500/30 hover:-translate-y-1 transition-all duration-300 text-lg">
                                Kirim Pengajuan Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingWizard', () => ({
            step: 1,
            stepLabels: ['Tanggal', 'Jam', 'Berkas', 'Selesai'],
            
            selectedDates: [],
            selectedAdditionalLabs: [],
            
            // Time configurations
            sameTimeEveryday: true,
            globalStartTime: '08:00',
            globalEndTime: '12:00',
            dailyTimes: [],

            pj_nama: '',
            pj_hp: '',
            reason: '',
            
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

            validateTime() {
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

            validateForm() {
                if(!this.pj_nama || !this.pj_hp || !this.reason) {
                    alert("Mohon lengkapi semua data teks!");
                    return false;
                }
                const fileInput = document.getElementById('suratInput');
                if(fileInput.files.length === 0) {
                    alert("Mohon unggah Surat Resmi Peminjaman!");
                    return false;
                }
                return true;
            },

            // Recurring setup
            showRecurringPanel: false,
            recurringDay: 1, // Default Monday
            recurringStart: '',
            recurringEnd: '',

            generateRecurring() {
                if (!this.recurringStart || !this.recurringEnd) {
                    alert('Mohon isi tanggal mulai dan selesai praktikum!');
                    return;
                }
                
                let start = new Date(this.recurringStart);
                let end = new Date(this.recurringEnd);
                let targetDay = parseInt(this.recurringDay);
                let dates = [];
                
                if (start > end) {
                    alert('Tanggal selesai harus setelah tanggal mulai!');
                    return;
                }
                
                // Set start time to 00:00 to avoid timezone shift issues
                start.setHours(0,0,0,0);
                end.setHours(0,0,0,0);
                
                let current = new Date(start);
                while (current <= end) {
                    if (current.getDay() === targetDay) {
                        let d = new Date(current);
                        d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                        let dateStr = d.toISOString().split('T')[0];
                        dates.push(dateStr);
                    }
                    current.setDate(current.getDate() + 1);
                }
                
                if (dates.length === 0) {
                    alert('Tidak ditemukan hari yang cocok pada rentang tersebut.');
                    return;
                }
                
                // Merge and update
                this.selectedDates = [...new Set([...this.selectedDates, ...dates])].sort();
                this.updateDailyTimes();
                this.renderSelections();
                
                alert(`Berhasil menambahkan ${dates.length} hari ke jadwal!`);
                this.showRecurringPanel = false;
            },

            initCalendar() {
                var calendarEl = document.getElementById('calendar');
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    aspectRatio: 1.8,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    events: '/api/labs/{{ $lab->id }}/reservations',
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
                    selectOverlap: false, // Prevent selecting dates that have background events if any
                    validRange: {
                        start: new Date() // Cannot select past dates
                    },
                    select: (info) => {
                        let startDate = new Date(info.start);
                        let endDate = new Date(info.end);
                        let dayDiff = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
                        
                        if (dayDiff === 1) {
                            // Single day click: Toggle behavior
                            let d = new Date(startDate);
                            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                            let dateStr = d.toISOString().split('T')[0];
                            
                            if (this.selectedDates.includes(dateStr)) {
                                this.selectedDates = this.selectedDates.filter(d => d !== dateStr);
                            } else {
                                this.selectedDates.push(dateStr);
                            }
                        } else {
                            // Drag selection: Add all behavior
                            let dates = [];
                            let currentDate = new Date(startDate);
                            while(currentDate < endDate) {
                                let d = new Date(currentDate);
                                d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                                let dateStr = d.toISOString().split('T')[0];
                                dates.push(dateStr);
                                currentDate.setDate(currentDate.getDate() + 1);
                            }
                            // Merge with existing
                            this.selectedDates = [...new Set([...this.selectedDates, ...dates])];
                        }
                        
                        this.selectedDates.sort();
                        this.updateDailyTimes();
                        this.renderSelections();
                        this.calendar.unselect(); // Clear native drag highlight
                    }
                });
                this.calendar.render();
                
                setTimeout(() => { this.calendar.updateSize(); }, 200);
            },
            
            updateDailyTimes() {
                this.dailyTimes = this.selectedDates.map(() => ({
                    start: this.globalStartTime,
                    end: this.globalEndTime
                }));
            },
            
            renderSelections() {
                // Clear existing custom selections
                this.calendar.getEvents().forEach(event => {
                    if (event.extendedProps.isSelection) event.remove();
                });
                // Add new custom selections
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
    /* FullCalendar customizations to match Unpad theme */
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
    .fc .fc-button-primary {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
        font-weight: bold;
        border-radius: 0.5rem;
    }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }
    .fc .fc-button-primary:hover {
        background-color: #1e3a8a;
        opacity: 0.9;
    }
    .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        font-weight: 900;
        color: #1e3a8a;
    }
</style>
@endsection
