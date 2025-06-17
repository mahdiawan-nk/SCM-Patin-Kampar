<?php

namespace App\Filament\Resources\PermissionsResource\Pages;

use App\Filament\Resources\PermissionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\Permissions;
use Spatie\Permission\Contracts\Permission;

class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Permission')
            ->slideOver()
            ->modalWidth('md')
            // ->form($this->getFormSchema())
            ->action(function (array $data) {
                Permissions::insert($data['permissions']);
                // $this->call('create', [$data]);
            }),
        ];
    }
}
