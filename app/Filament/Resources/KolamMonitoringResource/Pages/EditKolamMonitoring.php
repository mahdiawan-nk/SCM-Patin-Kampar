<?php

namespace App\Filament\Resources\KolamMonitoringResource\Pages;

use App\Filament\Resources\KolamMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKolamMonitoring extends EditRecord
{
    protected static string $resource = KolamMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
