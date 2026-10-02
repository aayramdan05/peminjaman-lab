@extends('layouts.app')
@section('header', 'Dashboard Utama')
@section('content')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-unpad-blue to-blue-900 rounded-xl p-5 sm:p-6 text-white shadow-sm mb-6 relative overflow-hidden">
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold mb-1">Halo, {{ Auth::user()->name }} 👋</h2>
                <p class="text-blue-200 text-sm">
                    @if(Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                        Berikut ringkasan aktivitas peminjaman lab hari ini.
                    @else
                        Pantau status peminjaman lab Anda di sini.
                    @endif
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/4 blur-xl"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <h3 class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-unpad-blue">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Disetujui</p>
                <h3 class="text-2xl font-extrabold text-gray-900">{{ $stats['approved'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-ui-success">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pending</p>
                <h3 class="text-2xl font-extrabold text-gray-900">{{ $stats['pending'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-ui-warning">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ditolak</p>
                <h3 class="text-2xl font-extrabold text-gray-900">{{ $stats['rejected'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-ui-danger">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-extrabold text-gray-900">
                @if (Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                    Antrean Persetujuan & Riwayat (Semua User)
                @else
                    Riwayat Peminjaman Terbaru
                @endif
            </h3>
            
            <!-- Future Filter Input Placeholder -->
            <div class="relative">
                <input type="text" placeholder="Cari..." class="pl-9 pr-4 py-2 border-gray-200 rounded-lg text-sm focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue w-64 bg-white shadow-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        @if ($reservations->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-gray-500 text-lg font-medium">Belum ada data peminjaman.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-bold">
                            <th class="px-6 py-4">Tgl Pengajuan</th>
                            @if (Auth::user()->role !== 'user')
                                <th class="px-6 py-4">Pemohon</th>
                            @endif
                            <th class="px-6 py-4">Ruangan & Waktu</th>
                            <th class="px-6 py-4">Tujuan</th>
                            <th class="px-6 py-4">Status</th>
                            @if (Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                                <th class="px-6 py-4 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($reservations as $reservation)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $reservation->created_at->format('d M Y') }}<br><span class="text-xs text-gray-400">{{ $reservation->created_at->format('H:i') }}</span></td>
                                
                                @if (Auth::user()->role !== 'user')
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $reservation->user->name }}</td>
                                @endif
                                
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
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
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
                                    @endif
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

                                @if (Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 text-right">
                                        @if ($reservation->status === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold bg-white border border-gray-200 text-ui-success hover:bg-green-50 hover:border-green-200 rounded-lg shadow-sm transition-colors">Setujui</button>
                                                </form>
                                                <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold bg-white border border-gray-200 text-ui-danger hover:bg-red-50 hover:border-red-200 rounded-lg shadow-sm transition-colors">Tolak</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">Telah Diproses</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Simple Pagination Placeholder -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
                Menampilkan {{ $reservations->count() }} data
            </div>
        @endif
    </div>

@endsection
