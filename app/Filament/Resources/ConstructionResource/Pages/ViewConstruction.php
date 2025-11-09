<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use App\Filament\Resources\ConstructionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewConstruction extends ViewRecord
{
    protected static string $resource = ConstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
