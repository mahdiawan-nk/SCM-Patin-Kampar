<?php

namespace App\Filament\Resources\StockFeedingDrugResource\Pages;

use App\Filament\Resources\StockFeedingDrugResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockFeedingDrug extends EditRecord
{
    protected static string $resource = StockFeedingDrugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
