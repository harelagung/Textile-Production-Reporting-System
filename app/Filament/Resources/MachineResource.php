<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Machine;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MachineResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MachineResource\RelationManagers;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    // Ubah nama di sidebar/navigation
    protected static ?string $navigationLabel = "Data Mesin Tenun";

    // Ubah nama singular (untuk form/detail page)
    protected static ?string $modelLabel = "data mesin";

    // Ubah nama plural (untuk list page)
    protected static ?string $pluralModelLabel = "data mesin";

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
            Forms\Components\TextInput::make("kd_mach")
                ->required()
                ->live()
                ->afterStateUpdated(fn($state, $set) => $set("kd_mach", ucwords(strtoupper($state))))
                ->maxLength(10),
            Forms\Components\Select::make("construction_id")->relationship("construction", "name"),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("kd_mach")->searchable()->label("Kode Mesin"),
                Tables\Columns\TextColumn::make("construction.name")
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->label("Konstruksi"),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("deleted_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([Tables\Filters\TrashedFilter::make()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
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
            "index" => Pages\ListMachines::route("/"),
            "create" => Pages\CreateMachine::route("/create"),
            "view" => Pages\ViewMachine::route("/{record}"),
            "edit" => Pages\EditMachine::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
