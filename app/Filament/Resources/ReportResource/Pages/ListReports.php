<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Actions;
use App\Models\Report;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportResource;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public ?int $activeShiftFilter = null;
    public ?string $activeDateFilter = "today";

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // === PRINT ACTIONS ===
    //         Action::make("print_report")
    //             ->label("Cetak Laporan")
    //             ->button()
    //             ->color("success")
    //             ->icon("heroicon-o-printer")
    //             ->extraAttributes([
    //                 "class" => "relative",
    //             ])
    //             ->form([
    //                 Forms\Components\Select::make("print_type")
    //                     ->label("Pilih Format")
    //                     ->options([
    //                         "pdf" => "Cetak PDF",
    //                         "excel" => "Ekspor Excel",
    //                     ])
    //                     ->required()
    //                     ->placeholder("Pilih format cetak")
    //                     ->native(false),
    //             ])
    //             ->fillForm([
    //                 "print_type" => "pdf",
    //             ])
    //             ->action(function (array $data) {
    //                 // Ambil filter yang sedang aktif
    //                 $filters = $this->tableFilters;

    //                 if ($data["print_type"] === "pdf") {
    //                     // Untuk PDF - cetak semua shift dalam satu hari
    //                     $params = [
    //                         "date_from" => $filters["created_at"]["from"] ?? now()->format("Y-m-d"),
    //                         "date_until" => $filters["created_at"]["until"] ?? now()->format("Y-m-d"),
    //                         "all_shifts" => true, // Parameter baru untuk menandai cetak semua shift
    //                     ];

    //                     $url = route("reports.print-pdf", array_filter($params));

    //                     // Notifikasi
    //                     \Filament\Notifications\Notification::make()
    //                         ->title("Menyiapkan PDF")
    //                         ->body("Laporan PDF untuk semua shift sedang disiapkan...")
    //                         ->success()
    //                         ->duration(3000)
    //                         ->send();

    //                     // Buka di tab baru menggunakan JavaScript
    //                     $this->js("window.open('$url', '_blank')");
    //                 } elseif ($data["print_type"] === "excel") {
    //                     // Untuk Excel - gunakan filter yang ada
    //                     $params = [
    //                         "shift_id" => $filters["shift_id"]["value"] ?? null,
    //                         "date_from" => $filters["created_at"]["from"] ?? null,
    //                         "date_until" => $filters["created_at"]["until"] ?? null,
    //                     ];

    //                     $url = route("reports.export-excel", array_filter($params));

    //                     // Notifikasi
    //                     \Filament\Notifications\Notification::make()
    //                         ->title("Mengunduh Excel")
    //                         ->body("File Excel sedang diunduh...")
    //                         ->success()
    //                         ->duration(3000)
    //                         ->send();

    //                     $this->js("window.location.href = '$url'");
    //                 }
    //             }),

    //         Actions\CreateAction::make()->label("Buat Laporan")->icon("heroicon-o-plus")->url(route("report.index")),
    //     ];
    // }

    protected function getHeaderActions(): array
    {
        return [
            // === PRINT ACTIONS ===
            Action::make("print_report")
                ->label("Cetak Laporan")
                ->button()
                ->color("success")
                ->icon("heroicon-o-printer")
                ->extraAttributes([
                    "class" => "relative",
                ])
                // ->form([
                //     Forms\Components\Select::make("print_type")
                //         ->label("Pilih Format")
                //         ->options([
                //             "pdf" => "Cetak PDF",
                //             "excel" => "Ekspor Excel",
                //         ])
                //         ->required()
                //         ->placeholder("Pilih format cetak")
                //         ->native(false),
                // ])
                // ->fillForm([
                //     "print_type" => "pdf",
                // ])
                ->action(function (array $data) {
                    // Ambil filter yang sedang aktif
                    $filters = $this->tableFilters;
                    $params = [
                        "date_from" => $filters["created_at"]["from"] ?? now()->format("Y-m-d"),
                        "date_until" => $filters["created_at"]["until"] ?? now()->format("Y-m-d"),
                        "all_shifts" => true, // Parameter baru untuk menandai cetak semua shift
                    ];

                    $url = route("reports.print-pdf", array_filter($params));

                    // Notifikasi
                    \Filament\Notifications\Notification::make()
                        ->title("Menyiapkan PDF")
                        ->body("Laporan PDF untuk semua shift sedang disiapkan...")
                        ->success()
                        ->duration(3000)
                        ->send();

                    // Buka di tab baru menggunakan JavaScript
                    $this->js("window.open('$url', '_blank')");
                }),

            Actions\CreateAction::make()->label("Buat Laporan")->icon("heroicon-o-plus")->url(route("report.index")),
        ];
    }

    // Method untuk JavaScript integration
    public function printReport($type, $params = [])
    {
        $queryString = http_build_query(array_filter($params));

        if ($type === "pdf") {
            $url = route("reports.print-pdf") . ($queryString ? "?" . $queryString : "");
            $this->js("window.open('$url', '_blank')");
        } elseif ($type === "excel") {
            $url = route("reports.export-excel") . ($queryString ? "?" . $queryString : "");
            $this->js("window.location.href = '$url'");
        }
    }

    // Override untuk mengatur default table filters saat halaman pertama kali dimuat
    public function mount(): void
    {
        parent::mount();

        // Set default state
        $this->activeShiftFilter = 1; // Default Shift 1
        $this->activeDateFilter = "today"; // Default hari ini

        $this->tableFilters = [
            "shift_id" => ["value" => 1],
            "created_at" => [
                "from" => now()->format("Y-m-d"),
                "until" => now()->format("Y-m-d"),
            ],
        ];
    }

    // Method untuk reset ke default filters
    public function resetToDefaultFilters(): void
    {
        $this->tableFilters = [
            "shift_id" => ["value" => 1],
            "created_at" => [
                "from" => now()->format("Y-m-d"),
                "until" => now()->format("Y-m-d"),
            ],
        ];
    }

    // Tambahkan method ini pada ListReports class (bukan di ReportResource)
    public function deleteReport($reportId)
    {
        try {
            $report = Report::findOrFail($reportId);
            $report->delete();

            // Notification success
            \Filament\Notifications\Notification::make()
                ->title("Berhasil dihapus")
                ->body("Laporan berhasil dihapus.")
                ->success()
                ->send();

            // Refresh table data
            $this->resetTable();

            return true;
        } catch (\Exception $e) {
            // Notification error
            \Filament\Notifications\Notification::make()
                ->title("Gagal menghapus")
                ->body("Terjadi kesalahan saat menghapus data.")
                ->danger()
                ->send();

            throw $e;
        }
    }
}
