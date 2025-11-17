<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan {{ $pesanan->nomor_resi }} - Jersey Family Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soccer-green': '#113F67',
                        'soccer-dark': '#EB5B00',
                        'soccer-accent': '#FEA405'
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-futbol text-3xl text-soccer-green"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan</h1>
                        <p class="text-sm text-gray-600">Resi {{ $pesanan->nomor_resi }}</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('pesanan.list') }}" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="{{ route('pesanan.index') }}" 
                        class="bg-soccer-green text-white px-4 py-2 rounded-lg hover:bg-soccer-dark transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Pesanan
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Data Pemesan -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-soccer-green to-soccer-dark px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Data Pelanggan</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Pemesan</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $pesanan->nama_pemesan }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nomor WhatsApp</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    <a href="https://wa.me/{{ $pesanan->formatted_whatsapp }}" target="_blank" 
                                        class="text-green-600 hover:text-green-800">
                                        {{ $pesanan->nomor_whatsapp }}
                                        <i class="fab fa-whatsapp ml-2"></i>
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nomor Punggung</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-soccer-green text-white">#{{ $pesanan->nomor_punggung }}</span>
                                </p>
                            </div>
                            @if(!empty($pesanan->nama_punggung))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Punggung</label>
                                <p class="text-lg font-semibold text-gray-900">{{ strtoupper($pesanan->nama_punggung) }}</p>
                            </div>
                            @endif
                            @if(!empty($pesanan->style_request))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Permintaan Desain/Style</label>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap bg-gray-50 border border-gray-200 rounded-lg p-3">{{ $pesanan->style_request }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status Pesanan</label>
                                <p class="text-lg font-semibold">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed',
                                            'processing' => 'Processing',
                                            'completed' => 'Completed',
                                            'cancelled' => 'Cancelled'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$pesanan->status_pesanan] }}">
                                        {{ $statusLabels[$pesanan->status_pesanan] }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pesan</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $pesanan->tanggal_pesan ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d F Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Ringkasan Pesanan</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Baju:</span>
                                <span class="text-lg font-bold text-gray-900">{{ $pesanan->total_pesanan }} pcs</span>
                            </div>
                            <div class="flex justify-between items-center border-t pt-4">
                                <span class="text-gray-600">Total Harga:</span>
                                <span class="text-2xl font-bold text-green-600">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Anggota -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-soccer-green to-soccer-dark px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Detail Anggota</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">No</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">Nama</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">Umur</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">Gender</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">Ukuran</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-500">No. Punggung</th>
                                        <th class="text-right py-3 px-4 font-medium text-gray-500">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->detailAnggota as $index => $anggota)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $anggota->nama_anggota }}</div>
                                            @if($index === 0)
                                            <div class="text-xs text-soccer-green font-medium">(Pelanggan)</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-900">{{ $anggota->umur }} thn</td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $anggota->jenis_kelamin === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                <i class="fas {{ $anggota->jenis_kelamin === 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                {{ $anggota->jenis_kelamin }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-sm font-medium text-gray-900">{{ $anggota->ukuran_baju }}</td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-soccer-green text-white">
                                                #{{ $anggota->nomor_punggung }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right text-sm font-bold text-green-600">
                                            Rp{{ number_format($anggota->harga_baju, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Update Status</h2>
                    </div>
                    <div class="p-6">
                        <form id="statusForm" class="flex items-center space-x-4">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                                    <option value="pending" {{ $pesanan->status_pesanan === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $pesanan->status_pesanan === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $pesanan->status_pesanan === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $pesanan->status_pesanan === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $pesanan->status_pesanan === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" 
                                class="bg-soccer-green text-white px-6 py-2 rounded-lg hover:bg-soccer-dark transition-colors">
                                <i class="fas fa-save mr-2"></i>Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newStatus = document.getElementById('statusSelect').value;
            
            fetch(`/pesanan/{{ $pesanan->id_pesanan }}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status_pesanan: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat update status');
            });
        });
    </script>
</body>
</html>
<script>
    (function(){
        const form = document.getElementById('statusForm');
        if (!form) return;
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const select = document.getElementById('statusSelect');
            const newStatus = select ? select.value : '';
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/pesanan/{{ $pesanan->id_pesanan }}/status`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ status_pesanan: newStatus })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengupdate status');
                }
            })
            .catch(() => alert('Terjadi kesalahan saat mengupdate status'));
        });
    })();
</script>
