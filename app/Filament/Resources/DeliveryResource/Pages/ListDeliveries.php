<?php

namespace App\Filament\Resources\DeliveryResource\Pages;

use App\Filament\Resources\DeliveryResource;
use App\Models\Delivery;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDeliveries extends ListRecords
{
    protected static string $resource = DeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Data Contoh Pengiriman')
                ->modalDescription('Buat 5 data pengiriman contoh? Pastikan sudah ada data Order, Driver, dan Armada terlebih dahulu.')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    try {
                        // Check if there are existing orders, drivers, and vehicles
                        if (!\App\Models\Order::exists() || !\App\Models\ArmadaDriver::exists() || !\App\Models\ArmadaVehicle::exists()) {
                            Notification::make()
                                ->title('Data contoh gagal dibuat')
                                ->body('Pastikan sudah ada data Order, Driver, dan Armada terlebih dahulu.')
                                ->danger()
                                ->send();
                            return;
                        }

                        Delivery::factory()
                            ->count(5)
                            ->create();

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data pengiriman contoh telah ditambahkan')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal membuat data contoh')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })

        ];
    }
}
