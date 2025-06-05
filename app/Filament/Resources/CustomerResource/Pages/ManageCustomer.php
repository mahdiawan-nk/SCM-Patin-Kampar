<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomer extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['username'] = $data['user']['username'];
        // $data['email'] = $data['user']['email'];
        dd($data);

        return $data;
    }
}
