<?php

namespace App\Filament\Resources\DeliveryTrackingResource\Pages;

use App\Filament\Resources\DeliveryTrackingResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryTrackings extends ListRecords
{
    protected static string $resource = DeliveryTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Data Contoh Pelacakan')
                ->modalDescription('Buat 10 data pelacakan contoh? Pastikan sudah ada data Pengiriman terlebih dahulu.')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    try {
                        if (!\App\Models\Delivery::exists()) {
                            throw new \Exception('Tidak ada data Pengiriman. Silahkan buat data Pengiriman terlebih dahulu.');
                        }

                        \App\Models\DeliveryTracking::factory()
                            ->count(10)
                            ->create();

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('10 data pelacakan contoh telah ditambahkan')
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
