@extends('layouts.app')

@section('header')
    Edit Lab - {{ $lab->name }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-soft">
        
        <form action="{{ route('labs.update', $lab->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info Section -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lab (Read Only)</label>
                        <input type="text" value="{{ $lab->name }}" disabled class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-sm px-4 py-3 font-semibold">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kapasitas (Orang)</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $lab->capacity) }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-unpad-blue focus:ring-unpad-blue px-4 py-3 font-semibold">
                        @error('capacity') <p class="text-ui-danger text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Spesifikasi Komputer</label>
                        <textarea name="pc_specs" rows="4" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-unpad-blue focus:ring-unpad-blue px-4 py-3 font-semibold">{{ old('pc_specs', $lab->pc_specs) }}</textarea>
                        @error('pc_specs') <p class="text-ui-danger text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fasilitas Tambahan (Pisahkan dengan koma)</label>
                        <textarea name="facilities" rows="3" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-unpad-blue focus:ring-unpad-blue px-4 py-3 font-semibold">{{ old('facilities', $lab->facilities) }}</textarea>
                        @error('facilities') <p class="text-ui-danger text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Images Section -->
                <div class="space-y-6">
                    <!-- Main Image -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Foto Utama (Sampul)</label>
                        @if($lab->image_path)
                            <div class="mb-3 relative rounded-xl overflow-hidden aspect-video bg-gray-200">
                                <img src="{{ Storage::url($lab->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="relative">
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-unpad-blue/10 file:text-unpad-blue hover:file:bg-unpad-blue/20 transition-colors">
                        </div>
                        <p class="text-xs text-gray-400 mt-2 font-medium">Maksimal ukuran file: 2MB (JPG/PNG)</p>
                        @error('image') <p class="text-ui-danger text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gallery Images -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Galeri Foto Ruangan</label>
                        
                        @if($lab->gallery_images && count($lab->gallery_images) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                                @foreach($lab->gallery_images as $index => $galleryPath)
                                    <div class="relative group rounded-xl overflow-hidden aspect-square bg-gray-200">
                                        <img src="{{ Storage::url($galleryPath) }}" class="w-full h-full object-cover">
                                        <!-- Delete Checkbox Overlay -->
                                        <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                            <input type="checkbox" name="remove_gallery[]" value="{{ $index }}" class="w-5 h-5 text-red-600 rounded mb-2 border-none">
                                            <span class="text-white text-xs font-bold">Hapus Foto</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 font-medium mb-4">Belum ada foto galeri tambahan.</p>
                        @endif

                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 mb-2">Unggah Tambahan Foto (Bisa lebih dari 1)</label>
                            <input type="file" name="gallery[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition-colors">
                        </div>
                        <p class="text-xs text-gray-400 mt-2 font-medium">Maksimal ukuran per file: 2MB (JPG/PNG)</p>
                        @error('gallery.*') <p class="text-ui-danger text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                </div> <!-- Close Images Section -->
            </div> <!-- Close grid-cols-2 -->

            <!-- Interactive Layout Builder Section -->
            <div class="border-t border-gray-100 pt-8" x-data="layoutBuilder()">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Pembangun Denah Interaktif</h3>
                        <p class="text-sm text-gray-500">Buat denah ruangan lab secara visual. Ubah ukuran grid dan letakkan elemen meja/PC.</p>
                    </div>
                </div>

                <!-- Hidden Input to store JSON -->
                <input type="hidden" name="layout_data" :value="getJson()">

                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-inner">
                    <!-- Controls -->
                    <div class="flex flex-col lg:flex-row gap-8 mb-8 pb-8 border-b border-gray-200">
                        <!-- Grid Size Config -->
                        <div class="flex flex-col gap-2">
                            <label class="block text-sm font-bold text-gray-700">Ukuran Grid PC</label>
                            <div class="flex gap-3">
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">Baris</span>
                                    <input type="number" x-model.number="rows" @change="resizeGrid()" min="1" max="30" class="w-16 rounded-lg border-gray-300 text-sm focus:ring-unpad-blue font-bold text-center">
                                </div>
                                <div class="self-end pb-2 font-bold text-gray-400">X</div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">Kolom</span>
                                    <input type="number" x-model.number="cols" @change="resizeGrid()" min="1" max="30" class="w-16 rounded-lg border-gray-300 text-sm focus:ring-unpad-blue font-bold text-center">
                                </div>
                            </div>
                        </div>

                        <!-- Toolbox for Grid -->
                        <div class="flex flex-col gap-2 border-l border-gray-200 pl-8">
                            <label class="block text-sm font-bold text-gray-700">Alat Grid (Tahan klik & geser untuk multi-isi)</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="activeTool = 'pc'" :class="activeTool === 'pc' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    💻 PC
                                </button>
                                <button type="button" @click="activeTool = 'desk'" :class="activeTool === 'desk' ? 'bg-orange-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    👨‍🏫 Dosen
                                </button>
                                <button type="button" @click="activeTool = 'empty'" :class="activeTool === 'empty' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    ⬜ Hapus
                                </button>
                                <div class="w-px h-8 bg-gray-200 mx-2 self-center"></div>
                                <button type="button" @click="activeTool = 'rotate'" :class="activeTool === 'rotate' ? 'bg-purple-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    🔄 Putar Alat
                                </button>
                            </div>
                        </div>

                        <!-- External Elements (Door & Whiteboard) -->
                        <div class="flex flex-col gap-2 border-l border-gray-200 pl-8 flex-1">
                            <label class="block text-sm font-bold text-gray-700">Elemen Ruangan (Luar Grid)</label>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🚪</span>
                                    <select x-model="doorPosition" class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-unpad-blue text-gray-700 font-medium">
                                        <option value="none">Tanpa Pintu (Sembunyikan)</option>
                                        <option value="bottom-right">Kanan Bawah</option>
                                        <option value="bottom-left">Kiri Bawah</option>
                                        <option value="top-right">Kanan Atas</option>
                                        <option value="top-left">Kiri Atas</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📝</span>
                                    <select x-model="whiteboardPosition" class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-unpad-blue text-gray-700 font-medium">
                                        <option value="none">Tanpa Papan Tulis</option>
                                        <option value="top">Atas (Tengah)</option>
                                        <option value="bottom">Bawah (Tengah)</option>
                                        <option value="left">Kiri (Tengah)</option>
                                        <option value="right">Kanan (Tengah)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- The Grid Canvas Wrapper -->
                    <div class="bg-gray-100 p-8 rounded-2xl border-2 border-gray-300 relative overflow-x-auto w-full flex items-center justify-center min-h-[400px]">
                        
                        <!-- Whiteboard Absolute Positioning -->
                        <div x-show="whiteboardPosition === 'top'" class="absolute top-2 left-1/2 -translate-x-1/2 w-32 h-6 bg-green-600 rounded shadow-md border-2 border-green-800 text-white text-[10px] font-bold flex items-center justify-center">PAPAN TULIS</div>
                        <div x-show="whiteboardPosition === 'bottom'" class="absolute bottom-2 left-1/2 -translate-x-1/2 w-32 h-6 bg-green-600 rounded shadow-md border-2 border-green-800 text-white text-[10px] font-bold flex items-center justify-center">PAPAN TULIS</div>
                        <div x-show="whiteboardPosition === 'left'" class="absolute left-2 top-1/2 -translate-y-1/2 w-6 h-32 bg-green-600 rounded shadow-md border-2 border-green-800 text-white text-[10px] font-bold flex items-center justify-center" style="writing-mode: vertical-rl;">PAPAN TULIS</div>
                        <div x-show="whiteboardPosition === 'right'" class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-32 bg-green-600 rounded shadow-md border-2 border-green-800 text-white text-[10px] font-bold flex items-center justify-center" style="writing-mode: vertical-rl;">PAPAN TULIS</div>

                        <!-- Door Absolute Positioning -->
                        <div x-show="doorPosition === 'bottom-right'" class="absolute bottom-0 right-8 w-16 h-3 bg-amber-800 rounded-t-lg border-2 border-amber-950 flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-yellow-400 ml-8"></div></div>
                        <div x-show="doorPosition === 'bottom-left'" class="absolute bottom-0 left-8 w-16 h-3 bg-amber-800 rounded-t-lg border-2 border-amber-950 flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-yellow-400 mr-8"></div></div>
                        <div x-show="doorPosition === 'top-right'" class="absolute top-0 right-8 w-16 h-3 bg-amber-800 rounded-b-lg border-2 border-amber-950 flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-yellow-400 ml-8"></div></div>
                        <div x-show="doorPosition === 'top-left'" class="absolute top-0 left-8 w-16 h-3 bg-amber-800 rounded-b-lg border-2 border-amber-950 flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-yellow-400 mr-8"></div></div>

                        <div class="grid gap-1.5 min-w-max my-8 mx-8" :style="`grid-template-columns: repeat(${cols}, minmax(0, 1fr));`" @mouseleave="isDragging = false">
                            <template x-for="(cell, index) in grid" :key="index">
                                <div @mousedown="startDrag(index)" 
                                     @mouseover="dragOver(index)"
                                     @mouseup="stopDrag()"
                                     @dragstart.prevent=""
                                     class="w-12 h-12 sm:w-16 sm:h-16 border border-gray-300 rounded-md flex items-center justify-center cursor-pointer transition-colors shadow-sm bg-white hover:border-unpad-blue"
                                     :class="{
                                         'bg-blue-50 border-blue-400 shadow-blue-500/20': cell.type === 'pc', 
                                         'bg-orange-50 border-orange-400 shadow-orange-500/20': cell.type === 'desk', 
                                     }">
                                     <div :style="`transform: rotate(${cell.rotation}deg);`" class="flex items-center justify-center w-full h-full pointer-events-none">
                                         <!-- PC Icon -->
                                         <div x-show="cell.type === 'pc'" class="flex flex-col items-center">
                                             <div class="w-8 h-1.5 bg-gray-400 rounded-sm mb-1.5 shadow-sm"></div>
                                             <div class="w-10 h-5 bg-blue-600 rounded flex items-center justify-center shadow-sm">
                                                <span class="text-white text-[10px] font-extrabold tracking-wider">PC</span>
                                             </div>
                                         </div>
                                         
                                         <!-- Desk Icon -->
                                         <div x-show="cell.type === 'desk'" class="w-12 h-7 bg-orange-500 rounded text-white text-[10px] font-bold flex items-center justify-center shadow-sm border border-orange-600">DOSEN</div>
                                     </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    </div>
                    <p class="text-center text-xs text-gray-500 mt-4 font-medium">Tip: Pilih alat di atas, lalu klik kotak di bawah untuk menempatkan. Gunakan alat 'Putar Arah' lalu klik PC/Meja untuk memutar orientasinya.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-100 mt-8">
                <a href="{{ route('admin.labs.index') }}" class="px-6 py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3 rounded-xl font-extrabold text-white bg-unpad-blue hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('layoutBuilder', () => ({
            rows: 5,
            cols: 8,
            grid: [],
            activeTool: 'pc',
            isDragging: false,
            doorPosition: 'bottom-right',
            whiteboardPosition: 'top',
            
            init() {
                let existing = '{!! $lab->layout_data ? addslashes(json_encode($lab->layout_data)) : "" !!}';
                if (existing) {
                    try {
                        let data = JSON.parse(existing);
                        if (data && data.rows && data.cols) {
                            this.rows = data.rows;
                            this.cols = data.cols;
                            this.grid = data.grid;
                            if (data.doorPosition) this.doorPosition = data.doorPosition;
                            if (data.whiteboardPosition) this.whiteboardPosition = data.whiteboardPosition;
                            return;
                        }
                    } catch (e) { console.error("Failed to parse layout data"); }
                }
                this.resizeGrid();
                
                // Stop dragging when mouse goes up anywhere
                window.addEventListener('mouseup', () => {
                    this.isDragging = false;
                });
            },
            
            resizeGrid() {
                if (this.rows < 1) this.rows = 1;
                if (this.cols < 1) this.cols = 1;
                
                let newGrid = [];
                for(let i=0; i<(this.rows * this.cols); i++) {
                    newGrid.push({ type: 'empty', rotation: 0 });
                }
                this.grid = newGrid;
            },
            
            startDrag(index) {
                this.isDragging = true;
                this.cellClick(index);
            },
            
            dragOver(index) {
                if (this.isDragging) {
                    this.cellClick(index);
                }
            },
            
            stopDrag() {
                this.isDragging = false;
            },
            
            cellClick(index) {
                let cell = this.grid[index];
                if (this.activeTool === 'rotate') {
                    if (cell.type !== 'empty') {
                        cell.rotation = (cell.rotation + 90) % 360;
                    }
                } else {
                    // Only update if different to avoid unnecessary reactivity triggers
                    if (cell.type !== this.activeTool || cell.rotation !== 0) {
                        cell.type = this.activeTool;
                        cell.rotation = 0;
                    }
                }
                this.grid[index] = { ...cell };
            },
            
            getJson() {
                return JSON.stringify({
                    rows: this.rows,
                    cols: this.cols,
                    grid: this.grid,
                    doorPosition: this.doorPosition,
                    whiteboardPosition: this.whiteboardPosition
                });
            }
        }));
    });
</script>
@endsection
