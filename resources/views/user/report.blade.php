@extends('layouts.user-app')

@section('title', 'Laporan Harian')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="animate-slide-up">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <!-- Header Section -->
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
                                        Dashboard Admin
                                    </button>
                                </a>
                            </div>
                        @endhasrole
                    </div>
                </div>

                <!-- Main Content - Layout Horizontal -->
                <div class="flex flex-col lg:flex-row min-h-[600px]">
                    <!-- LEFT SIDE - Form Section -->
                    <div class="lg:w-1/2 px-8 py-8 border-r border-gray-200/50 dark:border-gray-700/50">
                        <div class="sticky top-8">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Input Laporan
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Isi form di bawah untuk menambah laporan baru
                                </p>
                            </div>

                            <div id="alert-container"></div>

                            <form id="report-form">
                                @csrf
                                <div class="space-y-6">
                                    <!-- Shift Selection -->
                                    <div>
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

                                    <!-- Overtime -->
                                    <div>
                                        <label for="overtime"
                                            class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Overtime / jam
                                        </label>
                                        <input type="number" id="overtime" name="overtime" step="1" min="0"
                                            placeholder="0 Jam"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    </div>

                                    <!-- Machine Selection -->
                                    <div class="relative">
                                        <label for="machine_input"
                                            class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Kode Mesin <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input style="text-transform: capitalize" type="text" id="machine_input"
                                                name="machine_kd_mach" placeholder="Pilih salah satu opsi"
                                                autocomplete="off" required
                                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                            <input type="hidden" name="machine_id" id="machine_id">
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div id="machine_dropdown"
                                            class="hidden absolute z-20 w-full mt-1 text-gray-600 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                            @foreach ($machines as $machine)
                                                <div class="machine-option px-3 py-2 hover:bg-blue-50 cursor-pointer"
                                                    data-id="{{ $machine->id }}" data-kd_mach="{{ $machine->kd_mach }}">
                                                    {{ $machine->kd_mach }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Construction Selection -->
                                    <div class="relative">
                                        <label for="construction_input"
                                            class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Konstruksi <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="construction_input" name="construction_name"
                                                placeholder="Pilih salah satu opsi" autocomplete="off" required
                                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                            <input type="hidden" name="construction_id" id="construction_id">
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div id="construction_dropdown"
                                            class="hidden absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                            @foreach ($constructions as $construction)
                                                <div class="construction-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-gray-600"
                                                    data-id="{{ $construction->id }}" data-name="{{ $construction->name }}">
                                                    {{ $construction->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Stock Input -->
                                    <div>
                                        <label for="stock"
                                            class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Hasil yang Didapat / meter <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="stock" name="stock" required placeholder="0,0 Meter"
                                            inputmode="decimal"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t border-gray-200">
                                    <button type="submit"
                                        class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Laporan
                                    </button>
                                    <button type="button" onclick="resetForm()"
                                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Reset Form
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT SIDE - Reports List -->
                    <div class="lg:w-1/2 px-8 py-8 bg-gray-50/30 dark:bg-gray-900/30">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                Data Laporan Hari Ini
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Daftar laporan yang telah diinput hari ini
                            </p>
                        </div>

                        <!-- Reports Container with Custom Scrollbar -->
                        <div class="h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                            <div id="reports-list" class="space-y-4">
                                @if ($reports->count() > 0)
                                    @foreach ($reports as $report)
                                        <div class="border border-gray-200/80 dark:border-gray-700/50 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
                                            data-report-id="{{ $report->id }}">
                                            <!-- Report Header -->
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                                    <span
                                                        class="text-xs font-medium text-blue-600 uppercase tracking-wide">
                                                        {{ $report->shift_name }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($report->created_at)->format('H:i') }}
                                                    </span>
                                                    <button onclick="deleteReport({{ $report->id }})"
                                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all duration-200 group"
                                                        title="Hapus Laporan">
                                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Report Content -->
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                <div class="space-y-1">
                                                    <span
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Mesin</span>
                                                    <span class="block text-gray-900 dark:text-gray-100 font-semibold">
                                                        {{ $report->machine_kd_mach }}
                                                    </span>
                                                </div>
                                                <div class="space-y-1">
                                                    <span
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Hasil</span>
                                                    <span
                                                        class="block text-gray-900 dark:text-gray-100 font-bold text-green-600">
                                                        {{ $report->stock }} m
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Construction Info -->
                                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                <span
                                                    class="text-xs font-medium text-gray-500 uppercase tracking-wide">Konstruksi</span>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $report->construction_name }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                                        <div
                                            class="w-16 h-16 mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum Ada
                                            Laporan</h4>
                                        <p class="text-sm text-gray-600">Mulai dengan mengisi form di sebelah kiri</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Custom Scrollbar Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <div class="border border-gray-200/80 dark:border-gray-700/50 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200 animate-fade-in" data-report-id="${data.id}">
                    <!-- Report Header -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-green-600 uppercase tracking-wide">
                                ${data.shift_name}
                            </span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">BARU</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-500">
                                ${data.created_at}
                            </span>
                            <button 
                                onclick="deleteReport(${data.id})"
                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all duration-200 group"
                                title="Hapus Laporan">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="space-y-1">
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Mesin</span>
                            <span class="block text-gray-900 dark:text-gray-100 font-semibold">
                                ${data.machine_kd_mach}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Hasil</span>
                            <span class="block text-gray-900 dark:text-gray-100 font-bold text-green-600">
                                ${formatWithComma(data.stock)} m
                            </span>
                        </div>
                    </div>

                    <!-- Construction Info -->
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Konstruksi</span>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            ${data.construction_name}
                        </p>
                    </div>
                </div>`;

            // Hapus pesan "Belum ada laporan" jika ada
            const noReportMessage = reportsList.querySelector('.flex.flex-col.items-center');
            if (noReportMessage) {
                noReportMessage.remove();
            }

            reportsList.insertAdjacentHTML('afterbegin', newReportHtml);

            // Scroll ke item baru
            reportsList.scrollTop = 0;

            // Remove "BARU" badge setelah 3 detik
            setTimeout(() => {
                const newBadge = reportsList.querySelector('.animate-pulse');
                if (newBadge) {
                    newBadge.classList.remove('animate-pulse');
                    const badge = reportsList.querySelector('.bg-green-100');
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            }, 3000);
        }

        // ========== BAGIAN YANG DIUBAH: Menangani pengiriman form dengan AJAX ==========
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Tambahkan loading state pada button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Menyimpan...`;
            submitBtn.disabled = true;

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
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
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

    // ========== FUNGSI UNTUK MENGHAPUS LAPORAN ==========
    function deleteReport(reportId) {
        Swal.fire({
            title: 'Yakin hapus laporan ini?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-4 py-2',
                cancelButton: 'rounded-lg px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus laporan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value;

                // Send delete request
                fetch(`/reports/${reportId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal menghapus laporan');
                        }
                        return response.json();
                    })
                    .then(result => {
                        // Remove the report from DOM
                        const reportElement = document.querySelector(`[data-report-id="${reportId}"]`);
                        if (reportElement) {
                            // Add fade out animation
                            reportElement.style.transition = 'all 0.3s ease-out';
                            reportElement.style.opacity = '0';
                            reportElement.style.transform = 'translateX(100px)';

                            setTimeout(() => {
                                reportElement.remove();

                                // Check if reports list is empty
                                const remainingReports = document.querySelectorAll(
                                    '[data-report-id]');
                                if (remainingReports.length === 0) {
                                    showEmptyState();
                                }
                            }, 300);
                        }

                        // Show success message
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Laporan telah dihapus',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-xl'
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus laporan',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'rounded-xl',
                                confirmButton: 'rounded-lg px-4 py-2'
                            }
                        });
                    });
            }
        });
    }

    // Function to show empty state when all reports are deleted
    function showEmptyState() {
        const reportsList = document.getElementById('reports-list');
        const emptyStateHtml = `
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <div class="w-16 h-16 mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum Ada Laporan</h4>
                <p class="text-sm text-gray-600">Mulai dengan mengisi form di sebelah kiri</p>
            </div>`;
        reportsList.innerHTML = emptyStateHtml;
    }

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
