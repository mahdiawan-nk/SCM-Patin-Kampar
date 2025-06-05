<?php

namespace App\Filament\Resources\GeneralStockItemResource\Pages;

use App\Filament\Resources\GeneralStockItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralStockItems extends ListRecords
{
    protected static string $resource = GeneralStockItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
