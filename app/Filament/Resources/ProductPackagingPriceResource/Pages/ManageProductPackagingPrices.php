<?php

namespace App\Filament\Resources\ProductPackagingPriceResource\Pages;

use App\Filament\Resources\ProductPackagingPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProductPackagingPrices extends ManageRecords
{
    protected static string $resource = ProductPackagingPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
