<?php

namespace App\Filament\Resources\KolamGrowthResource\Pages;

use App\Filament\Resources\KolamGrowthResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKolamGrowths extends ListRecords
{
    protected static string $resource = KolamGrowthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
