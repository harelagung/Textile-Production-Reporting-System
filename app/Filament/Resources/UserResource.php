<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Ubah nama di sidebar/navigation
    protected static ?string $navigationLabel = "Data Karyawan";

    // Ubah nama singular (untuk form/detail page)
    protected static ?string $modelLabel = "karyawan";

    // Ubah nama plural (untuk list page)
    protected static ?string $pluralModelLabel = "karyawan";

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
            Forms\Components\Grid::make(1) // 1 kolom = semua fullwidth
                ->schema([
                    Forms\Components\TextInput::make("nip")->required()->maxLength(8)->label("NIP"),
                    Forms\Components\TextInput::make("name")
                        ->label("Nama Lengkap")
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(fn($state, $set) => $set("name", ucwords(strtolower($state)))),
                    Forms\Components\TextInput::make("email")->email()->required()->maxLength(255),
                    Forms\Components\Select::make("position")
                        ->label("Jabatan")
                        ->required()
                        ->relationship("position", "name")
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make("department")
                        ->label("Departemen")
                        ->required()
                        ->relationship("department", "name")
                        ->searchable()
                        ->preload(),
                    Select::make("roles")->required()->relationship("roles", "name")->preload(),
                    // Forms\Components\TextInput::make("password")
                    //     ->visibleOn("create")
                    //     ->password()
                    //     ->default("msep2025")
                    //     ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    //     ->required()
                    //     ->maxLength(255),
                    Forms\Components\DateTimePicker::make("email_verified_at")->visibleOn("view"),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("nip")->label("NIP")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("name")->label("Nama Lengkap")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("department.name")->label("Departemen")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("position.name")->label("Jabatan")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("roles.name")->searchable()->sortable(),
                // Tables\Columns\TextColumn::make("email")->searchable(),
                // Tables\Columns\TextColumn::make("email_verified_at")->dateTime()->sortable(),
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
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "view" => Pages\ViewUser::route("/{record}"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
