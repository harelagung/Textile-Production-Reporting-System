<div class="space-y-4 max-h-96 overflow-y-auto">
    @php
        // Debug: Cek apakah variabel ada
        $trashedReports = \App\Models\Report::onlyTrashed()
            ->with(['user', 'shift', 'machine', 'construction'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        $trashedGroups = $trashedReports->groupBy(function ($report) {
            return $report->user_id . '-' . $report->shift_id . '-' . $report->created_at->format('Y-m-d');
        });
    @endphp

    @if ($trashedGroups->isEmpty())
        <div class="text-center py-8">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800">
                <x-heroicon-o-archive-box-x-mark class="h-6 w-6 text-gray-400" />
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data yang dihapus</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua data masih aktif.</p>
        </div>
    @else
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Ditemukan {{ $trashedGroups->count() }} grup data yang sudah dihapus.
        </p>

        @foreach ($trashedGroups as $groupKey => $reports)
            @php
                $firstReport = $reports->first();
                $machineCount = $reports->count();
                $totalStock = $reports->sum('stock');
                $avgEff = $reports->avg('eff');
            @endphp

            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-700">
                <!-- Header Info -->
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $firstReport->user->name }} - {{ $firstReport->shift->name }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $firstReport->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            <x-heroicon-o-trash class="w-3 h-3 mr-1" />
                            Dihapus: {{ $firstReport->deleted_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                <!-- Summary Info -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $machineCount }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Mesin</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($totalStock) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Stock</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($avgEff, 1) }}%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Avg EFF</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-2">
                    <button type="button" onclick="alert('Restore Group: {{ $groupKey }}')"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700 transition-colors duration-200">
                        <x-heroicon-o-arrow-path class="w-4 h-4 mr-1" />
                        Pulihkan Semua ({{ $machineCount }} mesin)
                    </button>

                    <button type="button" onclick="alert('Force Delete Group: {{ $groupKey }}')"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700 transition-colors duration-200">
                        <x-heroicon-o-x-mark class="w-4 h-4 mr-1" />
                        Hapus Permanen
                    </button>
                </div>

                <!-- Detail Mesin -->
                <div class="mt-4 pt-4 border-t border-red-200 dark:border-red-700">
                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Mesin:</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($reports as $report)
                            <div class="bg-white dark:bg-gray-800 rounded p-2 text-xs">
                                <div class="font-medium">{{ $report->machine->kd_mach }}</div>
                                <div class="text-gray-500">{{ $report->construction->name }}</div>
                                <div class="text-blue-600">Stock: {{ number_format($report->stock) }}</div>
                                <div class="text-green-600">EFF: {{ $report->eff }}%</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
