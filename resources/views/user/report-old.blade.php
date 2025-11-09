@extends('layouts.user-app')

@section('title', 'Laporan Harian')

@section('content')
    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="animate-slide-up">
            <!-- Content Container -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <!-- Content Header -->
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
                        <div class="mt-4 sm:mt-0">
                            <a href="/admin">
                                <button
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Buat Laporan Baru
                                </button></a>
                        </div>
                    </div>
                </div>


                <!-- Simple Production Report Form -->
                <div class="px-8 py-16">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-red-800">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('reports.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Shift Select -->
                            <div class="md:col-span-2">
                                <label for="shift_id"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Shift <span class="text-red-500">*</span>
                                </label>
                                <select id="shift_id" name="shift_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <option value="">Pilih Shift</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}"
                                            {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Machine Select -->
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

                            <!-- Construction Select -->
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

                            <!-- stock Input -->
                            <div class="md:col-span-2">
                                <label for="stock"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Hasil yang Didapat / meter <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stock" name="stock" step="1" min="0"
                                    value="{{ old('stock') }}" required placeholder="0 Meter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                            </div>

                            <!-- overtime Input -->
                            <div class="md:col-span-2">
                                <label for="overtime"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Overtime / jam <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="overtime" name="overtime" step="1" min="0"
                                    value="{{ old('overtime') }}" placeholder="0 Jam"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
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

                    <!-- Reports List -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Laporan Hari Ini</h3>
                        <div class="space-y-3">
                            @if ($reports->count() > 0)
                                @foreach ($reports as $report)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-600">Shift:</span>
                                                <span class="block text-gray-900">{{ $report->shift_name }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Mesin:</span>
                                                <span class="block text-gray-900">{{ $report->machine_kd_mach }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Konstruksi:</span>
                                                <span class="block text-gray-900">{{ $report->construction_name }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Hasil:</span>
                                                <span
                                                    class="block text-gray-900 font-semibold">{{ number_format($report->stock) }}
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
    // FORM USER
    function resetForm() {
        if (confirm('Apakah Anda yakin ingin membatalkan? Data yang telah diisi akan hilang.')) {
            document.querySelector('form').reset();
        }
    }

    // Auto hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {

        // Data relasi dari controller
        const machineConstructions = @json($machineConstructions);

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

                // Show "Sedang mencari..." jika tidak ada hasil
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
            document.getElementById('shift_dropdown').classList.add('hidden');
            document.getElementById('machine_dropdown').classList.add('hidden');
            document.getElementById('construction_dropdown').classList.add('hidden');
        }

        function showNoResults(dropdown) {
            if (!dropdown.querySelector('.no-results')) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results px-3 py-2 text-gray-500 text-sm';
                noResults.textContent = 'Sedang mencari...';
                dropdown.appendChild(noResults);
            }
        }

        function hideNoResults(dropdown) {
            const noResults = dropdown.querySelector('.no-results');
            if (noResults) {
                noResults.remove();
            }
        }

        const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        });
    });
</script>
