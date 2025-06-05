<?php

namespace App\Filament\Resources\GeneralStockMutationResource\Pages;

use App\Filament\Resources\GeneralStockMutationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralStockMutations extends ListRecords
{
    protected static string $resource = GeneralStockMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
