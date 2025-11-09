<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use App\Filament\Resources\ConstructionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConstructions extends ListRecords
{
    protected static string $resource = ConstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
