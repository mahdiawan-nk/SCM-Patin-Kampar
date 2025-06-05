<?php

namespace App\Filament\Resources\ProductStockItemResource\Pages;

use App\Filament\Resources\ProductStockItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStockItems extends ListRecords
{
    protected static string $resource = ProductStockItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
