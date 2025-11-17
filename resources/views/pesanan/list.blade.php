@extends('layouts.admin')

@section('title', 'Daftar Pesanan | Admin')
@section('page-title', 'Daftar Pesanan')

@section('header-actions')
    <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-soccer-green text-white hover:bg-club-navy">
        <i class="fas fa-plus"></i>
        <span class="text-sm font-medium">Tambah Pesanan</span>
    </a>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-soccer-green to-soccer-dark">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Semua Pesanan</h2>
                    <p class="text-white/80 text-sm">Kelola pesanan dan transaksi</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('pesanan.list') }}" class="mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="relative w-full sm:max-w-sm">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari resi/nama/WA/no punggung/style/tanggal"
                            class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-soccer-green" />
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select name="status" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-soccer-green" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status')==='confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status')==='processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button class="px-4 py-2 bg-soccer-green text-white rounded-lg hover:bg-club-navy" type="submit">Cari</button>
                    @if(request('q') || request('status'))
                        <a href="{{ route('pesanan.list') }}" class="px-4 py-2 border rounded-lg">Reset</a>
                    @endif
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Punggung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pesanan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nomor_resi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_pemesan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->nomor_whatsapp }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge bg-soccer-green text-white">#{{ $item->nomor_punggung }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->total_pesanan }} pcs</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status_pesanan] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($item->status_pesanan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->tanggal_pesan ? \Carbon\Carbon::parse($item->tanggal_pesan)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('pesanan.show', $item->id_pesanan) }}" class="text-soccer-green hover:text-club-navy" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pesanan.invoice', $item->id_pesanan) }}" class="text-gray-600 hover:text-gray-800" title="Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <button onclick="updateStatus('{{ $item->id_pesanan }}', '{{ $item->status_pesanan }}')" class="text-blue-600 hover:text-blue-800" title="Update Status">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg">Belum ada pesanan</p>
                                <p class="text-sm">Pesanan akan muncul di sini setelah ada yang memesan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pesanan->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $pesanan->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status Pesanan</h3>
            <form id="statusForm">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select id="newStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                    <button type="submit" class="bg-soccer-green text-white px-4 py-2 rounded-lg hover:bg-soccer-dark transition-colors">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // jQuery search: debounce form submit + ESC to clear
    (function($){
        $(function(){
            var $input = $("input[name='q']");
            var $form = $input.closest('form');
            var timer = null;
            $input.on('input', function(){
                clearTimeout(timer);
                timer = setTimeout(function(){ $form.trigger('submit'); }, 400);
            });
            $input.on('keydown', function(e){
                if (e.key === 'Escape') { $input.val(''); $form.trigger('submit'); }
            });
            // Submit on status change handled inline via onchange attribute
        });
    })(jQuery);

    let currentPesananId = null;
    function updateStatus(id, currentStatus) {
        currentPesananId = id;
        document.getElementById('newStatus').value = currentStatus;
        document.getElementById('statusModal').classList.remove('hidden');
    }
    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        currentPesananId = null;
    }
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const newStatus = document.getElementById('newStatus').value;
        fetch(`/pesanan/${currentPesananId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status_pesanan: newStatus })
        })
        .then(() => window.location.reload())
        .catch(() => alert('Gagal memperbarui status.'));
    });
</script>
@endpush

