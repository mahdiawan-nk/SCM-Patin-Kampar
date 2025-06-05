<?php

namespace App\Filament\Resources\ArmadaDriverResource\Pages;

use App\Filament\Resources\ArmadaDriverResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArmadaDriver extends EditRecord
{
    protected static string $resource = ArmadaDriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
