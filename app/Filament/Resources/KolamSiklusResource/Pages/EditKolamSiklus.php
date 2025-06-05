<?php

namespace App\Filament\Resources\KolamSiklusResource\Pages;

use App\Filament\Resources\KolamSiklusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKolamSiklus extends EditRecord
{
    protected static string $resource = KolamSiklusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
