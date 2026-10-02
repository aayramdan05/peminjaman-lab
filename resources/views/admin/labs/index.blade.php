@extends('layouts.app')
@section('header', 'Manajemen Laboratorium')
@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Katalog & Data Lab</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data spesifikasi, fasilitas, dan kapasitas seluruh laboratorium PPBS.</p>
        </div>
        
        <button class="bg-unpad-blue text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Lab Baru
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-ui-success/10 border border-ui-success/20 text-green-800 rounded-xl font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-ui-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($labs as $lab)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-soft transition-shadow">
                <!-- Header / Image -->
                <div class="h-40 bg-gray-100 relative group">
                    @if($lab->image_path)
                        <img src="{{ Storage::url($lab->image_path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <a href="{{ route('labs.edit', $lab->id) }}" class="p-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition shadow-lg" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <button class="p-2 bg-ui-danger text-white rounded-lg hover:bg-red-600 transition shadow-lg" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-extrabold text-gray-900">{{ $lab->name }}</h3>
                        <span class="bg-blue-50 text-unpad-blue px-2 py-1 rounded text-xs font-bold">{{ $lab->capacity }} Org</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium line-clamp-2 mb-4 flex-1">
                        {{ $lab->pc_specs }}
                    </p>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('labs.show', $lab->id) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-50 text-gray-700 text-sm font-bold rounded-lg border border-gray-200 hover:bg-gray-100 transition">Lihat Publik</a>
                        <a href="{{ route('labs.edit', $lab->id) }}" class="flex-1 text-center py-2 bg-unpad-blue text-white text-sm font-bold rounded-lg border border-unpad-blue hover:bg-blue-900 transition">Edit Data</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
