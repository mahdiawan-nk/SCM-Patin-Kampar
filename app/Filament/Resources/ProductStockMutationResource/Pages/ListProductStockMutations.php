<?php

namespace App\Filament\Resources\ProductStockMutationResource\Pages;

use App\Filament\Resources\ProductStockMutationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStockMutations extends ListRecords
{
    protected static string $resource = ProductStockMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
