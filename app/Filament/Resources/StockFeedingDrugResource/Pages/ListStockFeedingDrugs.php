<?php

namespace App\Filament\Resources\StockFeedingDrugResource\Pages;

use App\Filament\Resources\StockFeedingDrugResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockFeedingDrugs extends ListRecords
{
    protected static string $resource = StockFeedingDrugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
