@extends('layouts.admin')

@section('title', 'Tambah Pesanan | Admin')
@section('page-title', 'Tambah Pesanan')

@section('header-actions')
    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50">
        <i class="fas fa-chart-line text-gray-600"></i>
        <span class="text-sm font-medium">Dashboard</span>
    </a>
    <a href="{{ route('pesanan.list') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-soccer-green text-white hover:bg-club-navy">
        <i class="fas fa-receipt"></i>
        <span class="text-sm font-medium">Daftar Pesanan</span>
    </a>
@endsection

@section('content')
    @include('pesanan._form')

    <!-- Daftar Pesanan Section -->
    <div class="mt-12 bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-club-navy to-club-maroon p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Daftar Pesanan
                    </h2>
                    <p class="text-white/80 mt-1">Kelola pesanan dan transaksi pelanggan</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search Box -->
                    <div class="relative">
                        <input type="text" id="searchPemesan" placeholder="Cari pesanan/pelanggan..."
                            class="w-64 px-4 py-2 pl-10 rounded-lg border-0 focus:ring-2 focus:ring-white/50 focus:outline-none">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <!-- Refresh Button -->
                    <button onclick="loadPemesanList()"
                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Loading State -->
            <div id="pemesanLoading" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-soccer-green mb-4"></i>
                <p class="text-gray-600">Memuat data pemesan...</p>
            </div>

            <!-- Error State -->
            <div id="pemesanError" class="text-center py-8 hidden">
                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                <p class="text-gray-600">Gagal memuat data pemesan</p>
                <button onclick="loadPemesanList()"
                    class="mt-4 bg-soccer-green text-white px-4 py-2 rounded-lg hover:bg-soccer-dark transition-colors">
                    Coba Lagi
                </button>
            </div>

            <!-- Empty State -->
            <div id="pemesanEmpty" class="text-center py-8 hidden">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Belum ada pemesan</p>
                <p class="text-gray-500 text-sm">Pesanan akan muncul di sini setelah ada yang memesan</p>
            </div>

            <!-- Pemesan List -->
            <div id="pemesanList" class="hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Punggung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th> -->
                            </tr>
                        </thead>
                        <tbody id="pemesanTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data akan diisi dengan JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="pemesanPagination" class="mt-6 flex items-center justify-between">
                    <!-- Pagination akan diisi dengan JavaScript -->
                </div>
            </div>
        </div>
    </div>

    @include('components.modal-success')
    @include('components.modal-detail')
@endsection

@push('scripts')
    <script>
        window.UKURAN_BAJU = @json($ukuranBaju ?? []);
    </script>
    <script src="{{ asset('js/pesanan.js') }}"></script>
    <script>
        // Live update jersey preview for pemesan utama
        (function(){
            function updateJerseyPreview() {
                var nama = (document.getElementById('namaPunggung')?.value || document.getElementById('namaPemesan')?.value || '').trim();
                var nomor = (document.getElementById('nomorPunggung')?.value || '').trim();
                var nameEl = document.getElementById('jerseyPreviewName');
                var numEl = document.getElementById('jerseyPreviewNumber');
                if (nameEl) nameEl.textContent = (nama ? nama : 'NAMA').toUpperCase();
                if (numEl) numEl.textContent = nomor ? nomor : '00';
            }
            document.addEventListener('input', function(e){
                if (e.target && (e.target.id === 'namaPunggung' || e.target.id === 'namaPemesan' || e.target.id === 'nomorPunggong' || e.target.id === 'nomorPunggung')) {
                    updateJerseyPreview();
                }
            });
            document.addEventListener('DOMContentLoaded', updateJerseyPreview);
        })();
    </script>
@endpush
