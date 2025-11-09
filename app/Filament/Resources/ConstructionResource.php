<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Construction;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ConstructionResource\Pages;
use App\Filament\Resources\ConstructionResource\RelationManagers;

class ConstructionResource extends Resource
{
    protected static ?string $model = Construction::class;

    // Ubah nama di sidebar/navigation
    protected static ?string $navigationLabel = "Data Konstruksi Kain";

    // Ubah nama singular (untuk form/detail page)
    protected static ?string $modelLabel = "konstruksi";

    // Ubah nama plural (untuk list page)
    protected static ?string $pluralModelLabel = "konstruksi";

    // Icon di sidebar (opsional)
    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    // Grouping di sidebar (opsional)
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
        return $userAccess->role_name === "Admin" && $userAccess->department_name === "Weaving";
    }

    // FORM
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")
                ->label("Nama Kontruksi Kain")
                ->maxLength(50)
                ->required()
                ->live()
                ->afterStateUpdated(fn($state, $set) => $set("name", ucwords(strtoupper($state)))),
            Forms\Components\TextInput::make("stock")->label("Stok / meter")->required()->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label("Nama Konstruksi Kain")
                    ->weight("bold")
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make("stock")->label("Stok / meter")->numeric()->sortable(),

                Tables\Columns\TextColumn::make("machines_count")
                    ->label("Jumlah Mesin Produksi")
                    ->getStateUsing(function (Construction $record): int {
                        return DB::table("machines")
                            ->where("construction_id", $record->id)
                            ->whereNull("deleted_at")
                            ->count();
                    })
                    ->badge()
                    ->color(
                        fn(int $state): string => match (true) {
                            $state > 3 => "success",
                            $state > 1 => "warning",
                            $state === 1 => "primary",
                            default => "gray",
                        },
                    )
                    ->suffix(" mesin")
                    ->sortable(),

                Tables\Columns\TextColumn::make("machine_codes")
                    ->label("Kode Mesin")
                    ->getStateUsing(function (Construction $record): string {
                        $machines = DB::table("machines")
                            ->where("construction_id", $record->id)
                            ->whereNull("deleted_at")
                            ->pluck("kd_mach")
                            ->toArray();

                        if (empty($machines)) {
                            return "Tidak ada mesin";
                        }

                        if (count($machines) <= 3) {
                            return implode(", ", $machines);
                        }

                        return implode(", ", array_slice($machines, 0, 3)) .
                            " (+" .
                            (count($machines) - 3) .
                            " lainnya)";
                    })
                    ->color(fn(string $state): string => $state === "Tidak ada mesin" ? "gray" : "primary")
                    ->wrap()
                    ->tooltip(function (Construction $record): ?string {
                        $machines = DB::table("machines")
                            ->where("construction_id", $record->id)
                            ->whereNull("deleted_at")
                            ->pluck("kd_mach")
                            ->toArray();

                        return count($machines) > 3 ? "Semua mesin: " . implode(", ", $machines) : null;
                    }),

                // Tables\Columns\TextColumn::make("production_status")
                //     ->label("Status Produksi")
                //     ->getStateUsing(function (Construction $record): string {
                //         $machineCount = DB::table("machines")
                //             ->where("construction_id", $record->id)
                //             ->whereNull("deleted_at")
                //             ->count();

                //         return match (true) {
                //             $machineCount > 0 => "Sedang Produksi",
                //             default => "Tidak Aktif",
                //         };
                //     })
                //     ->badge()
                //     ->color(
                //         fn(string $state): string => match ($state) {
                //             "Sedang Produksi" => "success",
                //             "Tidak Aktif" => "gray",
                //             default => "warning",
                //         },
                //     ),

                Tables\Columns\TextColumn::make("created_at")
                    ->label("Dibuat")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make("updated_at")
                    ->label("Diperbarui")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make("deleted_at")
                    ->label("Dihapus")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\Filter::make("sedang_produksi")
                    ->label("Sedang Produksi")
                    ->query(
                        fn(Builder $query): Builder => $query->whereExists(function ($subQuery) {
                            $subQuery
                                ->select(DB::raw(1))
                                ->from("machines")
                                ->whereColumn("machines.construction_id", "constructions.id")
                                ->whereNull("machines.deleted_at");
                        }),
                    )
                    ->toggle(),

                Tables\Filters\Filter::make("tidak_ada_mesin")
                    ->label("Tidak Ada Mesin")
                    ->query(
                        fn(Builder $query): Builder => $query->whereNotExists(function ($subQuery) {
                            $subQuery
                                ->select(DB::raw(1))
                                ->from("machines")
                                ->whereColumn("machines.construction_id", "constructions.id")
                                ->whereNull("machines.deleted_at");
                        }),
                    )
                    ->toggle(),

                Tables\Filters\SelectFilter::make("jumlah_mesin")
                    ->label("Jumlah Mesin")
                    ->options([
                        "1" => "1 Mesin",
                        "2" => "2 Mesin",
                        "3" => "3+ Mesin",
                        "0" => "Tidak Ada Mesin",
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data["value"] ?? null;

                        if (!$value) {
                            return $query;
                        }

                        return match ($value) {
                            "0" => $query->whereNotExists(function ($subQuery) {
                                $subQuery
                                    ->select(DB::raw(1))
                                    ->from("machines")
                                    ->whereColumn("machines.construction_id", "constructions.id")
                                    ->whereNull("machines.deleted_at");
                            }),
                            "1" => $query->whereExists(function ($subQuery) {
                                $subQuery
                                    ->select(DB::raw(1))
                                    ->from("machines")
                                    ->whereColumn("machines.construction_id", "constructions.id")
                                    ->whereNull("machines.deleted_at")
                                    ->havingRaw("COUNT(*) = 1");
                            }),
                            "2" => $query->whereExists(function ($subQuery) {
                                $subQuery
                                    ->select(DB::raw(1))
                                    ->from("machines")
                                    ->whereColumn("machines.construction_id", "constructions.id")
                                    ->whereNull("machines.deleted_at")
                                    ->havingRaw("COUNT(*) = 2");
                            }),
                            "3" => $query->whereExists(function ($subQuery) {
                                $subQuery
                                    ->select(DB::raw(1))
                                    ->from("machines")
                                    ->whereColumn("machines.construction_id", "constructions.id")
                                    ->whereNull("machines.deleted_at")
                                    ->havingRaw("COUNT(*) >= 3");
                            }),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Tables\Actions\Action::make("lihat_mesin")
                //     ->label("Lihat Detail Mesin")
                //     ->icon("heroicon-m-cog-6-tooth")
                //     ->color("info")
                //     ->modalHeading(fn(Construction $record): string => "Mesin untuk " . $record->name)
                //     ->modalContent(function (Construction $record): string {
                //         $machines = DB::table("machines")
                //             ->where("construction_id", $record->id)
                //             ->whereNull("deleted_at")
                //             ->get(["kd_mach", "created_at"]);

                //         if ($machines->isEmpty()) {
                //             return '<p class="text-gray-500">Tidak ada mesin yang sedang memproduksi konstruksi ini.</p>';
                //         }

                //         $html = '<div class="space-y-2">';
                //         foreach ($machines as $machine) {
                //             $html .= '<div class="flex justify-between items-center p-2 bg-gray-50 rounded">';
                //             $html .= '<span class="font-medium">' . $machine->kd_mach . "</span>";
                //             $html .=
                //                 '<span class="text-sm text-gray-500">Mulai: ' .
                //                 date("d/m/Y H:i", strtotime($machine->created_at)) .
                //                 "</span>";
                //             $html .= "</div>";
                //         }
                //         $html .= "</div>";

                //         return $html;
                //     })
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel("Tutup"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort("created_at", "desc");
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
            "index" => Pages\ListConstructions::route("/"),
            "create" => Pages\CreateConstruction::route("/create"),
            "view" => Pages\ViewConstruction::route("/{record}"),
            "edit" => Pages\EditConstruction::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
