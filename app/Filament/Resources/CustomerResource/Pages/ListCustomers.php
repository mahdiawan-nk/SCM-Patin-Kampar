<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Customer;
use Filament\Notifications\Notification;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function (array $data) {

                    if ($data['create_account']) {
                        $user = User::create([
                            'name' => $data['username'],
                            'email' => $data['email'],
                            'password' => bcrypt($data['password']),
                        ]);

                        $user->assignRole('Customer');
                        $customerData = array_merge($data, ['user_id' => $user->id]);
                        unset($customerData['username'], $customerData['password']);
                    }

                    $customer = Customer::create($data['create_account'] ? $customerData : $data);
                    Notification::make()
                        ->title('Saved successfully')
                        ->success()
                        ->send();

                    return $customer;
                })
        ];
    }
}
