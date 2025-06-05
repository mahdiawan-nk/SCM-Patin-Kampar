<?php

namespace App\Filament\Resources\GeneralStockItemResource\Pages;

use App\Filament\Resources\GeneralStockItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralStockItem extends EditRecord
{
    protected static string $resource = GeneralStockItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
