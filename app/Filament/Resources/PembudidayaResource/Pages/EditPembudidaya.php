<?php

namespace App\Filament\Resources\PembudidayaResource\Pages;

use App\Filament\Resources\PembudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembudidaya extends EditRecord
{
    protected static string $resource = PembudidayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
