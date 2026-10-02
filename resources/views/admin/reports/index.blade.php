@extends('layouts.app')
@section('header', 'Laporan & Analitik')
@section('content')

    <div class="mb-6 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900">Statistik Peminjaman Tahun {{ $currentYear }}</h2>
        <p class="text-sm text-gray-500 mt-1">Laporan komprehensif mengenai tingkat penggunaan laboratorium komputer PPBS.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-extrabold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-unpad-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                Grafik Tren Peminjaman
            </h3>
            <div class="w-full h-[400px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Top Labs Section -->
        <div class="lg:col-span-1 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col">
            <h3 class="text-lg font-extrabold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-unpad-secondary" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Lab Paling Populer
            </h3>
            
            <div class="flex-1 overflow-y-auto pr-2 space-y-4">
                @if($topLabs->isEmpty())
                    <p class="text-center text-gray-500 italic mt-10">Belum ada data peminjaman yang disetujui.</p>
                @else
                    @foreach($topLabs as $index => $topLab)
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-unpad-blue/30 hover:bg-blue-50/50 transition">
                            <div class="w-10 h-10 rounded-full {{ $index === 0 ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-unpad-blue' }} flex items-center justify-center font-black text-lg mr-4 shrink-0 shadow-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate">{{ $topLab->lab->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $topLab->total_bookings }} kali digunakan</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <button onclick="window.print()" class="text-sm font-bold text-unpad-blue hover:text-blue-800 flex items-center justify-center gap-2 w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const data = @json($chartData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Disetujui',
                        data: data.approved,
                        backgroundColor: '#2ECC71',
                        borderRadius: 6,
                    },
                    {
                        label: 'Ditolak',
                        data: data.rejected,
                        backgroundColor: '#EF4444',
                        borderRadius: 6,
                    },
                    {
                        label: 'Total Pengajuan',
                        data: data.total,
                        type: 'line',
                        borderColor: '#1e3a8a',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#1e3a8a'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: "'Nunito', sans-serif", weight: 'bold' },
                            usePointStyle: true,
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: "'Nunito', sans-serif" }
                        }
                    },
                    x: {
                        ticks: { font: { family: "'Nunito', sans-serif" } }
                    }
                }
            }
        });
    });
</script>
@endsection
