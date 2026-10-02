@extends('layouts.app')
@section('header', 'Edit Booking')
@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.schedules.index') }}" class="text-sm font-bold text-gray-500 hover:text-unpad-blue transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Jadwal
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Data Peminjaman</h2>
        
        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Laboratorium</label>
                    <select name="lab_id" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                        @foreach($labs as $l)
                            <option value="{{ $l->id }}" {{ $reservation->lab_id == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                    <select name="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                        <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $reservation->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ $reservation->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" value="{{ $reservation->start_time->format('Y-m-d\TH:i') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" value="{{ $reservation->end_time->format('Y-m-d\TH:i') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penanggung Jawab</label>
                    <input type="text" name="pj_nama" value="{{ $reservation->pj_nama }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP PJ</label>
                    <input type="text" name="pj_hp" value="{{ $reservation->pj_hp }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keperluan</label>
                    <textarea name="reason" rows="3" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-unpad-blue focus:border-unpad-blue bg-gray-50">{{ $reservation->reason }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.schedules.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-unpad-blue text-white font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>

@endsection
