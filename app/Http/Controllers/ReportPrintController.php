<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Construction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportPrintController extends Controller
{
    public function printPDF(Request $request)
    {
        // Ambil parameter filter
        $shiftId = $request->get("shift_id");
        $dateFrom = $request->get("date_from");
        $dateUntil = $request->get("date_until");
        $allShifts = $request->get("all_shifts", false); // Parameter baru

        // Query untuk data laporan dengan filter - sesuai dengan grouping di Resource
        $query = Report::with(["user", "shift", "machine", "construction"]);

        // Jika all_shifts = true, abaikan shift_id filter dan ambil semua shift
        if (!$allShifts && $shiftId) {
            $query->where("shift_id", $shiftId);
        }

        if ($dateFrom) {
            $query->whereDate("created_at", ">=", $dateFrom);
        }

        if ($dateUntil) {
            $query->whereDate("created_at", "<=", $dateUntil);
        }

        // Gunakan grouping yang sama dengan Resource untuk konsistensi
        $groupedIds = DB::table("reports")
            ->select(DB::raw("MIN(id) as min_id"))
            ->whereNull("deleted_at")
            ->when(!$allShifts && $shiftId, fn($q) => $q->where("shift_id", $shiftId))
            ->when($dateFrom, fn($q) => $q->whereDate("created_at", ">=", $dateFrom))
            ->when($dateUntil, fn($q) => $q->whereDate("created_at", "<=", $dateUntil))
            ->groupBy("user_id", "shift_id", DB::raw("DATE(created_at)"))
            ->pluck("min_id");

        $reports = $query
            ->whereIn("id", $groupedIds)
            ->orderBy("created_at", "desc")
            ->orderBy("shift_id")
            ->orderBy("user_id")
            ->get();

        // Ambil semua data untuk perhitungan agregat
        $allReportsQuery = Report::with(["user", "shift", "machine", "construction"]);

        if (!$allShifts && $shiftId) {
            $allReportsQuery->where("shift_id", $shiftId);
        }

        if ($dateFrom) {
            $allReportsQuery->whereDate("created_at", ">=", $dateFrom);
        }

        if ($dateUntil) {
            $allReportsQuery->whereDate("created_at", "<=", $dateUntil);
        }

        $allReports = $allReportsQuery->get();

        // Data untuk tabel keterangan konstruksi
        $constructions = Construction::orderBy("name")->get();

        // Generate judul berdasarkan filter
        $title = $this->generateTitle($allShifts ? null : $shiftId, $dateFrom, $dateUntil, $allShifts);

        // Data untuk PDF
        $data = [
            "reports" => $reports,
            "allReports" => $allReports, // Tambahkan ini untuk perhitungan agregat
            "constructions" => $constructions,
            "title" => $title,
            "date" => now()->format("d F Y"),
            "shift_id" => $allShifts ? null : $shiftId,
            "date_from" => $dateFrom,
            "date_until" => $dateUntil,
            "all_shifts" => $allShifts,
        ];

        // Generate PDF
        $pdf = Pdf::loadView("reports.print-pdf", $data)
            ->setPaper("a4", "portrait")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
                "defaultFont" => "sans-serif",
            ]);

        $filename = "Laporan_Counter_Op_Tenun_" . now()->format("d_m_Y_H_i_s") . ".pdf";

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        // Set memory limit dan max execution time untuk mengatasi memory exhausted
        ini_set("memory_limit", "1G"); // Increase to 1GB
        ini_set("max_execution_time", 300); // 5 minutes

        // Ambil parameter filter
        $shiftId = $request->get("shift_id");
        $dateFrom = $request->get("date_from");
        $dateUntil = $request->get("date_until");

        $filename = "Laporan_Counter_Op_Tenun_" . now()->format("d_m_Y_H_i_s") . ".xlsx";

        try {
            return Excel::download(new ReportExport($shiftId, $dateFrom, $dateUntil), $filename);
        } catch (\Exception $e) {
            Log::error("Excel export error: " . $e->getMessage());

            // Return error response
            return response()->json(
                [
                    "error" =>
                        "Terjadi kesalahan saat mengekspor data. Data terlalu besar atau terjadi masalah server.",
                    "message" => $e->getMessage(),
                ],
                500,
            );
        }
    }

    private function generateTitle($shiftId, $dateFrom, $dateUntil, $allShifts = false)
    {
        $title = "HASIL HARIAN COUNTER OPERATOR TENUN";

        // Tambahkan info shift
        if ($allShifts) {
            $title .= " - SEMUA SHIFT";
        } elseif ($shiftId) {
            $shiftName = "";
            switch ($shiftId) {
                case 1:
                    $shiftName = "SHIFT 1";
                    break;
                case 2:
                    $shiftName = "SHIFT 2";
                    break;
                case 3:
                    $shiftName = "SHIFT 3";
                    break;
                default:
                    $shiftName = "SHIFT " . $shiftId;
            }
            $title .= " - " . $shiftName;
        }

        // Tambahkan tanggal
        if ($dateFrom && $dateUntil) {
            if ($dateFrom === $dateUntil) {
                $title .= " - " . \Carbon\Carbon::parse($dateFrom)->format("d F Y");
            } else {
                $title .=
                    " - " .
                    \Carbon\Carbon::parse($dateFrom)->format("d F Y") .
                    " s/d " .
                    \Carbon\Carbon::parse($dateUntil)->format("d F Y");
            }
        } elseif ($dateFrom) {
            $title .= " - Mulai " . \Carbon\Carbon::parse($dateFrom)->format("d F Y");
        } elseif ($dateUntil) {
            $title .= " - Sampai " . \Carbon\Carbon::parse($dateUntil)->format("d F Y");
        } else {
            $title .= " - " . now()->format("F Y");
        }

        return $title;
    }
}
