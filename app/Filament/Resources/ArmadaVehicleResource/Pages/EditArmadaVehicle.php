<?php

namespace App\Filament\Resources\ArmadaVehicleResource\Pages;

use App\Filament\Resources\ArmadaVehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArmadaVehicle extends EditRecord
{
    protected static string $resource = ArmadaVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
