<?php

namespace App\Filament\Resources\KolamFeedingResource\Pages;

use App\Filament\Resources\KolamFeedingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKolamFeeding extends EditRecord
{
    protected static string $resource = KolamFeedingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
