<?php

// ReportResource.php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action as FormAction;
use App\Filament\Resources\ReportResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationLabel = "Laporan Counter Op. Tenun";
    protected static ?string $modelLabel = "laporan tenun";
    protected static ?string $pluralModelLabel = "laporan tenun";
    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";
    protected static ?string $navigationGroup = "Weaving";

    // MANUAL PERMISSION
    public static function shouldRegisterNavigation(): bool
    {
        return self::hasHRAccess();
    }

    public static function canViewAny(): bool
    {
        return self::hasHRAccess();
    }

    public static function canCreate(): bool
    {
        return self::hasHRAccess();
    }

    private static function hasHRAccess(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $userAccess = DB::table("users as u")
            ->join("model_has_roles as mhr", function ($join) {
                $join->on("u.id", "=", "mhr.model_id")->where("mhr.model_type", "=", "App\Models\User");
            })
            ->join("roles as r", "mhr.role_id", "=", "r.id")
            ->join("departments as d", "u.department_id", "=", "d.id")
            ->where("u.id", $user->id)
            ->select("r.name as role_name", "d.name as department_name")
            ->first();

        if (!$userAccess) {
            return false;
        }

        if ($userAccess->role_name === "Super Admin") {
            return true;
        }

        return $userAccess->role_name === "Admin" && $userAccess->department_name === "Weaving";
    }

    // FORM
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make("user_id")->relationship("user", "name")->required(),
            Forms\Components\Select::make("shift_id")->relationship("shift", "name")->required(),
            Forms\Components\Select::make("machine_id")->relationship("machine", "kd_mach")->required(),
            Forms\Components\Select::make("construction_id")->relationship("construction", "name")->required(),
            Forms\Components\TextInput::make("stock")->required()->numeric()->default(0),
            Forms\Components\TextInput::make("eff")->required()->numeric()->default(0.0),
            Forms\Components\TextInput::make("overtime")->label("Overtime (Jam)")->required()->numeric()->default(0),
            Forms\Components\Select::make("shift_id")
                ->relationship("shift", "duration_hours")
                ->label("Jam Kerja (Jam)")
                ->required()
                ->default(8)
                ->placeholder("Pilih jam kerja"),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("user.name")
                    ->label("Operator")
                    ->sortable()
                    ->searchable()
                    ->weight("medium")
                    ->icon("heroicon-o-user")
                    ->iconColor("primary"),

                TextColumn::make("shift.name")->label("Shift")->sortable()->badge()->color(
                    fn(string $state): string => match ($state) {
                        "Shift 1" => "info",
                        "Shift 2" => "success",
                        "Shift 3" => "warning",
                        default => "gray",
                    },
                ),

                // Kolom Virtual untuk Machine Count
                TextColumn::make("machine_count")
                    ->label("MC")
                    ->state(function ($record) {
                        static $cache = [];
                        $key = $record->user_id . "-" . $record->shift_id . "-" . $record->created_at->format("Y-m-d");

                        if (!isset($cache[$key])) {
                            $cache[$key] = Report::where("user_id", $record->user_id)
                                ->where("shift_id", $record->shift_id)
                                ->whereDate("created_at", $record->created_at->format("Y-m-d"))
                                ->distinct("machine_id")
                                ->count("machine_id");
                        }

                        return $cache[$key];
                    }),

                // Kolom Virtual untuk Total Stock
                TextColumn::make("total_stock")
                    ->label("Total Stock")
                    ->numeric()
                    ->icon("heroicon-o-archive-box")
                    ->iconColor("success")
                    ->state(function ($record) {
                        static $cache = [];
                        $key =
                            "stock_" .
                            $record->user_id .
                            "-" .
                            $record->shift_id .
                            "-" .
                            $record->created_at->format("Y-m-d");

                        if (!isset($cache[$key])) {
                            $cache[$key] = Report::where("user_id", $record->user_id)
                                ->where("shift_id", $record->shift_id)
                                ->whereDate("created_at", $record->created_at->format("Y-m-d"))
                                ->sum("stock");
                        }

                        return $cache[$key];
                    }),

                // Kolom Virtual untuk Average EFF
                TextColumn::make("eff")
                    ->label("EFF")
                    ->state(function ($record) {
                        static $cache = [];
                        $key =
                            "eff_" .
                            $record->user_id .
                            "-" .
                            $record->shift_id .
                            "-" .
                            $record->created_at->format("Y-m-d");

                        if (!isset($cache[$key])) {
                            $cache[$key] = Report::where("user_id", $record->user_id)
                                ->where("shift_id", $record->shift_id)
                                ->whereDate("created_at", $record->created_at->format("Y-m-d"))
                                ->avg("eff");
                        }

                        return number_format($cache[$key], 1);
                    })
                    ->badge()
                    ->color(
                        fn($state) => floatval($state) >= 80
                            ? "success"
                            : (floatval($state) >= 60
                                ? "warning"
                                : "danger"),
                    ),

                // Kolom Virtual untuk Total OT
                TextColumn::make("total_overtime")
                    ->label("Total OT (Jam)")
                    ->numeric()
                    ->icon("heroicon-o-clock")
                    ->iconColor("warning")
                    ->state(function ($record) {
                        static $cache = [];
                        $key =
                            "ot_" .
                            $record->user_id .
                            "-" .
                            $record->shift_id .
                            "-" .
                            $record->created_at->format("Y-m-d");

                        if (!isset($cache[$key])) {
                            $cache[$key] = Report::where("user_id", $record->user_id)
                                ->where("shift_id", $record->shift_id)
                                ->whereDate("created_at", $record->created_at->format("Y-m-d"))
                                ->avg("overtime");
                        }

                        return $cache[$key];
                    }),

                TextColumn::make("created_at")
                    ->label("Tanggal")
                    ->date("d/m/Y")
                    ->sortable()
                    ->searchable()
                    ->icon("heroicon-o-calendar-days")
                    ->iconColor("info")
                    ->toggleable(isToggledHiddenByDefault: false),
            ])

            // ============ TAMBAHAN UNTUK CUSTOM ROW CLICK ACTION ============
            ->recordAction("view_machines") // Mengarahkan klik row ke action 'view_machines'
            ->recordUrl(null) // Menonaktifkan URL default Filament
            // ============ END CUSTOM ROW CLICK ACTION ============

            ->headerActions([
                // // === PRINT ACTIONS ===
                // Action::make("print_report")
                //     ->label("Cetak Laporan")
                //     ->button()
                //     ->color("success")
                //     ->icon("heroicon-o-printer")
                //     ->extraAttributes([
                //         "class" => "relative",
                //     ])
                //     ->form([
                //         Forms\Components\Select::make("print_type")
                //             ->label("Pilih Format")
                //             ->options([
                //                 "pdf" => "Cetak PDF",
                //                 "excel" => "Ekspor Excel",
                //             ])
                //             ->required()
                //             ->placeholder("Pilih format cetak")
                //             ->native(false),
                //     ])
                //     ->fillForm([
                //         "print_type" => "pdf",
                //     ])
                //     ->action(function (array $data, $livewire) {
                //         // Ambil filter yang sedang aktif
                //         $filters = $livewire->tableFilters;

                //         if ($data["print_type"] === "pdf") {
                //             // Redirect ke route PDF dengan parameter filter
                //             $params = [
                //                 "shift_id" => $filters["shift_id"]["value"] ?? null,
                //                 "date_from" => $filters["created_at"]["from"] ?? null,
                //                 "date_until" => $filters["created_at"]["until"] ?? null,
                //             ];

                //             $url = route("reports.print-pdf", array_filter($params));

                //             // Notifikasi
                //             \Filament\Notifications\Notification::make()
                //                 ->title("Menyiapkan PDF")
                //                 ->body("Laporan PDF sedang disiapkan...")
                //                 ->success()
                //                 ->duration(3000)
                //                 ->send();

                //             // Buka di tab baru
                //             return redirect()->away($url);
                //         } elseif ($data["print_type"] === "excel") {
                //             // Redirect ke route Excel dengan parameter filter
                //             $params = [
                //                 "shift_id" => $filters["shift_id"]["value"] ?? null,
                //                 "date_from" => $filters["created_at"]["from"] ?? null,
                //                 "date_until" => $filters["created_at"]["until"] ?? null,
                //             ];

                //             $url = route("reports.export-excel", array_filter($params));

                //             // Notifikasi
                //             \Filament\Notifications\Notification::make()
                //                 ->title("Mengunduh Excel")
                //                 ->body("File Excel sedang diunduh...")
                //                 ->success()
                //                 ->duration(3000)
                //                 ->send();

                //             return redirect()->away($url);
                //         }
                //     }),

                // // === DIVIDER ===
                // Action::make("divider_print")
                //     ->label("")
                //     ->disabled()
                //     ->extraAttributes([
                //         "class" => "w-px h-8 bg-gray-300 dark:bg-gray-600 mx-2",
                //     ]),

                // === SHIFT FILTER SECTION ===
                Action::make("shift_pagi")
                    ->label("Shift 1")
                    ->button()
                    ->color(
                        fn($livewire) => $livewire->activeShiftFilter == 1 ||
                        ($livewire->tableFilters["shift_id"]["value"] ?? null) == 1
                            ? "primary"
                            : "gray",
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 1;
                        $livewire->activeShiftFilter = 1;
                    }),

                Action::make("shift_siang")
                    ->label("Shift 2")
                    ->button()
                    ->color(
                        fn($livewire) => $livewire->activeShiftFilter == 2 ||
                        ($livewire->tableFilters["shift_id"]["value"] ?? null) == 2
                            ? "primary"
                            : "gray",
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 2;
                        $livewire->activeShiftFilter = 2;
                    }),

                Action::make("shift_malam")
                    ->label("Shift 3")
                    ->button()
                    ->color(
                        fn($livewire) => $livewire->activeShiftFilter == 3 ||
                        ($livewire->tableFilters["shift_id"]["value"] ?? null) == 3
                            ? "primary"
                            : "gray",
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 3;
                        $livewire->activeShiftFilter = 3;
                    }),

                Action::make("semua_shift")
                    ->label("Semua Shift")
                    ->button()
                    ->color(
                        fn($livewire) => empty($livewire->activeShiftFilter) &&
                        empty($livewire->tableFilters["shift_id"]["value"])
                            ? "info"
                            : "gray",
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = null;
                        $livewire->activeShiftFilter = null;
                    }),

                // === DIVIDER ===
                Action::make("divider_1")
                    ->label("")
                    ->disabled()
                    ->extraAttributes([
                        "class" => "w-px h-8 bg-gray-300 dark:bg-gray-600 mx-2",
                    ]),

                // === TIME FILTER SECTION ===
                Action::make("hari_ini")
                    ->label("Hari Ini")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeDateFilter === "today" ? "info" : "gray")
                    ->icon("heroicon-o-calendar-days")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeDateFilter === "today"
                                    ? "border-info-500 bg-info-50 dark:bg-info-900/20 ring-2 ring-info-200"
                                    : "border-gray-300 hover:border-info-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["created_at"] = [
                            "from" => now()->format("Y-m-d"),
                            "until" => now()->format("Y-m-d"),
                        ];
                        $livewire->activeDateFilter = "today";
                    }),

                Action::make("bulan_ini")
                    ->label("Bulan Ini")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeDateFilter === "month" ? "warning" : "gray")
                    ->icon("heroicon-o-calendar")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeDateFilter === "month"
                                    ? "border-warning-500 bg-warning-50 dark:bg-warning-900/20 ring-2 ring-warning-200"
                                    : "border-gray-300 hover:border-warning-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["created_at"] = [
                            "from" => now()->startOfMonth()->format("Y-m-d"),
                            "until" => now()->endOfMonth()->format("Y-m-d"),
                        ];
                        $livewire->activeDateFilter = "month";
                    }),

                Action::make("custom_date")
                    ->label(
                        fn($livewire) => $livewire->activeDateFilter === "custom" ? "✓ Pilih Tanggal" : "Pilih Tanggal",
                    )
                    ->button()
                    ->color(fn($livewire) => $livewire->activeDateFilter === "custom" ? "purple" : "gray")
                    ->icon("heroicon-o-calendar-date-range")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeDateFilter === "custom"
                                    ? "border-purple-500 bg-purple-50 dark:bg-purple-900/20 ring-2 ring-purple-200"
                                    : "border-gray-300 hover:border-purple-400"),
                        ],
                    )
                    ->form([
                        Grid::make(2)->schema([
                            DatePicker::make("dari_tanggal")
                                ->label("Dari Tanggal")
                                ->native(false)
                                ->displayFormat("d/m/Y")
                                ->default(function ($livewire) {
                                    // Ambil dari filter aktif jika ada, atau default ke hari ini
                                    $currentFilter = $livewire->tableFilters["created_at"] ?? [];
                                    return $currentFilter["from"] ?? now()->format("Y-m-d");
                                })
                                ->required()
                                ->live() // Untuk real-time validation
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $sampaiTanggal = $get("sampai_tanggal");
                                    if ($sampaiTanggal && $state && $state > $sampaiTanggal) {
                                        $set("sampai_tanggal", $state);
                                    }
                                }),
                            DatePicker::make("sampai_tanggal")
                                ->label("Sampai Tanggal")
                                ->native(false)
                                ->displayFormat("d/m/Y")
                                ->default(function ($livewire) {
                                    // Ambil dari filter aktif jika ada, atau default ke hari ini
                                    $currentFilter = $livewire->tableFilters["created_at"] ?? [];
                                    return $currentFilter["until"] ?? now()->format("Y-m-d");
                                })
                                ->required()
                                ->live() // Untuk real-time validation
                                ->rules([
                                    function (callable $get) {
                                        return function (string $attribute, $value, callable $fail) use ($get) {
                                            $dariTanggal = $get("dari_tanggal");
                                            if ($dariTanggal && $value && $value < $dariTanggal) {
                                                $fail("Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.");
                                            }
                                        };
                                    },
                                ])
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $dariTanggal = $get("dari_tanggal");
                                    if ($dariTanggal && $state && $state < $dariTanggal) {
                                        // Optional: Auto-correct ke tanggal mulai
                                        $set("sampai_tanggal", $dariTanggal);
                                    }
                                }),
                        ]),
                    ])
                    ->fillForm(function ($livewire) {
                        // Pre-fill form dengan nilai filter yang sedang aktif
                        $currentFilter = $livewire->tableFilters["created_at"] ?? [];
                        return [
                            "dari_tanggal" => $currentFilter["from"] ?? now()->format("Y-m-d"),
                            "sampai_tanggal" => $currentFilter["until"] ?? now()->format("Y-m-d"),
                        ];
                    })
                    ->action(function (array $data, $livewire) {
                        // Validasi tambahan di action
                        if ($data["sampai_tanggal"] < $data["dari_tanggal"]) {
                            \Filament\Notifications\Notification::make()
                                ->title("Error Validasi")
                                ->body("Tanggal selesai tidak boleh lebih kecil dari tanggal mulai!")
                                ->danger()
                                ->duration(3000)
                                ->send();
                            return;
                        }

                        // Terapkan filter
                        $livewire->tableFilters["created_at"] = [
                            "from" => $data["dari_tanggal"],
                            "until" => $data["sampai_tanggal"],
                        ];

                        // Set status filter aktif
                        $livewire->activeDateFilter = "custom";

                        // Notifikasi sukses
                        \Filament\Notifications\Notification::make()
                            ->title("Filter Berhasil Diterapkan")
                            ->body(
                                "Data berhasil difilter dari " .
                                    \Carbon\Carbon::parse($data["dari_tanggal"])->format("d/m/Y") .
                                    " sampai " .
                                    \Carbon\Carbon::parse($data["sampai_tanggal"])->format("d/m/Y"),
                            )
                            ->success()
                            ->duration(3000)
                            ->send();

                        $livewire->dispatch("filter-changed", type: "date", value: "custom");
                    }),

                Action::make("semua_tanggal")
                    ->label("Semua Tanggal")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeDateFilter === "all" ? "info" : "gray")
                    ->icon("heroicon-o-document-text")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeDateFilter === "all"
                                    ? "border-info-500 bg-info-50 dark:bg-info-900/20 ring-2 ring-info-200"
                                    : "border-gray-300 hover:border-info-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["created_at"] = [
                            "from" => null,
                            "until" => null,
                        ];
                        $livewire->activeDateFilter = "all";
                    }),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
                SelectFilter::make("shift_id")->relationship("shift", "name")->label("Shift"),
                Tables\Filters\Filter::make("created_at")
                    ->form([
                        Forms\Components\DatePicker::make("from")->label("Dari Tanggal"),
                        Forms\Components\DatePicker::make("until")->label("Sampai Tanggal"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data["from"], fn($query, $date) => $query->whereDate("created_at", ">=", $date))
                            ->when($data["until"], fn($query, $date) => $query->whereDate("created_at", "<=", $date));
                    }),
            ])
            ->defaultSort("created_at", "desc")
            ->actions([
                Tables\Actions\Action::make("view_machines")
                    ->label("Lihat Detail")
                    ->icon("heroicon-o-eye")
                    ->color("info")
                    ->modalHeading(fn($record) => "Detail Laporan Mesin")
                    ->modalDescription(
                        fn($record) => "Detail semua mesin untuk {$record->user->name} - {$record->shift->name} pada " .
                            $record->created_at->format("d/m/Y"),
                    )
                    ->modalContent(function ($record) {
                        // Ambil semua laporan dengan user, shift, dan tanggal yang sama
                        $reports = Report::with(["machine", "construction"])
                            ->where("user_id", $record->user_id)
                            ->where("shift_id", $record->shift_id)
                            ->whereDate("created_at", $record->created_at->format("Y-m-d"))
                            ->orderBy("machine_id")
                            ->get();

                        return view("filament.custom.machine-reports-detail", [
                            "reports" => $reports,
                        ]);
                    })
                    ->modalWidth("4xl")
                    ->modalFooterActions([])
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            // Grouping dengan pendekatan yang lebih sederhana dan aman
            ->modifyQueryUsing(function (Builder $query) {
                // Ambil satu record per grup (user_id, shift_id, date)
                // Menggunakan MIN(id) untuk menghindari masalah GROUP BY
                $groupedIds = DB::table("reports")
                    ->select(DB::raw("MIN(id) as min_id"))
                    ->whereNull("deleted_at")
                    ->groupBy("user_id", "shift_id", DB::raw("DATE(created_at)"))
                    ->pluck("min_id");

                return $query->whereIn("id", $groupedIds);
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListReports::route("/"),
            "create" => Pages\CreateReport::route("/create"),
            "view" => Pages\ViewReport::route("/{record}"),
            "edit" => Pages\EditReport::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    // Tambahkan method ini di dalam class Resource Anda
    public function deleteRelatedReport($reportId)
    {
        try {
            $report = Report::findOrFail($reportId);
            $reportName = $report->machine->kd_mach ?? "Report ID: $reportId";

            $report->delete();

            // Kirim notifikasi sukses
            \Filament\Notifications\Notification::make()
                ->title("Data Berhasil Dihapus")
                ->body("Laporan mesin {$reportName} telah dihapus.")
                ->success()
                ->duration(3000)
                ->send();

            // Refresh table atau modal
            $this->dispatch("refresh-modal");
        } catch (\Exception $e) {
            // Kirim notifikasi error
            \Filament\Notifications\Notification::make()
                ->title("Gagal Menghapus Data")
                ->body("Terjadi kesalahan saat menghapus data.")
                ->danger()
                ->duration(5000)
                ->send();
        }
    }
}
