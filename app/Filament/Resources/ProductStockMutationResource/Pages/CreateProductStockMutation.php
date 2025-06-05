<?php

namespace App\Filament\Resources\ProductStockMutationResource\Pages;

use App\Filament\Resources\ProductStockMutationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductStockMutation extends CreateRecord
{
    protected static string $resource = ProductStockMutationResource::class;
}
