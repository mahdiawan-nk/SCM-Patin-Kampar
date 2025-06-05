<?php

namespace App\Filament\Resources\KolamTreatmentResource\Pages;

use App\Filament\Resources\KolamTreatmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKolamTreatment extends EditRecord
{
    protected static string $resource = KolamTreatmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
