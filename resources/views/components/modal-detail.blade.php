<!-- Detail Pemesan Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-3 text-soccer-green"></i>
                Detail Pesanan
            </h3>
            <button onclick="closeDetailModal()"
                class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Data Pemesan -->
        <div class="bg-gradient-to-r from-blue-50 to-white p-6 rounded-xl border border-blue-200 mb-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                Data Pemesan
            </h4>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama:</span>
                    <span id="detailNama" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">WhatsApp:</span>
                    <span id="detailWhatsapp" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Punggung:</span>
                    <span id="detailNomorPunggung" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah Baju:</span>
                    <span id="detailJumlahBaju" class="font-medium">-</span>
                </div>
            </div>
        </div>

        <!-- Detail Anggota Keluarga -->
        <div class="bg-white border-2 border-gray-100 rounded-xl overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h4 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list mr-2 text-soccer-green"></i>
                    Detail Anggota Keluarga
                </h4>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">No</th>
                                <th class="text-left py-2">Nama</th>
                                <th class="text-left py-2">Umur</th>
                                <th class="text-left py-2">Gender</th>
                                <th class="text-left py-2">Ukuran</th>
                                <th class="text-left py-2">No. Punggung</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <!-- Data akan diisi dengan JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button onclick="closeDetailModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

