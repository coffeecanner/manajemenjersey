<div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
    <!-- Progress Steps -->
    <div class="bg-gradient-to-r from-club-navy to-club-maroon p-6">
        <div class="flex justify-between items-center text-white">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-white text-soccer-green rounded-full flex items-center justify-center font-bold">1</div>
                <span class="font-medium">Data Pemesan</span>
            </div>
            <div class="flex items-center space-x-2 opacity-70">
                <div class="w-8 h-8 border-2 border-white rounded-full flex items-center justify-center font-bold">2</div>
                <span class="font-medium">Data Keluarga</span>
            </div>
            <div class="flex items-center space-x-2 opacity-70">
                <div class="w-8 h-8 border-2 border-white rounded-full flex items-center justify-center font-bold">3</div>
                <span class="font-medium">Konfirmasi</span>
            </div>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="px-8 py-3 bg-white border-b">
        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div id="progressBarFill" class="bg-field-green h-2 w-1/3 transition-all duration-300" style="width: 33%"></div>
        </div>
        <div class="mt-2 text-xs text-gray-600"><span id="progressPercent">33</span>% selesai</div>
    </div>

    <!-- Form Content -->
    <div class="p-8">
        <form id="pesananForm" action="{{ route('pesanan.store') }}" method="POST">
            @csrf

            <!-- Step 1: Data Pemesan Utama -->
            <div id="step1" class="step-content transition-all duration-300 ease-in-out">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-user-tie mr-3 text-soccer-green"></i>
                        Data Pelanggan
                    </h2>
                    <p class="text-gray-600">Masukkan data pelanggan dan konfigurasi pesanan</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-user mr-2 text-soccer-green"></i>
                            Nama Pelanggan *
                        </label>
                        <input type="text" name="nama_pemesan" id="namaPemesan" placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-3 min-h-12 text-base border-2 border-gray-200 rounded-xl focus:border-field-green focus:outline-none transition-colors" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                            Nomor WhatsApp Pelanggan *
                        </label>
                        <input type="tel" name="nomor_whatsapp" id="nomorWhatsapp" placeholder="08123456789"
                            class="w-full px-4 py-3 min-h-12 text-base border-2 border-gray-200 rounded-xl focus:border-field-green focus:outline-none transition-colors" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-hashtag mr-2 text-gray-500"></i>
                            Nomor Punggung *
                        </label>
                        <input type="text" name="nomor_punggung" id="nomorPunggung" placeholder="1-999"
                            class="w-full px-4 py-3 min-h-12 text-base border-2 border-gray-200 rounded-xl focus:border-field-green focus:outline-none transition-colors" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-signature mr-2 text-gray-500"></i>
                            Nama di Punggung (opsional)
                        </label>
                        <input type="text" name="nama_punggung" id="namaPunggung" placeholder="Nama yang dicetak di punggung" maxlength="30"
                            class="w-full px-4 py-3 min-h-12 text-base border-2 border-gray-200 rounded-xl focus:border-field-green focus:outline-none transition-colors">
                    </div>

                    
                </div>

                <!-- Style/Desain Request (Opsional) -->
                <div class="mt-4">
                    <label class="text-sm font-semibold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-palette mr-2 text-soccer-green"></i>
                        Permintaan Desain/Style (opsional)
                    </label>
                    <textarea name="style_request" id="styleRequest" rows="3" placeholder="Contoh: Warna dasar hijau, list putih, font nama seperti club X, tambahkan logo keluarga di dada kiri."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-field-green focus:outline-none transition-colors"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Jika dibiarkan kosong, desainer akan menggunakan standar desain yang disepakati.</p>
                </div>

                <!-- Preview Jersey (Pelanggan) -->
                <div class="bg-white border-2 border-gray-100 rounded-xl overflow-hidden mb-8">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-shirt mr-2 text-soccer-green"></i>
                            Preview Jersey Pelanggan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <div class="relative mx-auto w-64 aspect-[3/4]">
                                <!-- Jersey base -->
                                <svg viewBox="0 0 300 300" class="absolute inset-0 w-full h-full">
                                    <path d="M100,40 L120,20 L180,20 L200,40 L240,60 L220,110 L200,95 L200,270 L100,270 L100,95 L80,110 L60,60 Z" fill="#ffffff" stroke="#e5e7eb" stroke-width="4" />
                                </svg>
                                <!-- Name -->
                                <div id="jerseyPreviewName" class="absolute top-[28%] left-1/2 -translate-x-1/2 text-center text-gray-800 font-bold tracking-widest text-sm">NAMA</div>
                                <!-- Number -->
                                <div id="jerseyPreviewNumber" class="absolute top-[43%] left-1/2 -translate-x-1/2 text-center text-gray-900 font-extrabold" style="font-size: 56px; line-height: 1;">00</div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Gambar hanya preview. Konfirmasikan detail desain dengan customer.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="nextStep()"
                        class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        Lanjutkan
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Data Anggota Keluarga -->
            <div id="step2" class="step-content hidden transition-all duration-300 ease-in-out">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-users mr-3 text-soccer-green"></i>
                        Data Anggota Keluarga
                    </h2>
                    <p class="text-gray-600">Isi data untuk setiap anggota keluarga</p>
                </div>

                <div id="anggotaContainer" class="space-y-6">
                    <!-- Anggota cards di-generate oleh JavaScript -->
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep()"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </button>
                    <button type="button" onclick="nextStep()"
                        class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        Lanjutkan
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Review Pesanan -->
            <div id="step3" class="step-content hidden transition-all duration-300 ease-in-out">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-clipboard-check mr-3 text-soccer-green"></i>
                        Review Pesanan
                    </h2>
                    <p class="text-gray-600">Periksa kembali detail sebelum menyimpan atau mengirimkan info ke pelanggan</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Data Pelanggan -->
                    <div class="bg-gradient-to-r from-blue-50 to-white p-6 rounded-xl border border-blue-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-check mr-2 text-blue-600"></i>
                            Data Pelanggan
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span id="summaryNama" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">WhatsApp:</span>
                                <span id="summaryWhatsapp" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nomor Punggung:</span>
                                <span id="summaryNomorPunggung" class="font-medium">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pesanan -->
                    <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl border border-green-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calculator mr-2 text-green-600"></i>
                            Total Pesanan
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Baju:</span>
                                <span id="summaryJumlahBaju" class="font-medium">0 pcs</span>
                            </div>
                            <!-- Total Harga disembunyikan sesuai permintaan -->
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-600">Total Harga:</span>
                                <span id="summaryTotalHarga" class="text-xl font-bold text-green-600">Rp0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Anggota -->
                <div class="bg-white border-2 border-gray-100 rounded-xl overflow-hidden mb-8">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-list mr-2 text-soccer-green"></i>
                            Detail Anggota Keluarga
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2">No</th>
                                        <th class="text-left py-2">Preview</th>
                                        <th class="text-left py-2">Nama</th>
                                        <th class="text-left py-2">Umur</th>
                                        <th class="text-left py-2">Gender</th>
                                        <th class="text-left py-2">Ukuran</th>
                                        <th class="text-left py-2">No. Punggung</th>
                                        <!-- <th class="text-right py-2">Harga</th> -->
                                    </tr>
                                </thead>
                                <tbody id="summaryTableBody">
                                    <!-- Data akan diisi dengan JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Konfirmasi -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" id="confirmCheckbox"
                            class="mt-1 h-5 w-5 text-soccer-green border-2 border-gray-300 rounded focus:ring-soccer-green">
                        <div>
                            <span class="text-gray-800 font-medium">Ya, data sudah benar dan saya konfirmasi pesanan ini</span>
                            <p class="text-sm text-gray-600 mt-1">
                                Dengan mencentang kotak ini, saya menyatakan bahwa semua data yang diisi sudah benar dan siap untuk diproses.
                            </p>
                        </div>
                    </label>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="prevStep()"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </button>
                    <button type="submit" id="submitBtn" disabled
                        class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Pesanan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
