@extends('layouts.user-app')

@section('title', 'Laporan Harian')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="animate-slide-up">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div
                    class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-gray-50/50 to-blue-50/30 dark:from-gray-800/50 dark:to-blue-900/20">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                Laporan Harian
                            </h2>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">
                                {{ $hariini }} {{ $namabulan[$bulanini] }} {{ $tahunini }}
                            </p>
                        </div>
                        @hasrole(['Admin', 'Super Admin'])
                            <div class="mt-4 sm:mt-0">
                                <a href="/admin">
                                    <button
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        Dahsboard Admin
                                    </button></a>
                            </div>
                        @endhasrole
                    </div>
                </div>


                <div class="px-8 py-8">
                    <div id="alert-container"></div>

                    <form id="report-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="shift_id"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Shift <span class="text-red-500">*</span>
                                </label>
                                <select id="shift_id" name="shift_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <option value="">Pilih Shift</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}">
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="overtime"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Overtime / jam
                                </label>
                                <input type="number" id="overtime" name="overtime" step="1" min="0"
                                    placeholder="0 Jam"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                            </div>

                            <div class="md:col-span-1 relative">
                                <label for="machine_input"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Kode Mesin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input style="text-transform: capitalize" type="text" id="machine_input"
                                        name="machine_kd_mach" placeholder="Pilih salah satu opsi" autocomplete="off"
                                        required
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <input type="hidden" name="machine_id" id="machine_id">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="machine_dropdown"
                                    class="hidden absolute z-10 w-full mt-1 text-gray-600 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                    @foreach ($machines as $machine)
                                        <div class="machine-option px-3 py-2 hover:bg-blue-50 cursor-pointer"
                                            data-id="{{ $machine->id }}" data-kd_mach="{{ $machine->kd_mach }}">
                                            {{ $machine->kd_mach }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-1 relative">
                                <label for="construction_input"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Konstruksi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="construction_input" name="construction_name"
                                        placeholder="Pilih salah satu opsi" autocomplete="off" required
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <input type="hidden" name="construction_id" id="construction_id">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="construction_dropdown"
                                    class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                    @foreach ($constructions as $construction)
                                        <div class="construction-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-gray-600"
                                            data-id="{{ $construction->id }}" data-name="{{ $construction->name }}">
                                            {{ $construction->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="stock"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Hasil yang Didapat / meter <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="stock" name="stock" required placeholder="0,0 Meter"
                                    inputmode="decimal"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 pb-8 border-b-4 border-gray-200">
                            <button type="submit"
                                class="flex-1 sm:flex-none px-6 py-2 bg-blue-600 text-white font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Simpan
                            </button>
                            <button type="button" onclick="resetForm()"
                                class="flex-1 sm:flex-none px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 ">
                        <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-gray-100 mb-4">Data Laporan
                            Hari Ini</h3>
                        <div id="reports-list" class="space-y-3">
                            @if ($reports->count() > 0)
                                @foreach ($reports as $report)
                                    <div
                                        class="border-gray-200/50 dark:border-gray-700/50 border bg-gradient-to-r from-gray-50/50 to-blue-50/30 dark:from-gray-800/50 dark:to-blue-900/20 rounded-lg p-4">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-400">Shift:</span>
                                                <span
                                                    class="block text-gray-900 dark:text-gray-100">{{ $report->shift_name }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-400">Mesin:</span>
                                                <span
                                                    class="block text-gray-900 dark:text-gray-100">{{ $report->machine_kd_mach }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Konstruksi:</span>
                                                <span
                                                    class="block text-gray-900 dark:text-gray-100">{{ $report->construction_name }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Hasil:</span>
                                                <span
                                                    class="block text-gray-900 dark:text-gray-100 font-semibold">{{ $report->stock }}
                                                    {{ 'meter' }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500">
                                            Dibuat: {{ \Carbon\Carbon::parse($report->created_at)->format('H:i:s') }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p>Belum ada laporan hari ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportForm = document.getElementById('report-form');
        const reportsList = document.getElementById('reports-list');
        const alertContainer = document.getElementById('alert-container');
        const shiftIdInput = document.getElementById('shift_id');
        const overtimeInput = document.getElementById('overtime');
        const machineIdInput = document.getElementById('machine_id');
        const constructionIdInput = document.getElementById('construction_id');
        const stockInput = document.getElementById('stock');

        const machineConstructions = @json($machineConstructions);

        // ========== BAGIAN BARU: Setup Decimal Input untuk Stock ==========
        function setupDecimalInput() {
            stockInput.addEventListener('input', function(e) {
                let value = e.target.value;

                // Hanya izinkan angka dan koma
                value = value.replace(/[^0-9,]/g, '');

                // Hanya satu koma yang diizinkan
                const commaIndex = value.indexOf(',');
                if (commaIndex !== -1) {
                    value = value.substring(0, commaIndex + 1) + value.substring(commaIndex + 1)
                        .replace(/,/g, '');
                }

                // Maksimal 2 digit setelah koma
                const parts = value.split(',');
                if (parts[1] && parts[1].length > 2) {
                    value = parts[0] + ',' + parts[1].substring(0, 2);
                }

                e.target.value = value;
            });
        }

        // Initialize decimal input
        setupDecimalInput();

        // ========== FUNGSI FORMAT DECIMAL UNTUK DISPLAY ==========
        function formatWithComma(number) {
            return parseFloat(number).toString().replace('.', ',');
        }

        // Fungsi untuk menampilkan alert
        function showAlert(message, type) {
            const alertHtml = `
                <div class="mb-6 p-4 ${type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'} rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ${type === 'success' ? 'text-green-600' : 'text-red-600'} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>' : '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'}
                        </svg>
                        <div class="${type === 'success' ? 'text-green-800' : 'text-red-800'} font-medium">
                            ${message}
                        </div>
                    </div>
                </div>`;
            alertContainer.innerHTML = alertHtml;
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // ========== BAGIAN YANG DIUBAH: Function untuk menambahkan laporan baru ke daftar ==========
        function addNewReportToList(data) {
            const newReportHtml = `
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Shift:</span>
                            <span class="block text-gray-900">${data.shift_name}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Mesin:</span>
                            <span class="block text-gray-900">${data.machine_kd_mach}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Konstruksi:</span>
                            <span class="block text-gray-900">${data.construction_name}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Hasil:</span>
                            <span class="block text-gray-900 font-semibold">${formatWithComma(data.stock)} meter</span>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Dibuat: ${data.created_at}
                    </div>
                </div>`;

            // Hapus pesan "Belum ada laporan" jika ada
            const noReportMessage = reportsList.querySelector('div.text-center');
            if (noReportMessage) {
                noReportMessage.remove();
            }

            reportsList.insertAdjacentHTML('afterbegin', newReportHtml);
        }

        // ========== BAGIAN YANG DIUBAH: Menangani pengiriman form dengan AJAX ==========
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil token CSRF dan data form
            const formData = new FormData(this);
            const csrfToken = formData.get('_token');

            // ========== BAGIAN BARU: Konversi koma ke titik untuk stock sebelum dikirim ==========
            const stockValue = formData.get('stock').replace(',', '.');

            const data = {
                shift_id: formData.get('shift_id'),
                overtime: formData.get('overtime'),
                machine_id: formData.get('machine_id'),
                construction_id: formData.get('construction_id'),
                stock: stockValue // Menggunakan nilai yang sudah dikonversi
            };

            fetch('{{ route('reports.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    // Periksa apakah respons adalah JSON sebelum parsing
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json().then(json => {
                            if (!response.ok) {
                                // Jika respons bukan 2xx, throw error
                                const errorMessage = Object.values(json.errors).flat().join(
                                    '<br>');
                                throw new Error(errorMessage);
                            }
                            return json;
                        });
                    } else {
                        // Tangani respons non-JSON
                        return response.text().then(text => {
                            throw new Error(`Respons tidak valid: ${text}`);
                        });
                    }
                })
                .then(result => {
                    showAlert('Laporan berhasil disimpan!', 'success');
                    addNewReportToList(result.report);

                    // Reset form, kecuali field shift dan overtime
                    machineIdInput.value = '';
                    stockInput.value = '';
                    constructionIdInput.value = '';
                    document.getElementById('machine_input').value = '';
                    document.getElementById('construction_input').value = '';

                    // Biarkan shift dan overtime tetap

                })
                .catch(error => {
                    showAlert(`Terjadi kesalahan:<br>${error.message}`, 'error');
                });
        });

        // Searchable Select untuk Machine 
        setupSearchableSelect('machine_input', 'machine_dropdown', 'machine-option', 'machine_id', 'kd_mach');

        // Searchable Select untuk Construction
        setupSearchableSelect('construction_input', 'construction_dropdown', 'construction-option',
            'construction_id', 'name');

        function setupSearchableSelect(inputId, dropdownId, optionClass, hiddenId, valueAttr) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const hiddenInput = document.getElementById(hiddenId);
            const options = dropdown.querySelectorAll('.' + optionClass);

            // Show dropdown saat input diklik atau difokus
            input.addEventListener('click', function() {
                hideAllDropdowns();
                dropdown.classList.remove('hidden');
                filterOptions('');
            });

            input.addEventListener('focus', function() {
                hideAllDropdowns();
                dropdown.classList.remove('hidden');
                filterOptions('');
            });

            // Filter options saat mengetik
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                dropdown.classList.remove('hidden');
                filterOptions(searchTerm);

                // Reset hidden input jika user mengetik manual
                hiddenInput.value = '';
            });

            // Handle click pada option
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const displayText = valueAttr === 'kd_mach' ?
                        this.getAttribute('data-kd_mach') :
                        this.getAttribute('data-' + valueAttr);

                    input.value = displayText;
                    hiddenInput.value = id;
                    dropdown.classList.add('hidden');

                    // Auto-fill konstruksi jika ini adalah pemilihan mesin
                    if (inputId === 'machine_input') {
                        autoFillConstruction(displayText);
                    }
                });
            });

            function filterOptions(searchTerm) {
                let hasVisible = false;
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisible = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Show "Data tidak ditemukan" jika tidak ada hasil
                if (!hasVisible && searchTerm) {
                    showNoResults(dropdown);
                } else {
                    hideNoResults(dropdown);
                }
            }
        }

        // Function untuk auto-fill konstruksi berdasarkan mesin
        function autoFillConstruction(machineCode) {
            const constructionInput = document.getElementById('construction_input');
            const constructionHidden = document.getElementById('construction_id');

            // Cari konstruksi yang sesuai dengan mesin
            const machineData = machineConstructions[machineCode];

            if (machineData) {
                constructionInput.value = machineData.construction_name;
                constructionHidden.value = machineData.construction_id;

                // Tambahkan efek visual untuk menunjukkan auto-fill
                constructionInput.classList.add('bg-blue-50', 'border-blue-300');
                setTimeout(() => {
                    constructionInput.classList.remove('bg-blue-50', 'border-blue-300');
                }, 1000);

                // Optional: Tampilkan notifikasi kecil
                showAutoFillNotification('Konstruksi otomatis terisi berdasarkan data terakhir');
            }
        }

        // Function untuk menampilkan notifikasi auto-fill
        function showAutoFillNotification(message) {
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Hide semua dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                hideAllDropdowns();
            }
        });

        function hideAllDropdowns() {
            document.getElementById('machine_dropdown').classList.add('hidden');
            document.getElementById('construction_dropdown').classList.add('hidden');
        }

        function showNoResults(dropdown) {
            if (!dropdown.querySelector('.no-results')) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results px-3 py-2 text-gray-500 text-sm';
                noResults.textContent = 'Data tidak ditemukan';
                dropdown.appendChild(noResults);
            }
        }

        function hideNoResults(dropdown) {
            const noResults = dropdown.querySelector('.no-results');
            if (noResults) {
                noResults.remove();
            }
        }
    });

    // FORM USER
    function resetForm() {
        if (confirm('Apakah Anda yakin ingin membatalkan? Data yang telah diisi akan hilang.')) {
            // Reset seluruh form
            document.getElementById('report-form').reset();
            // Kosongkan hidden inputs juga
            document.getElementById('machine_id').value = '';
            document.getElementById('construction_id').value = '';
        }
    }
</script>
