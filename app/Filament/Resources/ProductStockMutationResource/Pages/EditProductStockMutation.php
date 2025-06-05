<?php

namespace App\Filament\Resources\ProductStockMutationResource\Pages;

use App\Filament\Resources\ProductStockMutationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductStockMutation extends EditRecord
{
    protected static string $resource = ProductStockMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
