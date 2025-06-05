<?php

namespace App\Filament\Resources\KolamSiklusResource\Pages;

use App\Filament\Resources\KolamSiklusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKolamSikluses extends ListRecords
{
    protected static string $resource = KolamSiklusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
