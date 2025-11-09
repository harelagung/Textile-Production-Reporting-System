<?php

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

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    // Ubah nama di sidebar/navigation
    protected static ?string $navigationLabel = "Laporan Counter Op. Tenun";

    // Ubah nama singular (untuk form/detail page)
    protected static ?string $modelLabel = "laporan tenun";

    // Ubah nama plural (untuk list page)
    protected static ?string $pluralModelLabel = "laporan tenun";

    // Icon di sidebar (opsional)
    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    // Grouping di sidebar (opsional)
    protected static ?string $navigationGroup = "P P C";

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

        // Query menggunakan Spatie Permission structure
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

        // Super Admin bisa akses semua
        if ($userAccess->role_name === "Super Admin") {
            return true;
        }

        // Admin yang departemennya PPC
        return $userAccess->role_name === "Admin" && $userAccess->department_name === "Production Planning Control";
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
                Tables\Columns\TextColumn::make("user.name")->numeric()->sortable()->searchable(),
                Tables\Columns\TextColumn::make("shift.name")->numeric()->sortable()->searchable(),
                Tables\Columns\TextColumn::make("machine.kd_mach")->numeric()->sortable()->searchable(),
                Tables\Columns\TextColumn::make("construction.name")->numeric()->sortable()->searchable(),
                Tables\Columns\TextColumn::make("stock")->numeric()->sortable(),
                Tables\Columns\TextColumn::make("eff")->label("EFF")->numeric(),
                Tables\Columns\TextColumn::make("overtime")->label("OT (Jam)")->numeric()->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->label("Tanggal")
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("deleted_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                // === SHIFT FILTER SECTION ===
                Action::make("shift_pagi")
                    ->label("Shift 1")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeShiftFilter === 1 ? "primary" : "gray")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeShiftFilter === 1
                                    ? "border-primary-500 bg-primary-50 dark:bg-primary-900/20 ring-2 ring-primary-200"
                                    : "border-gray-300 hover:border-primary-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 1;
                        $livewire->activeShiftFilter = 1;
                    }),

                Action::make("shift_siang")
                    ->label("Shift 2")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeShiftFilter === 2 ? "success" : "gray")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeShiftFilter === 2
                                    ? "border-success-500 bg-success-50 dark:bg-success-900/20 ring-2 ring-success-200"
                                    : "border-gray-300 hover:border-success-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 2;
                        $livewire->activeShiftFilter = 2;
                    }),

                Action::make("shift_malam")
                    ->label("Shift 3")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeShiftFilter === 3 ? "warning" : "gray")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeShiftFilter === 3
                                    ? "border-warning-500 bg-warning-50 dark:bg-warning-900/20 ring-2 ring-warning-200"
                                    : "border-gray-300 hover:border-warning-400"),
                        ],
                    )
                    ->action(function ($livewire) {
                        $livewire->tableFilters["shift_id"]["value"] = 3;
                        $livewire->activeShiftFilter = 3;
                    }),

                Action::make("semua_shift")
                    ->label("Semua Shift")
                    ->button()
                    ->color(fn($livewire) => $livewire->activeShiftFilter === null ? "info" : "gray")
                    ->extraAttributes(
                        fn($livewire) => [
                            "class" =>
                                "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                                ($livewire->activeShiftFilter === null
                                    ? "border-info-500 bg-info-50 dark:bg-info-900/20 ring-2 ring-info-200"
                                    : "border-gray-300 hover:border-info-400"),
                        ],
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

                // Action::make("kemarin")
                //     ->label("Kemarin")
                //     ->button()
                //     ->color(fn($livewire) => $livewire->activeDateFilter === "yesterday" ? "secondary" : "gray")
                //     ->icon("heroicon-o-calendar-days")
                //     ->extraAttributes(
                //         fn($livewire) => [
                //             "class" =>
                //                 "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                //                 ($livewire->activeDateFilter === "yesterday"
                //                     ? "border-gray-500 bg-gray-50 dark:bg-gray-900/20 ring-2 ring-gray-200"
                //                     : "border-gray-300 hover:border-gray-400"),
                //         ],
                //     )
                //     ->action(function ($livewire) {
                //         $livewire->tableFilters["created_at"] = [
                //             "from" => now()->subDay()->format("Y-m-d"),
                //             "until" => now()->subDay()->format("Y-m-d"),
                //         ];
                //         $livewire->activeDateFilter = "yesterday";
                //     }),

                // Action::make("minggu_ini")
                //     ->label("Minggu Ini")
                //     ->button()
                //     ->color(fn($livewire) => $livewire->activeDateFilter === "week" ? "success" : "gray")
                //     ->icon("heroicon-o-calendar")
                //     ->extraAttributes(
                //         fn($livewire) => [
                //             "class" =>
                //                 "transition-all duration-200 hover:scale-105 hover:shadow-md border-2 " .
                //                 ($livewire->activeDateFilter === "week"
                //                     ? "border-success-500 bg-success-50 dark:bg-success-900/20 ring-2 ring-success-200"
                //                     : "border-gray-300 hover:border-success-400"),
                //         ],
                //     )
                //     ->action(function ($livewire) {
                //         $livewire->tableFilters["created_at"] = [
                //             "from" => now()->startOfWeek()->format("Y-m-d"),
                //             "until" => now()->endOfWeek()->format("Y-m-d"),
                //         ];
                //         $livewire->activeDateFilter = "week";
                //     }),

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
                                ->duration(5000)
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
                            ->duration(30000)
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

                // === DIVIDER ===
                // Action::make("divider_2")
                //     ->label("")
                //     ->disabled()
                //     ->extraAttributes([
                //         "class" => "w-px h-8 bg-gray-300 dark:bg-gray-600 mx-2",
                //     ]),

                // === RESET SECTION ===
                // Action::make("reset_default")
                //     ->label("Reset")
                //     ->button()
                //     ->color("danger")
                //     ->icon("heroicon-o-arrow-path")
                //     ->extraAttributes([
                //         "class" =>
                //             "transition-all duration-200 hover:scale-105 hover:shadow-lg border-2 border-red-300 hover:border-red-500",
                //     ])
                //     ->action(function ($livewire) {
                //         $livewire->tableFilters["shift_id"]["value"] = 1;
                //         $livewire->tableFilters["created_at"] = [
                //             "from" => now()->format("Y-m-d"),
                //             "until" => now()->format("Y-m-d"),
                //         ];
                //         $livewire->activeShiftFilter = 1;
                //         $livewire->activeDateFilter = "today";
                //     }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
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
}
