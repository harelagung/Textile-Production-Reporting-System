<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Report;
use App\Models\Machine;
use App\Models\Construction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index()
    {
        $format = date("Y-m-d");
        $hariini = date("d") * 1;
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // Get today's reports with relations
        $reports = Report::with(["shift", "machine", "construction", "user"])
            ->whereDate("created_at", today())
            ->orderBy("created_at", "desc")
            ->get();

        // Get data untuk select options
        $shifts = DB::table("shifts")->select("id", "name")->orderBy("name")->get();

        $machines = DB::table("machines")->select("id", "kd_mach")->orderBy("kd_mach")->get();

        $constructions = DB::table("constructions")->select("id", "name")->orderBy("name")->get();

        // Query untuk mendapatkan konstruksi terakhir per mesin dari reports
        $recentConstructions = DB::table("reports as r")
            ->join("machines as m", "r.machine_id", "=", "m.id")
            ->join("constructions as c", "r.construction_id", "=", "c.id")
            ->select("m.kd_mach as machine_kd_mach", "c.id as construction_id", "c.name as construction_name")
            ->whereNull("r.deleted_at")
            ->orderBy("r.created_at", "desc")
            ->get()
            ->groupBy("machine_kd_mach")
            ->map(function ($items) {
                return $items->first();
            });

        // Query untuk semua mesin dengan konstruksi pertama sebagai fallback
        $allMachinesWithDefaultConstruction = DB::table("machines as m")
            ->crossJoin("constructions as c")
            ->select("m.kd_mach as machine_kd_mach", "c.id as construction_id", "c.name as construction_name")
            ->whereIn("c.id", function ($query) {
                $query->select(DB::raw("MIN(id)"))->from("constructions");
            })
            ->get()
            ->keyBy("machine_kd_mach");

        // Merge: prioritas data dari reports, fallback ke konstruksi default
        $machineConstructions = $allMachinesWithDefaultConstruction->merge($recentConstructions);

        // Get laporan hari ini untuk user yang sedang login
        $reports = DB::table("reports as r")
            ->join("shifts as s", "r.shift_id", "=", "s.id")
            ->join("machines as m", "r.machine_id", "=", "m.id")
            ->join("constructions as c", "r.construction_id", "=", "c.id")
            ->select(
                "r.id",
                "r.stock",
                "r.created_at",
                "s.name as shift_name",
                "m.kd_mach as machine_kd_mach",
                "m.kd_mach as machine_name",
                "c.name as construction_name",
            )
            ->where("r.user_id", auth()->id()) // Filter berdasarkan user login
            ->whereDate("r.created_at", today())
            ->whereNull("r.deleted_at")
            ->orderBy("r.created_at", "desc")
            ->get();

        return view(
            "user.report",
            compact(
                "hariini",
                "bulanini",
                "tahunini",
                "namabulan",
                "format",
                "shifts",
                "machines",
                "reports",
                "constructions",
                "machineConstructions",
                "allMachinesWithDefaultConstruction",
                "recentConstructions",
            ),
        );
    }

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate(
    //         [
    //             "shift_id" => "required|exists:shifts,id",
    //             "machine_id" => "required|exists:machines,id",
    //             "construction_id" => "required|exists:constructions,id",
    //             "stock" => "required|numeric|min:0|max:999999.99",
    //         ],
    //         [
    //             "shift_id.required" => "Shift wajib dipilih",
    //             "shift_id.exists" => "Shift yang dipilih tidak valid",
    //             "machine_id.required" => "Mesin tidak ditemukan",
    //             "machine_id.exists" => "Mesin yang dipilih tidak valid",
    //             "construction_id.required" => "Konstruksi wajib dipilih",
    //             "construction_id.exists" => "Konstruksi yang dipilih tidak valid",
    //             "stock.required" => "Hasil produksi wajib diisi",
    //             "stock.numeric" => "Hasil produksi harus berupa angka",
    //             "stock.min" => "Hasil produksi tidak boleh kurang dari 0",
    //             "stock.max" => "Hasil produksi terlalu besar",
    //         ],
    //     );

    //     try {
    //         // Mulai database transaction untuk memastikan consistency
    //         DB::beginTransaction();

    //         // Ambil data mesin yang akan diupdate
    //         $machine = DB::table("machines")->where("id", $request->machine_id)->first();

    //         $shifts = DB::table("shifts")->where("id", $request->shift_id)->first();

    //         $shiftDuration = $shifts->duration_hours;

    //         $overtime = $request->overtime;

    //         $workhours = $shiftDuration + $overtime;

    //         $constructions = DB::table("constructions")->where("id", $request->construction_id)->first();

    //         $consType = $constructions->id;

    //         if (!$machine) {
    //             return back()
    //                 ->withInput()
    //                 ->withErrors(["machine_kd_mach" => "Mesin tidak ditemukan"]);
    //         }
    //         // Cek apakah kombinasi shift, mesin, konstruksi sudah ada hari ini
    //         $existingReport = DB::table("reports")
    //             ->where("shift_id", $request->shift_id)
    //             ->where("machine_id", $request->machine_id)
    //             ->where("construction_id", $request->construction_id)
    //             ->whereDate("created_at", today())
    //             ->whereNull("deleted_at")
    //             ->first();

    //         if ($existingReport) {
    //             return back()
    //                 ->withInput()
    //                 ->withErrors(["error" => "Kombinasi shift, mesin, dan konstruksi ini sudah dilaporkan hari ini"]);
    //         }

    //         // Simpan data report ke database
    //         DB::table("reports")->insert([
    //             "user_id" => Auth::id(),
    //             "shift_id" => $request->shift_id,
    //             "machine_id" => $request->machine_id,
    //             "construction_id" => $request->construction_id,
    //             "stock" => $request->stock,
    //             "overtime" => $overtime,
    //             "eff" => $workhours,
    //             "created_at" => now(),
    //             "updated_at" => now(),
    //         ]);

    //         // Update tabel machines dengan construction_id terakhir yang digunakan
    //         DB::table("machines")
    //             ->where("id", $request->machine_id)
    //             ->update([
    //                 "construction_id" => $request->construction_id,
    //                 "updated_at" => now(),
    //             ]);

    //         DB::commit();

    //         return redirect()->route("report.index")->with("success", "Laporan produksi berhasil disimpan!");
    //     } catch (\Exception $e) {
    //         // Rollback transaction jika ada error
    //         DB::rollBack();

    //         return back()
    //             ->withInput()
    //             ->withErrors(["error" => "Terjadi kesalahan saat menyimpan data: " . $e->getMessage()]);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            // Lakukan validasi input. Jika gagal, exception akan otomatis dilempar.
            $request->validate(
                [
                    "shift_id" => "required|exists:shifts,id",
                    "machine_id" => "required|exists:machines,id",
                    "construction_id" => "required|exists:constructions,id",
                    "stock" => "required|numeric|min:0|max:999999.99",
                ],
                [
                    "shift_id.required" => "Shift wajib dipilih",
                    "shift_id.exists" => "Shift yang dipilih tidak valid",
                    "machine_id.required" => "Mesin tidak ditemukan",
                    "machine_id.exists" => "Mesin yang dipilih tidak valid",
                    "construction_id.required" => "Konstruksi wajib dipilih",
                    "construction_id.exists" => "Konstruksi yang dipilih tidak valid",
                    "stock.required" => "Hasil produksi wajib diisi",
                    "stock.numeric" => "Hasil produksi harus berupa angka",
                    "stock.min" => "Hasil produksi tidak boleh kurang dari 0",
                    "stock.max" => "Hasil produksi terlalu besar",
                ],
            );

            // Mulai database transaction
            DB::beginTransaction();

            // Cek apakah kombinasi shift, mesin, konstruksi sudah ada hari ini
            $existingReport = DB::table("reports")
                ->where("shift_id", $request->shift_id)
                ->where("machine_id", $request->machine_id)
                ->where("construction_id", $request->construction_id)
                ->whereDate("created_at", today())
                ->whereNull("deleted_at")
                ->first();

            if ($existingReport) {
                // Return JSON response for AJAX error handling
                return response()->json(
                    ["errors" => ["error" => "Kombinasi shift, mesin, dan konstruksi ini sudah dilaporkan hari ini"]],
                    422,
                );
            }

            // Ambil data mesin yang akan diupdate
            $machine = DB::table("machines")->where("id", $request->machine_id)->first();

            $shifts = DB::table("shifts")->where("id", $request->shift_id)->first();

            $shiftDuration = $shifts->duration_hours;

            $overtime = $request->overtime;

            $effectivity = $shiftDuration + $overtime;

            $constructions = DB::table("constructions")->where("id", $request->construction_id)->first();

            $lateStock = $request->stock;

            $lastStock = $constructions->stock;

            $sumStock = $lateStock + $lastStock;

            if (!$machine) {
                return back()
                    ->withInput()
                    ->withErrors(["machine_kd_mach" => "Mesin tidak ditemukan"]);
            }

            // Simpan data report ke database
            DB::table("reports")->insert([
                "user_id" => Auth::id(),
                "shift_id" => $request->shift_id,
                "machine_id" => $request->machine_id,
                "construction_id" => $request->construction_id,
                "stock" => $request->stock,
                "overtime" => $request->overtime,
                "eff" => $effectivity,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            // Update tabel machines
            DB::table("machines")
                ->where("id", $request->machine_id)
                ->update([
                    "construction_id" => $request->construction_id,
                    "updated_at" => now(),
                ]);

            DB::table("constructions")
                ->where("id", $request->construction_id)
                ->update([
                    "stock" => $sumStock,
                    "updated_at" => now(),
                ]);

            DB::commit();

            // Mengembalikan respons JSON untuk AJAX
            return response()->json(
                [
                    "message" => "Laporan berhasil disimpan!",
                    "report" => [
                        "shift_name" => $shifts->name,
                        "machine_kd_mach" => $machine->kd_mach,
                        "construction_name" => $constructions->name,
                        "stock" => number_format($request->stock),
                        "created_at" => now()->format("H:i:s"),
                    ],
                ],
                200,
            );
        } catch (ValidationException $e) {
            // Tangkap exception validasi dan kembalikan error JSON
            DB::rollBack();
            return response()->json(
                [
                    "errors" => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            // Tangkap exception umum dan kembalikan error JSON
            DB::rollBack();
            return response()->json(
                [
                    "errors" => ["error" => "Terjadi kesalahan saat menyimpan data: " . $e->getMessage()],
                ],
                500,
            );
        }
    }

    // Di ReportController.php
    public function destroy($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete(); // Soft delete

            return back()->with("success", "Data berhasil dihapus dan bisa dipulihkan nanti!");
        } catch (\Exception $e) {
            return back()->with("error", "Gagal menghapus data: " . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $report = Report::withTrashed()->findOrFail($id);
            $report->restore();

            return back()->with("success", "Data berhasil dipulihkan!");
        } catch (\Exception $e) {
            return back()->with("error", "Gagal memulihkan data: " . $e->getMessage());
        }
    }

    public function forceDestroy($id)
    {
        try {
            $report = Report::withTrashed()->findOrFail($id);
            $report->forceDelete();

            return back()->with("success", "Data berhasil dihapus permanen!");
        } catch (\Exception $e) {
            return back()->with("error", "Gagal menghapus data: " . $e->getMessage());
        }
    }
}
