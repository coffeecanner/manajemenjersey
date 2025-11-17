<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Baju Bola Keluarga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soccer-green': '#113F67',
                        'soccer-dark': '#EB5B00',
                        'soccer-accent': '#FEA405',
                        'warm-gray': '#f5f5f4'
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-soccer-green/10 to-blue-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-center space-x-3">
                <i class="fas fa-futbol text-3xl text-soccer-green animate-spin"></i>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Jersey Family Order</h1>
                    <p class="text-sm text-gray-600">Pesan Baju Bola untuk Seluruh Keluarga</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Progress Steps -->
            <div class="bg-gradient-to-r from-soccer-green to-soccer-dark p-6">
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

            <!-- Form Content -->
            <div class="p-8">
                
                <!-- Step 1: Data Pemesan Utama -->
                <div id="step1" class="step-content">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-user-tie mr-3 text-soccer-green"></i>
                            Data Pemesan Utama
                        </h2>
                        <p class="text-gray-600">Isi data Anda sebagai pemesan utama</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-user mr-2 text-soccer-green"></i>
                                Nama Pemesan *
                            </label>
                            <input type="text" id="namaPemesan" placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                                Nomor WhatsApp *
                            </label>
                            <input type="tel" id="nomorWhatsapp" placeholder="08123456789"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hashtag mr-2 text-soccer-green"></i>
                                Nomor Punggung *
                            </label>
                            <input type="number" id="nomorPunggung" min="1" max="999" placeholder="10"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors">
                            <p class="text-xs text-gray-500">Nomor ini akan digunakan untuk semua anggota keluarga</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-users mr-2 text-soccer-green"></i>
                                Jumlah Anggota Keluarga *
                            </label>
                            <select id="jumlahAnggota"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors">
                                <option value="">Pilih jumlah anggota</option>
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                                <option value="5">5 Orang</option>
                                <option value="6">6+ Orang</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button onclick="nextStep()" 
                            class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            Lanjut ke Data Keluarga
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Data Anggota Keluarga -->
                <div id="step2" class="step-content hidden">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-users mr-3 text-soccer-green"></i>
                            Data Anggota Keluarga
                        </h2>
                        <p class="text-gray-600">Isi data setiap anggota keluarga untuk pemesanan baju</p>
                    </div>

                    <div id="anggotaContainer" class="space-y-6">
                        <!-- Template Anggota Keluarga -->
                        <div class="anggota-card bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border-2 border-gray-100 hover:border-soccer-green/30 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                    <span class="w-8 h-8 bg-soccer-green text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                                    Anggota #1 (Pemesan Utama)
                                </h3>
                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                    <i class="fas fa-hashtag"></i>
                                    <span class="nomor-punggung-display">-</span>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Nama *</label>
                                    <input type="text" placeholder="Nama anggota"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Umur *</label>
                                    <input type="number" min="1" max="100" placeholder="25"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Jenis Kelamin *</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="gender1" value="Laki-laki" class="text-soccer-green focus:ring-soccer-green">
                                            <span class="ml-2 text-sm">Laki-laki</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="gender1" value="Perempuan" class="text-soccer-green focus:ring-soccer-green">
                                            <span class="ml-2 text-sm">Perempuan</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Ukuran Baju *</label>
                                    <select class="ukuran-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                                        <option value="">Pilih ukuran</option>
                                        <optgroup label="Ukuran Anak">
                                            <option value="S Anak" data-harga="80000">S Anak - Rp80.000</option>
                                            <option value="M Anak" data-harga="80000">M Anak - Rp80.000</option>
                                            <option value="L Anak" data-harga="80000">L Anak - Rp80.000</option>
                                        </optgroup>
                                        <optgroup label="Ukuran Dewasa">
                                            <option value="Dewasa S" data-harga="100000">Dewasa S - Rp100.000</option>
                                            <option value="Dewasa M" data-harga="100000">Dewasa M - Rp100.000</option>
                                            <option value="Dewasa L" data-harga="100000">Dewasa L - Rp100.000</option>
                                            <option value="Dewasa XL" data-harga="110000">Dewasa XL - Rp110.000</option>
                                            <option value="Dewasa XXL" data-harga="110000">Dewasa XXL - Rp110.000</option>
                                            <option value="Dewasa XXXL" data-harga="120000">Dewasa XXXL - Rp120.000</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-green-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Harga Baju:</span>
                                    <span class="harga-display text-lg font-bold text-soccer-green">Rp0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button onclick="prevStep()" 
                            class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </button>
                        <button onclick="nextStep()" 
                            class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            Lihat Ringkasan
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Ringkasan & Konfirmasi -->
                <div id="step3" class="step-content hidden">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-receipt mr-3 text-soccer-green"></i>
                            Ringkasan Pesanan
                        </h2>
                        <p class="text-gray-600">Periksa kembali detail pesanan Anda sebelum konfirmasi</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid lg:grid-cols-2 gap-6 mb-8">
                        <!-- Data Pemesan -->
                        <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-xl border border-blue-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-check mr-2 text-blue-600"></i>
                                Data Pemesan
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
                                            <th class="text-left py-2">Nama</th>
                                            <th class="text-left py-2">Umur</th>
                                            <th class="text-left py-2">Gender</th>
                                            <th class="text-left py-2">Ukuran</th>
                                            <th class="text-left py-2">No. Punggung</th>
                                            <th class="text-right py-2">Harga</th>
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
                        <button onclick="prevStep()" 
                            class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </button>
                        <button onclick="submitOrder()" id="submitBtn" disabled
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-3xl text-green-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h3>
                <p class="text-gray-600 mb-6">Terima kasih! Pesanan Anda telah berhasil dikirim dan akan segera diproses.</p>
                <button onclick="resetForm()" 
                    class="bg-gradient-to-r from-soccer-green to-soccer-dark text-white px-6 py-3 rounded-xl font-semibold w-full">
                    Pesan Lagi
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">© 2024 Jersey Family Order. All rights reserved.</p>
        </div>
    </footer>

    <script>
        let currentStep = 1;
        let totalSteps = 3;
        
        // Data yang akan dikumpulkan
        let orderData = {
            pemesan: {},
            anggota: [],
            total: {
                jumlah: 0,
                harga: 0
            }
        };

        // Update progress indicator
        function updateProgress() {
            for (let i = 1; i <= totalSteps; i++) {
                const stepEl = document.querySelector(`.step-content:nth-of-type(${i})`);
                if (i === currentStep) {
                    stepEl.classList.remove('hidden');
                } else {
                    stepEl.classList.add('hidden');
                }
            }
        }

        // Next step
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep === 1) {
                    generateFamilyForms();
                } else if (currentStep === 2) {
                    generateSummary();
                }
                currentStep++;
                updateProgress();
            }
        }

        // Previous step
        function prevStep() {
            currentStep--;
            updateProgress();
        }

        // Validate current step
        function validateCurrentStep() {
            if (currentStep === 1) {
                const nama = document.getElementById('namaPemesan').value;
                const whatsapp = document.getElementById('nomorWhatsapp').value;
                const punggung = document.getElementById('nomorPunggung').value;
                const jumlah = document.getElementById('jumlahAnggota').value;
                
                if (!nama || !whatsapp || !punggung || !jumlah) {
                    alert('Mohon lengkapi semua data pemesan utama');
                    return false;
                }
                
                orderData.pemesan = { nama, whatsapp, punggung, jumlah };
                return true;
            }
            return true;
        }

        // Generate family member forms
        function generateFamilyForms() {
            const container = document.getElementById('anggotaContainer');
            const jumlah = parseInt(orderData.pemesan.jumlah);
            container.innerHTML = '';

            for (let i = 1; i <= jumlah; i++) {
                const isMainOrder = i === 1;
                const cardHtml = `
                    <div class="anggota-card bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border-2 border-gray-100 hover:border-soccer-green/30 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-8 h-8 bg-soccer-green text-white rounded-full flex items-center justify-center text-sm mr-3">${i}</span>
                                Anggota #${i} ${isMainOrder ? '(Pemesan Utama)' : ''}
                            </h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-hashtag"></i>
                                <span class="nomor-punggung-display">${orderData.pemesan.punggung}</span>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Nama *</label>
                                <input type="text" name="nama_${i}" placeholder="Nama anggota" ${isMainOrder ? `value="${orderData.pemesan.nama}" readonly` : ''}
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none ${isMainOrder ? 'bg-gray-100' : ''}">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Umur *</label>
                                <input type="number" name="umur_${i}" min="1" max="100" placeholder="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Jenis Kelamin *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="gender_${i}" value="Laki-laki" class="text-soccer-green focus:ring-soccer-green">
                                        <span class="ml-2 text-sm">Laki-laki</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="gender_${i}" value="Perempuan" class="text-soccer-green focus:ring-soccer-green">
                                        <span class="ml-2 text-sm">Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Ukuran Baju *</label>
                                <select name="ukuran_${i}" class="ukuran-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-soccer-green focus:outline-none" onchange="updatePrice(this)">
                                    <option value="">Pilih ukuran</option>
                                    <optgroup label="Ukuran Anak">
                                        <option value="S Anak" data-harga="80000">S Anak - Rp80.000</option>
                                        <option value="M Anak" data-harga="80000">M Anak - Rp80.000</option>
                                        <option value="L Anak" data-harga="80000">L Anak - Rp80.000</option>
                                    </optgroup>
                                    <optgroup label="Ukuran Dewasa">
                                        <option value="Dewasa S" data-harga="100000">Dewasa S - Rp100.000</option>
                                        <option value="Dewasa M" data-harga="100000">Dewasa M - Rp100.000</option>
                                        <option value="Dewasa L" data-harga="100000">Dewasa L - Rp100.000</option>
                                        <option value="Dewasa XL" data-harga="110000">Dewasa XL - Rp110.000</option>
                                        <option value="Dewasa XXL" data-harga="110000">Dewasa XXL - Rp110.000</option>
                                        <option value="Dewasa XXXL" data-harga="120000">Dewasa XXXL - Rp120.000</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-green-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Harga Baju:</span>
                                <span class="harga-display text-lg font-bold text-soccer-green">Rp0</span>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += cardHtml;
            }
        }

        // Update price when size is selected
        function updatePrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga') || 0;
            const card = selectElement.closest('.anggota-card');
            const hargaDisplay = card.querySelector('.harga-display');
            hargaDisplay.textContent = `Rp${parseInt(harga).toLocaleString('id-ID')}`;
        }

        // Generate summary
        function generateSummary() {
            // Collect family member data
            orderData.anggota = [];
            let totalHarga = 0;
            
            const anggotaCards = document.querySelectorAll('.anggota-card');
            anggotaCards.forEach((card, index) => {
                const nama = card.querySelector(`input[name="nama_${index + 1}"]`).value;
                const umur = card.querySelector(`input[name="umur_${index + 1}"]`).value;
                const gender = card.querySelector(`input[name="gender_${index + 1}"]:checked`)?.value;
                const ukuran = card.querySelector(`select[name="ukuran_${index + 1}"]`).value;
                const hargaOption = card.querySelector(`select[name="ukuran_${index + 1}"] option:checked`);
                const harga = hargaOption ? parseInt(hargaOption.getAttribute('data-harga')) : 0;
                
                if (nama && umur && gender && ukuran) {
                    orderData.anggota.push({
                        nama, umur, gender, ukuran, harga,
                        nomorPunggung: orderData.pemesan.punggung
                    });
                    totalHarga += harga;
                }
            });
            
            orderData.total.jumlah = orderData.anggota.length;
            orderData.total.harga = totalHarga;
            
            // Update summary display
            document.getElementById('summaryNama').textContent = orderData.pemesan.nama;
            document.getElementById('summaryWhatsapp').textContent = orderData.pemesan.whatsapp;
            document.getElementById('summaryNomorPunggung').textContent = orderData.pemesan.punggung;
            document.getElementById('summaryJumlahBaju').textContent = `${orderData.total.jumlah} pcs`;
            document.getElementById('summaryTotalHarga').textContent = `Rp${totalHarga.toLocaleString('id-ID')}`;
            
            // Generate table
            const tableBody = document.getElementById('summaryTableBody');
            tableBody.innerHTML = '';
            orderData.anggota.forEach((anggota, index) => {
                const row = `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3">${index + 1}</td>
                        <td class="py-3 font-medium">${anggota.nama}</td>
                        <td class="py-3">${anggota.umur} thn</td>
                        <td class="py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${anggota.gender === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">
                                <i class="fas ${anggota.gender === 'Laki-laki' ? 'fa-mars' : 'fa-venus'} mr-1"></i>
                                ${anggota.gender}
                            </span>
                        </td>
                        <td class="py-3 font-medium">${anggota.ukuran}</td>
                        <td class="py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-soccer-green text-white">
                                #${anggota.nomorPunggung}
                            </span>
                        </td>
                        <td class="py-3 text-right font-bold text-soccer-green">Rp${anggota.harga.toLocaleString('id-ID')}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        // Enable/disable submit button based on checkbox
        document.getElementById('confirmCheckbox').addEventListener('change', function() {
            document.getElementById('submitBtn').disabled = !this.checked;
        });

        // Submit order
        function submitOrder() {
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Here you would normally send data to your backend
                console.log('Order Data:', orderData);
                
                // Show success modal
                document.getElementById('successModal').classList.remove('hidden');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        }

        // Reset form
        function resetForm() {
            location.reload();
        }

        // Format number input for WhatsApp
        document.getElementById('nomorWhatsapp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (!value.startsWith('62')) {
                value = '62' + value;
            }
            e.target.value = value;
        });

        // Initialize
        updateProgress();

        // Add animation to cards
        function addCardAnimations() {
            const cards = document.querySelectorAll('.anggota-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Add floating animation to soccer ball
        setInterval(() => {
            const ball = document.querySelector('.fa-futbol');
            ball.style.transform = 'rotate(360deg) scale(1.1)';
            setTimeout(() => {
                ball.style.transform = 'rotate(0deg) scale(1)';
            }, 500);
        }, 3000);

        // Auto-scroll to top when changing steps
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Update original next/prev functions to include scroll
        const originalNextStep = nextStep;
        const originalPrevStep = prevStep;
        
        nextStep = function() {
            const result = originalNextStep();
            scrollToTop();
            return result;
        };
        
        prevStep = function() {
            const result = originalPrevStep();
            scrollToTop();
            return result;
        };

        // Add form validation styling
        function addValidationStyling() {
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') || this.name) {
                        if (!this.value) {
                            this.classList.add('border-red-500');
                            this.classList.remove('border-green-500');
                        } else {
                            this.classList.add('border-green-500');
                            this.classList.remove('border-red-500');
                        }
                    }
                });
            });
        }

        // Initialize validation styling
        setTimeout(addValidationStyling, 100);

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.target.matches('textarea')) {
                e.preventDefault();
                const nextButton = document.querySelector('button[onclick="nextStep()"]');
                if (nextButton && !nextButton.disabled) {
                    nextStep();
                }
            }
        });
    </script>
</body>
</html>