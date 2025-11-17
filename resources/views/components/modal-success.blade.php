<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-3xl text-green-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Berhasil Disimpan</h3>
        <p class="text-gray-600 mb-6">Data pesanan telah disimpan. Anda dapat mengirim ringkasan via WhatsApp atau kembali ke dashboard.</p>
        <div id="successSummary" class="text-sm text-gray-700 bg-gray-50 rounded-lg p-4 mb-4 hidden">
            <div class="flex justify-between"><span>Jumlah Baju:</span><span id="successJumlah">-</span></div>
            <div class="flex justify-between"><span>Total Harga:</span><span id="successTotalHarga">-</span></div>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <a id="invoiceDownloadBtn" href="#" class="hidden bg-gradient-to-r from-field-green to-club-navy text-white px-6 py-3 rounded-xl font-semibold w-full text-center">
                Download Invoice (PDF)
            </a>
            <button onclick="openWhatsApp()"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold w-full">
                Kirim ke WhatsApp
            </button>
            <button onclick="resetForm()"
                class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-6 py-3 rounded-xl font-semibold w-full">
                Kembali ke Dashboard
            </button>
        </div>
    </div>
    
</div>
