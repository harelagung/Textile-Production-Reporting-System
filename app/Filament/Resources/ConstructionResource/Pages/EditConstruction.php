<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use App\Filament\Resources\ConstructionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConstruction extends EditRecord
{
    protected static string $resource = ConstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
