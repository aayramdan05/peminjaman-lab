@extends('layouts.app')
@section('header', 'Jadwal & Riwayat Peminjaman')
@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Jadwal Kalender Keseluruhan</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau seluruh jadwal peminjaman dari semua lab dalam bentuk kalender.</p>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <div id="calendar" class="w-full text-xs"></div>
    </div>

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Riwayat Booking (Tabel)</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar semua permohonan booking yang masuk beserta statusnya.</p>
        </div>
        <div>
            <a href="{{ route('admin.reservations.create') }}" class="px-5 py-2.5 bg-unpad-blue text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-all shadow-md shadow-blue-900/20 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Booking Baru
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8" x-data="bulkActions()">
        
        <!-- Confirmation Modal -->
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" aria-hidden="true" @click="showDeleteModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-headline" x-text="deleteModalTitle"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="deleteModalMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="executeDelete()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Hapus
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-unpad-blue sm:mt-0 sm:w-auto sm:text-sm">
                            Tidak, Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Header -->
        <div x-show="selectedIds.length > 0" x-transition class="bg-orange-50 border-b border-orange-100 px-6 py-3 flex items-center justify-between" style="display: none;">
            <span class="text-sm font-bold text-orange-800"><span x-text="selectedIds.length"></span> baris terpilih</span>
            <form action="{{ route('reservations.bulkDestroy') }}" method="POST" @submit.prevent="confirmDelete($event.target, 'Hapus Banyak Jadwal', 'Yakin ingin menghapus ' + selectedIds.length + ' booking terpilih sekaligus? Tindakan ini tidak dapat dibatalkan.')">
                @csrf @method('DELETE')
                <template x-for="id in selectedIds">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-4 py-1.5 bg-ui-danger text-white text-xs font-bold rounded-lg hover:bg-red-600 transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Terpilih
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <th class="px-6 py-4 w-12">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-unpad-blue focus:ring-unpad-blue">
                        </th>
                        <th class="px-6 py-4">Tgl Pengajuan</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Ruangan & Waktu</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($reservations as $reservation)
                        <tr class="hover:bg-gray-50/50 transition-colors group" :class="{'bg-blue-50/30': selectedIds.includes('{{ $reservation->id }}')}">
                            <td class="px-6 py-4">
                                <input type="checkbox" x-model="selectedIds" value="{{ $reservation->id }}" @change="updateSelectAll()" class="rounded border-gray-300 text-unpad-blue focus:ring-unpad-blue">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $reservation->created_at->format('d M Y') }}<br><span class="text-xs text-gray-400">{{ $reservation->created_at->format('H:i') }}</span></td>
                            
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $reservation->user->name }}</td>
                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-unpad-blue">
                                    {{ $reservation->lab->name }}
                                    @if(is_array($reservation->additional_labs) && count($reservation->additional_labs) > 0)
                                        <details class="group cursor-pointer mt-1">
                                            <summary class="text-xs text-unpad-secondary hover:underline font-bold">+{{ count($reservation->additional_labs) }} Lab Tambahan</summary>
                                            <div class="mt-2 space-y-1 bg-blue-50 p-2 rounded-lg border border-blue-100 absolute z-10 shadow-lg min-w-[150px]">
                                                @foreach($reservation->additional_labs as $addLabId)
                                                    <div class="text-xs font-bold text-unpad-blue border-b border-blue-100 last:border-0 pb-1 last:pb-0">
                                                        {{ isset($allLabs[$addLabId]) ? $allLabs[$addLabId]->name : 'Lab Tidak Ditemukan' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1 font-medium">
                                    @if(is_array($reservation->schedules) && count($reservation->schedules) > 1)
                                        <div class="flex items-center gap-1 mb-1">
                                            <svg class="w-3 h-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $reservation->start_time->format('d M') }} - {{ $reservation->end_time->format('d M Y') }} ({{ count($reservation->schedules) }} Hari)
                                        </div>
                                        <details class="group cursor-pointer">
                                            <summary class="text-unpad-secondary hover:underline">Lihat Jam per Hari</summary>
                                            <div class="mt-2 space-y-1 bg-gray-50 p-2 rounded-lg border border-gray-100 absolute z-10 shadow-lg">
                                                @foreach($reservation->schedules as $sched)
                                                    <div class="flex justify-between gap-4">
                                                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($sched['start_time'])->format('d M') }}</span>
                                                        <span class="text-unpad-blue">{{ \Carbon\Carbon::parse($sched['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($sched['end_time'])->format('H:i') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @else
                                        {{ $reservation->start_time->format('d M Y, H:i') }} - {{ $reservation->end_time->format('H:i') }}
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-bold max-w-[200px] truncate" title="{{ $reservation->reason }}">
                                    {{ $reservation->reason }}
                                </div>
                                <div class="mt-2 text-xs text-gray-500 border-t border-gray-100 pt-2">
                                    <p><span class="font-bold text-gray-700">PJ:</span> {{ $reservation->pj_nama ?: '-' }}</p>
                                    <p><span class="font-bold text-gray-700">HP:</span> {{ $reservation->pj_hp ?: '-' }}</p>
                                    @if($reservation->surat_path)
                                        <a href="{{ Storage::url($reservation->surat_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-unpad-blue hover:underline font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat Surat
                                        </a>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($reservation->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pending
                                    </span>
                                @elseif ($reservation->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-right space-y-2">
                                @if ($reservation->status === 'pending')
                                    <div class="flex items-center justify-end gap-2 mb-2">
                                        <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-3 py-1 text-xs font-bold bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 rounded-lg transition-colors">Setujui</button>
                                        </form>
                                        <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-3 py-1 text-xs font-bold bg-red-50 border border-red-200 text-red-700 hover:bg-red-100 rounded-lg transition-colors">Tolak</button>
                                        </form>
                                    </div>
                                @endif
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('reservations.edit', $reservation->id) }}" class="px-3 py-1 text-xs font-bold bg-blue-50 border border-blue-200 text-unpad-blue hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" @submit.prevent="confirmDelete($event.target, 'Hapus Booking', 'Yakin ingin menghapus booking atas nama {{ addslashes($reservation->pj_nama ?: $reservation->user->name) }} ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs font-bold bg-gray-100 border border-gray-200 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-bold">
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
            allDaySlot: false,
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
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

    document.addEventListener('alpine:init', () => {
        Alpine.data('bulkActions', () => ({
            selectedIds: [],
            selectAll: false,
            allIds: [
                @foreach($reservations as $reservation)
                    '{{ $reservation->id }}',
                @endforeach
            ],
            
            toggleAll() {
                if (this.selectAll) {
                    this.selectedIds = [...this.allIds];
                } else {
                    this.selectedIds = [];
                }
            },
            
            updateSelectAll() {
                this.selectAll = this.selectedIds.length === this.allIds.length && this.allIds.length > 0;
            },

            showDeleteModal: false,
            deleteModalTitle: '',
            deleteModalMessage: '',
            formToSubmit: null,

            confirmDelete(form, title, message) {
                this.formToSubmit = form;
                this.deleteModalTitle = title;
                this.deleteModalMessage = message;
                this.showDeleteModal = true;
            },

            executeDelete() {
                if(this.formToSubmit) {
                    this.formToSubmit.submit();
                }
            }
        }));
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
