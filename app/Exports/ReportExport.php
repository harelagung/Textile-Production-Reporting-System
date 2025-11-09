<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\Construction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    protected $shiftId;
    protected $dateFrom;
    protected $dateUntil;

    public function __construct($shiftId = null, $dateFrom = null, $dateUntil = null)
    {
        $this->shiftId = $shiftId;
        $this->dateFrom = $dateFrom;
        $this->dateUntil = $dateUntil;
    }

    public function sheets(): array
    {
        return [
            "Laporan Operator" => new ReportOperatorSheet($this->shiftId, $this->dateFrom, $this->dateUntil),
            "Data Konstruksi" => new ConstructionSheet(),
        ];
    }
}

class ReportOperatorSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, WithChunkReading
{
    protected $shiftId;
    protected $dateFrom;
    protected $dateUntil;
    protected $rowNumber = 0;

    public function __construct($shiftId = null, $dateFrom = null, $dateUntil = null)
    {
        $this->shiftId = $shiftId;
        $this->dateFrom = $dateFrom;
        $this->dateUntil = $dateUntil;
    }

    public function query()
    {
        // Gunakan query yang sama dengan Resource dan Controller
        $baseQuery = Report::with(["user", "shift", "machine", "construction"]);

        if ($this->shiftId) {
            $baseQuery->where("shift_id", $this->shiftId);
        }

        if ($this->dateFrom) {
            $baseQuery->whereDate("created_at", ">=", $this->dateFrom);
        }

        if ($this->dateUntil) {
            $baseQuery->whereDate("created_at", "<=", $this->dateUntil);
        }

        // Gunakan grouping yang sama dengan Resource
        $groupedIds = DB::table("reports")
            ->select(DB::raw("MIN(id) as min_id"))
            ->whereNull("deleted_at")
            ->when($this->shiftId, fn($q) => $q->where("shift_id", $this->shiftId))
            ->when($this->dateFrom, fn($q) => $q->whereDate("created_at", ">=", $this->dateFrom))
            ->when($this->dateUntil, fn($q) => $q->whereDate("created_at", "<=", $this->dateUntil))
            ->groupBy("user_id", "shift_id", DB::raw("DATE(created_at)"))
            ->pluck("min_id");

        return $baseQuery
            ->whereIn("id", $groupedIds)
            ->orderBy("created_at", "desc")
            ->orderBy("shift_id")
            ->orderBy("user_id");
    }

    public function chunkSize(): int
    {
        return 100; // Process dalam chunk kecil untuk menghemat memory
    }

    public function headings(): array
    {
        return [
            "NO",
            "NAMA OPERATOR",
            "SHIFT",
            "MC",
            "P.JG(MTR)",
            "EFF",
            "OVERTIME (JAM)",
            "NO KONSTRUKSI",
            "TANGGAL",
            "KETERANGAN",
        ];
    }

    public function map($report): array
    {
        $this->rowNumber++;

        // Hitung data agregat untuk user/shift/tanggal yang sama (optimized)
        static $cache = [];
        $cacheKey = $report->user_id . "-" . $report->shift_id . "-" . $report->created_at->format("Y-m-d");

        if (!isset($cache[$cacheKey])) {
            // Query single untuk semua data yang dibutuhkan
            $aggregateData = Report::where("user_id", $report->user_id)
                ->where("shift_id", $report->shift_id)
                ->whereDate("created_at", $report->created_at->format("Y-m-d"))
                ->select(
                    DB::raw("COUNT(DISTINCT machine_id) as machine_count"),
                    DB::raw("SUM(stock) as total_stock"),
                    DB::raw("AVG(eff) as avg_eff"),
                    DB::raw("AVG(overtime) as avg_overtime"),
                )
                ->first();

            $cache[$cacheKey] = [
                "machine_count" => $aggregateData->machine_count ?? 1,
                "total_stock" => $aggregateData->total_stock ?? 0,
                "avg_eff" => $aggregateData->avg_eff ?? 0,
                "avg_overtime" => $aggregateData->avg_overtime ?? 0,
            ];
        }

        $data = $cache[$cacheKey];

        return [
            $this->rowNumber,
            $report->user->name ?? "",
            $report->shift->name ?? "",
            $data["machine_count"],
            number_format($data["total_stock"], 2),
            number_format($data["avg_eff"], 1) . "%",
            number_format($data["avg_overtime"], 0),
            $report->construction->name ?? "",
            $report->created_at->format("d/m/Y"),
            "", // Kolom keterangan kosong
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                "font" => [
                    "bold" => true,
                    "size" => 12,
                ],
                "alignment" => [
                    "horizontal" => Alignment::HORIZONTAL_CENTER,
                ],
                "fill" => [
                    "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    "color" => ["rgb" => "E2E8F0"],
                ],
            ],

            // Style untuk semua data
            "A:J" => [
                "borders" => [
                    "allBorders" => [
                        "borderStyle" => Border::BORDER_THIN,
                    ],
                ],
                "alignment" => [
                    "vertical" => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Style khusus untuk kolom angka
            "E:E" => ["alignment" => ["horizontal" => Alignment::HORIZONTAL_RIGHT]],
            "F:F" => ["alignment" => ["horizontal" => Alignment::HORIZONTAL_CENTER]],
            "G:G" => ["alignment" => ["horizontal" => Alignment::HORIZONTAL_CENTER]],
        ];
    }
}

class ConstructionSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, WithChunkReading
{
    protected $rowNumber = 0;

    public function query()
    {
        return Construction::orderBy("name");
    }

    public function chunkSize(): int
    {
        return 50; // Chunk lebih kecil untuk constructions
    }

    public function headings(): array
    {
        return ["NO", "NO KONSTRUKSI", "STOCK", "KETERANGAN"];
    }

    public function map($construction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $construction->name,
            number_format($construction->stock ?? 0),
            "", // Keterangan kosong
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                "font" => [
                    "bold" => true,
                    "size" => 12,
                ],
                "alignment" => [
                    "horizontal" => Alignment::HORIZONTAL_CENTER,
                ],
                "fill" => [
                    "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    "color" => ["rgb" => "E2E8F0"],
                ],
            ],

            // Style untuk semua data
            "A:D" => [
                "borders" => [
                    "allBorders" => [
                        "borderStyle" => Border::BORDER_THIN,
                    ],
                ],
                "alignment" => [
                    "vertical" => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Style untuk kolom angka
            "C:C" => ["alignment" => ["horizontal" => Alignment::HORIZONTAL_RIGHT]],
        ];
    }
}
