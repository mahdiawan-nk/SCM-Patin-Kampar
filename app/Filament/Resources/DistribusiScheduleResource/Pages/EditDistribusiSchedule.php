<?php

namespace App\Filament\Resources\DistribusiScheduleResource\Pages;

use App\Filament\Resources\DistribusiScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistribusiSchedule extends EditRecord
{
    protected static string $resource = DistribusiScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
