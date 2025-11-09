<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Shift;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ShiftResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ShiftResource\RelationManagers;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    // Ubah nama di sidebar/navigation
    protected static ?string $navigationLabel = "Data Shift";

    // Ubah nama singular (untuk form/detail page)
    protected static ?string $modelLabel = "shift";

    // Ubah nama plural (untuk list page)
    protected static ?string $pluralModelLabel = "shift";

    // Icon di sidebar (opsional)
    protected static ?string $navigationIcon = "heroicon-o-users";

    // Grouping di sidebar (opsional)
    protected static ?string $navigationGroup = "Personalia";

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

        // Admin yang departemennya HR
        return $userAccess->role_name === "Admin" && $userAccess->department_name === "Personalia";
    }

    // FORM
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")
                ->label("Nama Shift")
                ->required()
                ->maxLength(20)
                ->live()
                ->afterStateUpdated(fn($state, $set) => $set("name", ucwords(strtolower($state)))),
            Forms\Components\TextInput::make("start_time")->label("Jam Masuk")->required(),
            Forms\Components\TextInput::make("end_time")->label("Jam Pulang")->required(),
            Forms\Components\TextInput::make("duration_hours")->label("Durasi Kerja / Jam")->required()->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->label("Nama Shift")->searchable(),
                Tables\Columns\TextColumn::make("start_time")->date("H:i")->label("Jam Masuk"),
                Tables\Columns\TextColumn::make("end_time")->date("H:i")->label("Jam Pulang"),
                Tables\Columns\TextColumn::make("duration_hours")->label("Durasi Kerja / Jam")->numeric()->sortable(),
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
            "index" => Pages\ListShifts::route("/"),
            "create" => Pages\CreateShift::route("/create"),
            "view" => Pages\ViewShift::route("/{record}"),
            "edit" => Pages\EditShift::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
