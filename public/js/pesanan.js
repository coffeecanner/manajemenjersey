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

// CSRF Token untuk AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Update progress indicator
function updateProgress() {
    for (let i = 1; i <= totalSteps; i++) {
        const stepEl = document.querySelector(`.step-content:nth-of-type(${i})`);
        if (stepEl) {
            if (i === currentStep) {
                stepEl.classList.remove('hidden');
            } else {
                stepEl.classList.add('hidden');
            }
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
        scrollToTop();
    }
}

// Previous step
function prevStep() {
    currentStep--;
    updateProgress();
    scrollToTop();
}

// Validate current step
function validateCurrentStep() {
    if (currentStep === 1) {
        const nama = document.getElementById('namaPemesan').value.trim();
        const whatsapp = document.getElementById('nomorWhatsapp').value.trim();
        const punggung = document.getElementById('nomorPunggung').value.trim();
        const namaPunggung = (document.getElementById('namaPunggung')?.value || '').trim();
        const jumlah = document.getElementById('jumlahAnggota').value;

        // Clear previous error messages
        clearValidationErrors();

        let isValid = true;

        // Validate nama pemesan
        if (!nama) {
            showFieldError('namaPemesan', 'Nama pemesan harus diisi');
            isValid = false;
        } else if (nama.length > 100) {
            showFieldError('namaPemesan', 'Nama pemesan maksimal 100 karakter');
            isValid = false;
        }

        // Validate nomor WhatsApp
        if (!whatsapp) {
            showFieldError('nomorWhatsapp', 'Nomor WhatsApp harus diisi');
            isValid = false;
        } else if (!isValidIndonesianPhone(whatsapp)) {
            showFieldError('nomorWhatsapp', 'Format nomor WhatsApp tidak valid. Gunakan format: 08123456789 atau +628123456789');
            isValid = false;
        }

        // Validate nomor punggung
        if (!punggung) {
            showFieldError('nomorPunggung', 'Nomor punggung harus diisi');
            isValid = false;
        } else if (!isValidJerseyNumber(punggung)) {
            showFieldError('nomorPunggung', 'Nomor punggung harus berupa angka antara 1-999');
            isValid = false;
        }

        // Validate jumlah anggota
        if (!jumlah) {
            showFieldError('jumlahAnggota', 'Jumlah anggota keluarga harus dipilih');
            isValid = false;
        }

        // Check for any existing validation errors
        const hasErrors = document.querySelectorAll('.field-error').length > 0;
        if (hasErrors) {
            isValid = false;
        }

        if (!isValid) {
            alert('Mohon perbaiki data yang salah sebelum melanjutkan');
            return false;
        }

        orderData.pemesan = { nama, whatsapp, punggung, namaPunggung, jumlah };
        return true;
    }
    return true;
}

// Validate Indonesian phone number format
function isValidIndonesianPhone(phone) {
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    return /^(08|62)[0-9]{8,13}$/.test(cleanPhone);
}

// Validate jersey number
function isValidJerseyNumber(number) {
    const num = parseInt(number);
    return !isNaN(num) && num >= 1 && num <= 999;
}

// Show field error
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-red-500 text-xs mt-1 field-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
    field.classList.add('border-red-500');
    try { field.setAttribute('title', message); } catch (e) {}
}

// Clear validation errors
function clearValidationErrors() {
    const errorElements = document.querySelectorAll('.field-error');
    errorElements.forEach(el => el.remove());

    const errorFields = document.querySelectorAll('.border-red-500');
    errorFields.forEach(field => field.classList.remove('border-red-500'));
}

// Generate family member forms
function generateFamilyForms() {
    const container = document.getElementById('anggotaContainer');
    const jumlah = parseInt(orderData.pemesan.jumlah);
    container.innerHTML = '';

    const optionsHtml = buildUkuranOptions();

    for (let i = 1; i <= jumlah; i++) {
        const isMainOrder = i === 1;
        const cardHtml = `
            <div class="anggota-card bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border-2 border-gray-100 hover:border-soccer-green/30 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-soccer-green text-white rounded-full flex items-center justify-center text-sm mr-3">${i}</span>
                        Anggota #${i} ${isMainOrder ? '(Pemesan Utama)' : ''}
                    </h3>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Anggota *</label>
                        <input type="text" name="anggota[${i-1}][nama_anggota]" placeholder="Nama lengkap"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Umur *</label>
                        <input type="number" name="anggota[${i-1}][umur]" placeholder="Umur"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Jenis Kelamin *</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="anggota[${i-1}][jenis_kelamin]" value="Laki-laki" class="form-radio text-soccer-green">
                                <span class="ml-2">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="anggota[${i-1}][jenis_kelamin]" value="Perempuan" class="form-radio text-soccer-green">
                                <span class="ml-2">Perempuan</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Ukuran Baju *</label>
                        <select name="anggota[${i-1}][ukuran_baju]" onchange="updatePrice(this)"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                            ${optionsHtml}
                        </select>
                    </div>
                </div>

                <div class="mt-4 grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama di Jersey (opsional)</label>
                        <input type="text" name="anggota[${i-1}][nama_di_jersey]" placeholder="Nama cetak di jersey" maxlength="30"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors">
                    </div>
                </div>

                <!-- Mini Preview Jersey -->
                <div class="mt-4">
                    <div class="text-sm font-semibold text-gray-700 mb-2">Preview Jersey</div>
                    <div class="flex items-center gap-4">
                        <div class="relative w-20 aspect-[3/4]">
                            <svg viewBox="0 0 300 300" class="absolute inset-0 w-full h-full">
                                <path d="M100,40 L120,20 L180,20 L200,40 L240,60 L220,110 L200,95 L200,270 L100,270 L100,95 L80,110 L60,60 Z" fill="#ffffff" stroke="#e5e7eb" stroke-width="4" />
                            </svg>
                            <div class="member-preview-name absolute top-[30%] left-1/2 -translate-x-1/2 text-center text-gray-800 font-bold tracking-wider text-[10px]">NAMA</div>
                            <div class="member-preview-number absolute top-[46%] left-1/2 -translate-x-1/2 text-center text-gray-900 font-extrabold text-xl leading-none">00</div>
                        </div>
                        <p class="text-[11px] text-gray-500">Gambar hanya preview. Konfirmasikan detail desain dengan customer.</p>
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
        container.insertAdjacentHTML('beforeend', cardHtml);

        // Hook up live preview for this member
        const card = container.lastElementChild;
        const nameInput = card.querySelector(`input[name=\"anggota[${i-1}][nama_anggota]\"]`);
        const printNameInput = card.querySelector(`input[name=\"anggota[${i-1}][nama_di_jersey]\"]`);
        const nameEl = card.querySelector('.member-preview-name');
        const numberEl = card.querySelector('.member-preview-number');
        const globalNumber = (document.getElementById('nomorPunggung')?.value || '').trim();
        if (numberEl) numberEl.textContent = globalNumber || '00';
        if (nameEl) nameEl.textContent = 'NAMA';
        const updateName = () => {
            const v = (printNameInput?.value || nameInput?.value || '').trim();
            nameEl.textContent = (v ? v : 'NAMA').toUpperCase();
        };
        if (nameInput && nameEl) nameInput.addEventListener('input', updateName);
        if (printNameInput && nameEl) printNameInput.addEventListener('input', updateName);
        updateName();
    }

    // ensure numbers reflect current main jersey number
    updateAllMemberPreviewsNumber();
}

function buildUkuranOptions() {
    const data = Array.isArray(window.UKURAN_BAJU) ? window.UKURAN_BAJU : [];
    let anak = [], dewasa = [];
    if (data.length) {
        data.forEach(item => {
            const opt = `<option value="${item.ukuran_baju}" data-harga="${item.harga}">${item.ukuran_baju}</option>`;
            if ((item.kategori || '').toLowerCase() === 'anak') anak.push(opt); else dewasa.push(opt);
        });
    } else {
        // Fallback to match DB seeder values exactly
        anak = [
            `<option value="S Anak" data-harga="80000">S Anak</option>`,
            `<option value="M Anak" data-harga="80000">M Anak</option>`,
            `<option value="L Anak" data-harga="80000">L Anak</option>`
        ];
        dewasa = [
            `<option value="Dewasa S" data-harga="100000">Dewasa S</option>`,
            `<option value="Dewasa M" data-harga="100000">Dewasa M</option>`,
            `<option value="Dewasa L" data-harga="100000">Dewasa L</option>`,
            `<option value="Dewasa XL" data-harga="110000">Dewasa XL</option>`,
            `<option value="Dewasa XXL" data-harga="110000">Dewasa XXL</option>`,
            `<option value="Dewasa XXXL" data-harga="120000">Dewasa XXXL</option>`
        ];
    }
    return `
        <option value="" disabled selected>Pilih ukuran</option>
        <optgroup label="Anak">
            ${anak.join('')}
        </optgroup>
        <optgroup label="Dewasa">
            ${dewasa.join('')}
        </optgroup>
    `;
}

// Update price when size is selected
function updatePrice(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const harga = selectedOption.getAttribute('data-harga') || 0;
    const card = selectElement.closest('.anggota-card');
    const hargaDisplay = card.querySelector('.harga-display');
    hargaDisplay.textContent = `Rp${parseInt(harga).toLocaleString('id-ID')}`;
}

// Sync all member preview numbers from main jersey number
function updateAllMemberPreviewsNumber() {
    const num = (document.getElementById('nomorPunggung')?.value || '').trim() || '00';
    document.querySelectorAll('.member-preview-number').forEach(el => {
        el.textContent = num;
    });
}

// Generate summary
function generateSummary() {
    // Collect family member data
    orderData.anggota = [];
    let totalHarga = 0;

    const anggotaCards = document.querySelectorAll('.anggota-card');
    anggotaCards.forEach((card, index) => {
        const nama = card.querySelector(`input[name="anggota[${index}][nama_anggota]"]`).value;
        const namaCetak = card.querySelector(`input[name="anggota[${index}][nama_di_jersey]"]`)?.value || '';
        const umur = card.querySelector(`input[name="anggota[${index}][umur]"]`).value;
        const gender = card.querySelector(`input[name="anggota[${index}][jenis_kelamin]"]:checked`)?.value;
        const ukuran = card.querySelector(`select[name="anggota[${index}][ukuran_baju]"]`).value;
        const hargaOption = card.querySelector(`select[name="anggota[${index}][ukuran_baju]"] option:checked`);
        const harga = hargaOption ? parseInt(hargaOption.getAttribute('data-harga')) : 0;

        if (nama && umur && gender && ukuran) {
            orderData.anggota.push({
                nama, namaCetak, umur, gender, ukuran, harga,
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
    const totalHargaEl = document.getElementById('summaryTotalHarga');
    if (totalHargaEl) totalHargaEl.textContent = `Rp${totalHarga.toLocaleString('id-ID')}`;

    // Generate table
    const tableBody = document.getElementById('summaryTableBody');
    tableBody.innerHTML = '';
    orderData.anggota.forEach((anggota, index) => {
        const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3">${index + 1}</td>
                <td class="py-3">
                    <div class="relative w-10 aspect-[3/4] mx-auto">
                        <svg viewBox="0 0 300 300" class="absolute inset-0 w-full h-full">
                            <path d="M100,40 L120,20 L180,20 L200,40 L240,60 L220,110 L200,95 L200,270 L100,270 L100,95 L80,110 L60,60 Z" fill="#ffffff" stroke="#e5e7eb" stroke-width="3" />
                        </svg>
                        <div class="absolute top-[33%] left-1/2 -translate-x-1/2 text-center text-gray-800 font-bold tracking-wider text-[8px] leading-none">${(anggota.namaCetak||anggota.nama||'NAMA').toString().toUpperCase().slice(0,8)}</div>
                        <div class="absolute top-[50%] left-1/2 -translate-x-1/2 text-center text-gray-900 font-extrabold text-sm leading-none">${orderData.pemesan.punggung || '00'}</div>
                    </div>
                </td>
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
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Scroll to top function
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// (moved) initialize handled in DOMContentLoaded at bottom

// Pemesan List Management
let currentPage = 1;
let currentSearch = '';
let pemesanData = null;

// Load pemesan list
function loadPemesanList(page = 1, search = '') {
    currentPage = page;
    currentSearch = search;

    // Show loading state
    showPemesanState('loading');

    const url = new URL('/api/pemesan-list', window.location.origin);
    url.searchParams.append('page', page);
    url.searchParams.append('per_page', 10);
    if (search) {
        url.searchParams.append('search', search);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                pemesanData = data.data;
                displayPemesanList(data.data);
                showPemesanState('list');
            } else {
                showPemesanState('error');
            }
        })
        .catch(error => {
            console.error('Error loading pemesan list:', error);
            showPemesanState('error');
        });
}

// Listen for main jersey number changes to sync previews
document.addEventListener('input', function(e){
    if (e.target && e.target.id === 'nomorPunggung') {
        updateAllMemberPreviewsNumber();
    }
});

// Show different states
function showPemesanState(state) {
    const states = ['loading', 'error', 'empty', 'list'];
    states.forEach(s => {
        const element = document.getElementById(`pemesan${s.charAt(0).toUpperCase() + s.slice(1)}`);
        if (element) {
            element.classList.toggle('hidden', s !== state);
        }
    });
}

// Display pemesan list
function displayPemesanList(data) {
    const tbody = document.getElementById('pemesanTableBody');
    tbody.innerHTML = '';

    if (data.data.length === 0) {
        showPemesanState('empty');
        return;
    }

    data.data.forEach(pemesan => {
        const row = createPemesanRow(pemesan);
        tbody.innerHTML += row;
    });

    // Update pagination
    updatePagination(data);
}

// Create pemesan row
function createPemesanRow(pemesan) {
    const statusColors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-blue-100 text-blue-800',
        'processing': 'bg-purple-100 text-purple-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    };

    const statusLabels = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'processing': 'Processing',
        'completed': 'Completed',
        'cancelled': 'Cancelled'
    };

    const tanggal = new Date(pemesan.tanggal_pesan).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    return `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #${pemesan.id_pesanan}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${pemesan.nama_pemesan}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${pemesan.nomor_whatsapp}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-soccer-green text-white">
                    #${pemesan.nomor_punggung}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${pemesan.total_pesanan} pcs
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="showDetailPemesan(${pemesan.id_pesanan})"
                    class="bg-soccer-green hover:bg-soccer-dark text-white px-3 py-1 rounded-md text-xs transition-colors">
                    <i class="fas fa-eye mr-1"></i>
                    Lihat Detail
                </button>
            </td>
            <!-- Harga, Status, dan Tanggal disembunyikan sesuai permintaan -->
            <!--
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                Rp${parseInt(pemesan.total_harga).toLocaleString('id-ID')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[pemesan.status]}">
                    ${statusLabels[pemesan.status]}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${tanggal}
            </td>
            -->
        </tr>
    `;
}

// Update pagination
function updatePagination(data) {
    const paginationDiv = document.getElementById('pemesanPagination');
    const currentPage = data.current_page;
    const lastPage = data.last_page;
    const total = data.total;

    let paginationHtml = `
        <div class="flex items-center text-sm text-gray-700">
            Menampilkan ${data.from || 0} sampai ${data.to || 0} dari ${total} pemesan
        </div>
        <div class="flex items-center space-x-2">
    `;

    // Previous button
    if (currentPage > 1) {
        paginationHtml += `
            <button onclick="loadPemesanList(${currentPage - 1}, '${currentSearch}')"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
    }

    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(lastPage, currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        paginationHtml += `
            <button onclick="loadPemesanList(${i}, '${currentSearch}')"
                class="px-3 py-1 rounded transition-colors ${isActive ? 'bg-soccer-green text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}">
                ${i}
            </button>
        `;
    }

    // Next button
    if (currentPage < lastPage) {
        paginationHtml += `
            <button onclick="loadPemesanList(${currentPage + 1}, '${currentSearch}')"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }

    paginationHtml += '</div>';
    paginationDiv.innerHTML = paginationHtml;
}

// (moved) search input binding and initial load defined later

// Show detail pemesan modal
function showDetailPemesan(idPesanan) {
    console.log('Loading detail for ID:', idPesanan);

    // Show loading state
    const modal = document.getElementById('detailModal');
    modal.classList.remove('hidden');

    // Show loading in modal
    document.getElementById('detailNama').textContent = 'Loading...';
    document.getElementById('detailWhatsapp').textContent = 'Loading...';
    document.getElementById('detailNomorPunggung').textContent = 'Loading...';
    document.getElementById('detailJumlahBaju').textContent = 'Loading...';

    const tableBody = document.getElementById('detailTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-500">Loading...</td></tr>';

    // Fetch detail data from server
    fetch(`/pesanan/${idPesanan}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const pemesan = data.data;

            // Update modal content
            document.getElementById('detailNama').textContent = pemesan.nama_pemesan;
            document.getElementById('detailWhatsapp').textContent = pemesan.nomor_whatsapp;
            document.getElementById('detailNomorPunggung').textContent = `#${pemesan.nomor_punggung}`;
            document.getElementById('detailJumlahBaju').textContent = `${pemesan.total_pesanan} pcs`;

            // Update detail anggota table
            tableBody.innerHTML = '';

            if (pemesan.detail_anggota && pemesan.detail_anggota.length > 0) {
                pemesan.detail_anggota.forEach((anggota, index) => {
                    const row = `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">${index + 1}</td>
                            <td class="py-3 font-medium">${anggota.nama_anggota}</td>
                            <td class="py-3">${anggota.umur} thn</td>
                            <td class="py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${anggota.jenis_kelamin === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">
                                    <i class="fas ${anggota.jenis_kelamin === 'Laki-laki' ? 'fa-mars' : 'fa-venus'} mr-1"></i>
                                    ${anggota.jenis_kelamin}
                                </span>
                            </td>
                            <td class="py-3 font-medium">${anggota.ukuran_baju}</td>
                            <td class="py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-soccer-green text-white">
                                    #${pemesan.nomor_punggung}
                                </span>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data anggota keluarga</td></tr>';
            }
        } else {
            console.error('Server returned error:', data);
            alert('Gagal memuat detail pesanan: ' + (data.message || 'Unknown error'));
            modal.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Terjadi kesalahan saat memuat detail pesanan: ' + error.message);
        modal.classList.add('hidden');
    });
}

// Close detail modal
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Add floating animation to soccer ball
setInterval(() => {
    const ball = document.querySelector('.fa-futbol');
    if (!ball) return;
    ball.style.transform = 'rotate(360deg) scale(1.1)';
    setTimeout(() => {
        ball.style.transform = 'rotate(0deg) scale(1)';
    }, 500);
}, 3000);

// (moved: confirm checkbox, submit handler, and whatsapp input are defined later in override section)

// Real-time validation for WhatsApp
function validateWhatsAppField() {
    const field = document.getElementById('nomorWhatsapp');
    const value = field.value.trim();

    // Clear previous error
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) existingError.remove();
    field.classList.remove('border-red-500', 'border-green-500');

    if (value && !isValidIndonesianPhone(value)) {
        showFieldError('nomorWhatsapp', 'Format nomor tidak valid. Gunakan: 08123456789');
    } else if (value && isValidIndonesianPhone(value)) {
        // AJAX validation for WhatsApp
        clearTimeout(window.whatsappValidationTimeout);
        window.whatsappValidationTimeout = setTimeout(() => {
            validateWhatsAppAJAX(value);
        }, 500);
    }
}

// Real-time validation for nama pemesan
const namaPemesanEl = document.getElementById('namaPemesan');
if (namaPemesanEl) {
    namaPemesanEl.addEventListener('input', function(e) {
        const field = e.target;
        const value = field.value.trim();

        // Clear previous error
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) existingError.remove();
        field.classList.remove('border-red-500', 'border-green-500');

        if (value && value.length > 100) {
            showFieldError('namaPemesan', 'Nama maksimal 100 karakter');
        } else if (value && value.length >= 3) {
            // Debounce AJAX validation
            clearTimeout(window.namaValidationTimeout);
            window.namaValidationTimeout = setTimeout(() => {
                validateCustomerNameAJAX(value);
            }, 500);
        }
    });
}

// Real-time validation for nomor punggung
const nomorPunggungEl = document.getElementById('nomorPunggung');
if (nomorPunggungEl) {
    nomorPunggungEl.addEventListener('input', function(e) {
        const field = e.target;
        const value = field.value.trim();

        // Clear previous error
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) existingError.remove();
        field.classList.remove('border-red-500', 'border-green-500');

        if (value && !isValidJerseyNumber(value)) {
            showFieldError('nomorPunggung', 'Nomor punggung harus 1-999');
        } else if (value && isValidJerseyNumber(value)) {
            // AJAX validation for jersey number
            clearTimeout(window.jerseyValidationTimeout);
            window.jerseyValidationTimeout = setTimeout(() => {
                validateJerseyNumberAJAX(value);
            }, 500);
        }
    });
}

// AJAX validation functions
function validateCustomerNameAJAX(name) {
    fetch('/api/validate-customer-name', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ nama_pemesan: name })
    })
    .then(response => response.json())
    .then(data => {
        const field = document.getElementById('namaPemesan');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) existingError.remove();
        field.classList.remove('border-red-500', 'border-green-500');

        if (!data.valid) {
            showFieldError('namaPemesan', data.message);
        } else {
            field.classList.add('border-green-500');
        }
    })
    .catch(error => {
        console.error('Validation error:', error);
    });
}

function validateWhatsAppAJAX(phone) {
    fetch('/api/validate-whatsapp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ nomor_whatsapp: phone })
    })
    .then(response => response.json())
    .then(data => {
        const field = document.getElementById('nomorWhatsapp');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) existingError.remove();
        field.classList.remove('border-red-500', 'border-green-500');

        if (!data.valid) {
            showFieldError('nomorWhatsapp', data.message);
        } else {
            field.classList.add('border-green-500');
        }
    })
    .catch(error => {
        console.error('Validation error:', error);
    });
}

function validateJerseyNumberAJAX(number) {
    fetch('/api/validate-jersey-number', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ nomor_punggung: number })
    })
    .then(response => response.json())
    .then(data => {
        const field = document.getElementById('nomorPunggung');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) existingError.remove();
        field.classList.remove('border-red-500', 'border-green-500');

        if (!data.valid) {
            showFieldError('nomorPunggung', data.message);
        } else {
            field.classList.add('border-green-500');
        }
    })
    .catch(error => {
        console.error('Validation error:', error);
    });
}

// --- Enhancements appended below (override/extend) ---
let previousStep = 1;
let lastSubmission = null;

// Override: progress with animation + percent (explicit by IDs)
function updateProgress() {
    const steps = [
        document.getElementById('step1'),
        document.getElementById('step2'),
        document.getElementById('step3'),
    ];
    steps.forEach((el, idx) => {
        if (!el) return;
        const stepIndex = idx + 1;
        if (stepIndex === currentStep) {
            el.classList.remove('hidden');
            el.classList.add('opacity-0');
            el.classList.remove('-translate-x-6', 'translate-x-6');
            el.classList.add(currentStep > previousStep ? 'translate-x-6' : '-translate-x-6');
            requestAnimationFrame(() => {
                el.classList.add('transition', 'duration-300', 'ease-in-out');
                el.classList.remove('opacity-0', 'translate-x-6', '-translate-x-6');
                el.classList.add('opacity-100', 'translate-x-0');
            });
        } else {
            el.classList.add('hidden');
            el.classList.remove('opacity-100', 'translate-x-0');
        }
    });
    const percent = Math.round((currentStep / totalSteps) * 100);
    const bar = document.getElementById('progressBarFill');
    const pct = document.getElementById('progressPercent');
    if (bar) bar.style.width = percent + '%';
    if (pct) pct.textContent = percent;
}

// Override: next/prev to track direction
function nextStep() {
    if (validateCurrentStep()) {
        previousStep = currentStep;
        if (currentStep === 1) {
            generateFamilyForms();
        } else if (currentStep === 2) {
            generateSummary();
        }
        currentStep++;
        updateProgress();
        scrollToTop();
    }
}
function prevStep() {
    previousStep = currentStep;
    currentStep--;
    updateProgress();
    scrollToTop();
}

// Override: add jersey icon and auto-fill pemesan for anggota #1
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
                        <i class="fas fa-shirt ml-2 text-soccer-accent"></i>
                    </h3>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Anggota *</label>
                        <input type="text" name="anggota[${i-1}][nama_anggota]" placeholder="Nama lengkap" value="${isMainOrder ? (orderData.pemesan.nama || '') : ''}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Umur *</label>
                        <input type="number" name="anggota[${i-1}][umur]" placeholder="Umur"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Jenis Kelamin *</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="anggota[${i-1}][jenis_kelamin]" value="Laki-laki" class="form-radio text-soccer-green">
                                <span class="ml-2">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="anggota[${i-1}][jenis_kelamin]" value="Perempuan" class="form-radio text-soccer-green">
                                <span class="ml-2">Perempuan</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Ukuran Baju *</label>
                        <select name="anggota[${i-1}][ukuran_baju]" onchange="updatePrice(this)"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-soccer-green focus:outline-none transition-colors" required>
                            ${buildUkuranOptions()}
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
        container.insertAdjacentHTML('beforeend', cardHtml);
    }
}

// (removed overrides — original functions updated directly above to avoid recursion)

// Confirm checkbox enable/disable
const confirmCheckboxEl = document.getElementById('confirmCheckbox');
if (confirmCheckboxEl) {
    confirmCheckboxEl.addEventListener('change', function() {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) submitBtn.disabled = !this.checked;
    });
}

// WhatsApp input formatting + validation
const whatsappEl = document.getElementById('nomorWhatsapp');
if (whatsappEl) {
    whatsappEl.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        } else if (!value.startsWith('62')) {
            value = '62' + value;
        }
        e.target.value = value;
        validateWhatsAppField();
    });
}

// Submit handler with spinner + success modal population
const pesananFormEl = document.getElementById('pesananForm');
if (pesananFormEl) {
    pesananFormEl.addEventListener('submit', function(e) {
        e.preventDefault();
        const confirmCheckbox = document.getElementById('confirmCheckbox');
        if (!confirmCheckbox || !confirmCheckbox.checked) {
            alert('Mohon centang konfirmasi pesanan terlebih dahulu');
            return;
        }
        const hasErrors = document.querySelectorAll('.field-error').length > 0;
        if (hasErrors) {
            alert('Mohon perbaiki data yang salah sebelum mengirim pesanan');
            return;
        }
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        submitBtn.disabled = true;
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                lastSubmission = { id: data.data.id_pesanan, total_harga: data.data.total_harga, total_pesanan: data.data.total_pesanan };
                const modal = document.getElementById('successModal');
                const sumBox = document.getElementById('successSummary');
                const sumJumlah = document.getElementById('successJumlah');
                const sumHarga = document.getElementById('successTotalHarga');
                const invoiceBtn = document.getElementById('invoiceDownloadBtn');
                if (sumBox && sumJumlah && sumHarga) {
                    sumJumlah.textContent = `${lastSubmission.total_pesanan} pcs`;
                    sumHarga.textContent = `Rp${parseInt(lastSubmission.total_harga).toLocaleString('id-ID')}`;
                    sumBox.classList.remove('hidden');
                }
                if (invoiceBtn && lastSubmission.id) {
                    invoiceBtn.href = `/pesanan/${lastSubmission.id}/invoice`;
                    invoiceBtn.classList.remove('hidden');
                }
                modal.classList.remove('hidden');
                loadPemesanList();
            } else {
                let errorMessage = 'Terjadi kesalahan saat menyimpan pesanan';
                if (data.message) errorMessage = data.message;
                if (data.errors) {
                    const errorList = Object.values(data.errors).flat().join('\n');
                    errorMessage += '\n\nDetail error:\n' + errorList;
                }
                alert(errorMessage);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan saat mengirim pesanan');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// WA helper
function openWhatsApp() {
    const nama = orderData?.pemesan?.nama || '';
    const nomor = (orderData?.pemesan?.whatsapp || '').replace(/[^0-9]/g, '');
    const jumlah = lastSubmission?.total_pesanan || orderData?.total?.jumlah || 0;
    const total = lastSubmission?.total_harga || orderData?.total?.harga || 0;
    const styleReq = (document.getElementById('styleRequest')?.value || '').trim();
    let message = `Halo, pesanan jersey saya:\n` +
        `Nama: ${nama}\n` +
        `Nama Punggung: ${orderData?.pemesan?.namaPunggung || '-'}\n` +
        `No Punggung: ${orderData?.pemesan?.punggung || ''}\n` +
        (styleReq ? `Style Request: ${styleReq}\n` : '') +
        `Jumlah Baju: ${jumlah} pcs\n` +
        `Total Harga: Rp${parseInt(total).toLocaleString('id-ID')}\n`;
    try {
        if (orderData?.anggota?.length) {
            message += `\nRincian Anggota:`;
            orderData.anggota.forEach((a, idx) => {
                const printName = a.namaCetak && a.namaCetak.trim() ? a.namaCetak : a.nama;
                message += `\n${idx + 1}. ${a.nama} (${a.gender}, ${a.umur} th) - ${a.ukuran}` + (printName ? ` | Nama Jersey: ${printName.toUpperCase()}` : '');
            });
            message += `\n`;
        }
    } catch (e) {}
    if (lastSubmission?.id) {
        message += `ID Pesanan: #${lastSubmission.id}\n` +
                   `Invoice: ${window.location.origin}/pesanan/${lastSubmission.id}/invoice`;
    }
    const url = nomor ? `https://wa.me/${nomor}?text=${encodeURIComponent(message)}` : `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
}

// Reset and go to dashboard
function resetForm() {
    try { document.getElementById('successModal')?.classList.add('hidden'); } catch (e) {}
    window.location.href = '/dashboard';
}

// Detail modal
function showDetailPemesan(idPesanan) {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('hidden');
    document.getElementById('detailNama').textContent = 'Loading...';
    document.getElementById('detailWhatsapp').textContent = 'Loading...';
    document.getElementById('detailNomorPunggung').textContent = 'Loading...';
    document.getElementById('detailJumlahBaju').textContent = 'Loading...';
    const tableBody = document.getElementById('detailTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-500">Loading...</td></tr>';
    fetch(`/pesanan/${idPesanan}`, { method: 'GET', headers: { 'Accept': 'application/json','X-Requested-With':'XMLHttpRequest' }})
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Unknown error');
            const pemesan = data.data;
            document.getElementById('detailNama').textContent = pemesan.nama_pemesan;
            document.getElementById('detailWhatsapp').textContent = pemesan.nomor_whatsapp;
            document.getElementById('detailNomorPunggung').textContent = `#${pemesan.nomor_punggung}`;
            document.getElementById('detailJumlahBaju').textContent = `${pemesan.total_pesanan} pcs`;
            tableBody.innerHTML = '';
            if (pemesan.detail_anggota && pemesan.detail_anggota.length > 0) {
                pemesan.detail_anggota.forEach((anggota, index) => {
                    const row = `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">${index + 1}</td>
                            <td class="py-3 font-medium">${anggota.nama_anggota}</td>
                            <td class="py-3">${anggota.umur} thn</td>
                            <td class="py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${anggota.jenis_kelamin === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">
                                    <i class="fas ${anggota.jenis_kelamin === 'Laki-laki' ? 'fa-mars' : 'fa-venus'} mr-1"></i>
                                    ${anggota.jenis_kelamin}
                                </span>
                            </td>
                            <td class="py-3 font-medium">${anggota.ukuran_baju}</td>
                            <td class="py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-soccer-green text-white">
                                    #${pemesan.nomor_punggung}
                                </span>
                            </td>
                        </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data anggota keluarga</td></tr>';
            }
        })
        .catch(err => {
            console.error('Detail error:', err);
            alert('Terjadi kesalahan saat memuat detail pesanan: ' + err.message);
            modal.classList.add('hidden');
        });
}
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Animate header ball
setInterval(() => {
    const ball = document.querySelector('.fa-futbol');
    if (!ball) return;
    ball.style.transform = 'rotate(360deg) scale(1.1)';
    setTimeout(() => { ball.style.transform = 'rotate(0deg) scale(1)'; }, 500);
}, 3000);

// Search input binding
const searchPemesanEl = document.getElementById('searchPemesan');
if (searchPemesanEl) {
    searchPemesanEl.addEventListener('input', function(e) {
        const searchTerm = e.target.value.trim();
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => { loadPemesanList(1, searchTerm); }, 500);
    });
}

// Load initial pemesan list
document.addEventListener('DOMContentLoaded', () => {
    try { updateProgress(); } catch (e) {}
    if (typeof loadPemesanList === 'function') {
        try { loadPemesanList(); } catch (e) {}
    }
});
