@extends('layouts.admin')

@section('title', 'Dashboard | Admin')
@section('page-title', 'Dashboard')

@section('header-actions')
    <a href="{{ route('pesanan.list') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50">
        <i class="fas fa-receipt"></i>
        <span class="text-sm font-medium">Daftar Pesanan</span>
    </a>
    <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-soccer-green text-white hover:bg-club-navy">
        <i class="fas fa-plus"></i>
        <span class="text-sm font-medium">Tambah Pesanan</span>
    </a>
@endsection

@section('content')
    <style>
        /* Smooth grid that reacts to sidebar state */
        .dashboard-stats { display:grid; gap:1.5rem; margin-bottom:2rem; transition: grid-template-columns .25s ease, gap .25s ease; }
        /* Default (sidebar visible) → 2x2 */
        #appShell .dashboard-stats { grid-template-columns: repeat(2,minmax(0,1fr)); }
        /* Sidebar hidden → linear horizontal (single row, scrollable) */
        #appShell.sidebar-collapsed .dashboard-stats {
            grid-template-columns: none;             /* disable fixed cols */
            grid-auto-flow: column;                  /* lay out items horizontally */
            grid-auto-columns: minmax(16rem, 1fr);   /* card width */
            overflow-x: auto;                        /* horizontal scroll */
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x proximity;
        }
        /* Card subtle animation */
        .stats-card { transition: transform .25s ease, box-shadow .25s ease; scroll-snap-align: start; }
        .stats-card:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1); }
    </style>

    <!-- Stats Cards -->
    <div class="dashboard-stats">
        <div class="card p-6 stats-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900 break-words leading-tight">{{ $stats['total_pesanan'] }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 stats-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 break-words leading-tight">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 stats-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-tshirt text-2xl"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Baju Terjual</p>
                    <p class="text-2xl font-bold text-gray-900 break-words leading-tight">{{ $stats['total_baju_terjual'] }} pcs</p>
                </div>
            </div>
        </div>

        <div class="card p-6 stats-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 break-words leading-tight">{{ $stats['pesanan_pending'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status + Per Ukuran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <span class="font-bold text-gray-900">{{ $stats['pesanan_pending'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Confirmed</span>
                    </div>
                    <span class="font-bold text-gray-900">{{ $stats['pesanan_confirmed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Processing</span>
                    </div>
                    <span class="font-bold text-gray-900">{{ $stats['pesanan_processing'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Completed</span>
                    </div>
                    <span class="font-bold text-gray-900">{{ $stats['pesanan_completed'] }}</span>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Penjualan Per Ukuran</h3>
            <div class="space-y-3">
                @foreach($statistikUkuran as $ukuran)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $ukuran->ukuran_baju }}</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">{{ $ukuran->jumlah_terjual }} pcs</span>
                        <span class="text-sm text-green-600">Rp{{ number_format($ukuran->total_pendapatan, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pendapatan Bulanan</h3>
            <canvas id="pendapatanChart" width="400" height="200"></canvas>
        </div>
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Jumlah Pesanan Bulanan</h3>
            <canvas id="pesananChart" width="400" height="200"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statistikBulanan = @json($statistikBulanan);
    const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
    new Chart(pendapatanCtx, {
        type: 'line',
        data: {
            labels: statistikBulanan.map(item => item.bulan),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: statistikBulanan.map(item => item.total_pendapatan),
                borderColor: '#113F67',
                backgroundColor: 'rgba(17, 63, 103, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (v) => 'Rp' + v.toLocaleString('id-ID') }
                }
            }
        }
    });

    const pesananCtx = document.getElementById('pesananChart').getContext('2d');
    new Chart(pesananCtx, {
        type: 'bar',
        data: {
            labels: statistikBulanan.map(item => item.bulan),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: statistikBulanan.map(item => item.jumlah_pesanan),
                backgroundColor: '#EB5B00',
                borderColor: '#EB5B00',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endpush
