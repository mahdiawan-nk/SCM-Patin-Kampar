<?php

namespace App\Filament\Resources\KolamBudidayaResource\Pages;

use App\Filament\Resources\KolamBudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKolamBudidaya extends EditRecord
{
    protected static string $resource = KolamBudidayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
