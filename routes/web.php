<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportPrintController;
use App\Http\Controllers\ReportHistoryController;

Route::get("/", function () {
    return view("auth.login");
});

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");

    // User
    Route::get("/report", [ReportController::class, "index"])->name("report.index");
    Route::post("/report", [ReportController::class, "store"])->name("reports.store");
    Route::get("/report-history", [ReportHistoryController::class, "index"])->name("history.index");

    // Routes untuk soft delete operations
    Route::patch("/reports/{id}/restore", [ReportController::class, "restore"])->name("reports.restore");
    Route::delete("/reports/{id}/force-delete", [ReportController::class, "forceDestroy"])->name(
        "reports.force-delete",
    );
    Route::delete("/reports/{id}", [ReportController::class, "destroy"])->name("reports.destroy");

    // Route untuk print PDF dan export Excel
    Route::middleware(["auth"])->group(function () {
        Route::get("/reports/print-pdf", [ReportPrintController::class, "printPDF"])->name("reports.print-pdf");

        Route::get("/reports/export-excel", [ReportPrintController::class, "exportExcel"])->name(
            "reports.export-excel",
        );
    });
});

require __DIR__ . "/auth.php";
