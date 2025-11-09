<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Actions;
use App\Models\Report;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportResource;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public ?int $activeShiftFilter = null;
    public ?string $activeDateFilter = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Buat Laporan")->icon("heroicon-o-plus")->url(route("report.index")),
        ];
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

        // Jika belum ada filter yang diset, gunakan default
        // if (empty($this->tableFilters)) {
        //     $this->tableFilters = [
        //         "shift_id" => ["value" => 1], // Default ke Shift Pagi
        //         "created_at" => [
        //             "from" => now(),
        //             "until" => now(),
        //         ],
        //     ];
        // }
    }

    // Method untuk reset ke default filters
    public function resetToDefaultFilters(): void
    {
        $this->tableFilters = [
            "shift_id" => ["value" => 1],
            "created_at" => [
                "from" => now(),
                "until" => now(),
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

    protected $listeners = [
        "restore-group" => "handleRestoreGroup",
        "force-delete-group" => "handleForceDeleteGroup",
        "restore-single" => "handleRestoreSingle",
        "force-delete-single" => "handleForceDeleteSingle",
    ];

    public function handleRestoreGroup($data)
    {
        try {
            $groupKey = $data["groupKey"];
            [$userId, $shiftId, $date] = explode("-", $groupKey);

            $reports = Report::onlyTrashed()
                ->where("user_id", $userId)
                ->where("shift_id", $shiftId)
                ->whereDate("created_at", $date)
                ->get();

            foreach ($reports as $report) {
                $report->restore();
            }

            \Filament\Notifications\Notification::make()
                ->title("Berhasil Dipulihkan")
                ->body("Semua data dalam grup berhasil dipulihkan!")
                ->success()
                ->send();

            $this->dispatch("close-modal");
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title("Gagal Memulihkan")
                ->body("Terjadi kesalahan saat memulihkan data.")
                ->danger()
                ->send();
        }
    }

    public function handleForceDeleteGroup($data)
    {
        try {
            $groupKey = $data["groupKey"];
            [$userId, $shiftId, $date] = explode("-", $groupKey);

            $reports = Report::onlyTrashed()
                ->where("user_id", $userId)
                ->where("shift_id", $shiftId)
                ->whereDate("created_at", $date)
                ->get();

            foreach ($reports as $report) {
                $report->forceDelete();
            }

            \Filament\Notifications\Notification::make()
                ->title("Berhasil Dihapus Permanen")
                ->body("Semua data dalam grup berhasil dihapus permanen!")
                ->success()
                ->send();

            $this->dispatch("close-modal");
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title("Gagal Menghapus")
                ->body("Terjadi kesalahan saat menghapus data.")
                ->danger()
                ->send();
        }
    }

    public function handleRestoreSingle($data)
    {
        try {
            $report = Report::onlyTrashed()->findOrFail($data["reportId"]);
            $report->restore();

            \Filament\Notifications\Notification::make()
                ->title("Berhasil Dipulihkan")
                ->body("Data berhasil dipulihkan!")
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title("Gagal Memulihkan")
                ->body("Terjadi kesalahan saat memulihkan data.")
                ->danger()
                ->send();
        }
    }

    public function handleForceDeleteSingle($data)
    {
        try {
            $report = Report::onlyTrashed()->findOrFail($data["reportId"]);
            $report->forceDelete();

            \Filament\Notifications\Notification::make()
                ->title("Berhasil Dihapus Permanen")
                ->body("Data berhasil dihapus permanen!")
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title("Gagal Menghapus")
                ->body("Terjadi kesalahan saat menghapus data.")
                ->danger()
                ->send();
        }
    }
}
