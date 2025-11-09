<div class="space-y-4">
    @foreach ($reports as $report)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Mesin</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $report->machine->kd_mach }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Construction</div>
                    <div class="text-sm text-gray-900 dark:text-white">
                        {{ $report->construction->name }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock</div>
                    <div
                        class="text-lg font-semibold 
                        {{ $report->stock > 100 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $report->stock }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Efficiency</div>
                    <div
                        class="text-lg font-semibold 
                        @if ($report->eff >= 80) text-green-600 dark:text-green-400
                        @elseif($report->eff >= 60)
                            text-yellow-600 dark:text-yellow-400
                        @else
                            text-red-600 dark:text-red-400 @endif
                    ">
                        {{ $report->eff }}%
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">
                        Overtime:
                        <span
                            class="{{ $report->overtime > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400' }} font-medium">
                            {{ $report->overtime }} jam
                        </span>
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">
                        Dibuat: {{ $report->created_at->format('H:i') }}
                    </span>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 flex justify-end items-center gap-4">
                <a href="{{ route('filament.admin.resources.reports.edit', ['record' => $report->id]) }}"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200 hover:underline">
                    <x-heroicon-o-pencil class="w-4 h-4 mr-1" />
                    Edit
                </a>

                <span class="text-gray-300 dark:text-gray-600">|</span>

                <button type="button" wire:click="deleteReport({{ $report->id }})"
                    wire:confirm="Yakin hapus data ini? Data yang dihapus tidak bisa dikembalikan!"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 hover:underline">
                    <x-heroicon-o-trash class="w-4 h-4 mr-1" />
                    Hapus
                </button>
            </div>
        </div>
    @endforeach
</div>
