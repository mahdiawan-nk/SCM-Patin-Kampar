<?php

namespace App\Filament\Resources\DistribusiScheduleResource\Pages;

use App\Filament\Resources\DistribusiScheduleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDistribusiSchedules extends ListRecords
{
    protected static string $resource = DistribusiScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Data Contoh Jadwal')
                ->modalDescription('Buat 5 data jadwal contoh? Pastikan sudah ada data Pengiriman terlebih dahulu.')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    try {
                        if (!\App\Models\Delivery::exists()) {
                            throw new \Exception('Tidak ada data Pengiriman. Silahkan buat data Pengiriman terlebih dahulu.');
                        }

                        \App\Models\DistribusiSchedule::factory()
                            ->count(5)
                            ->create();

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data jadwal contoh telah ditambahkan')
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
