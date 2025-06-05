<?php

namespace App\Filament\Resources\KolamFeedingResource\Pages;

use App\Filament\Resources\KolamFeedingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKolamFeedings extends ListRecords
{
    protected static string $resource = KolamFeedingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
