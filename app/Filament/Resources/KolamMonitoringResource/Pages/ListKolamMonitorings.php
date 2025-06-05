<?php

namespace App\Filament\Resources\KolamMonitoringResource\Pages;

use App\Filament\Resources\KolamMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKolamMonitorings extends ListRecords
{
    protected static string $resource = KolamMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
