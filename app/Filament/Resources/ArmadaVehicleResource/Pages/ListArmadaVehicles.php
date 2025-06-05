<?php

namespace App\Filament\Resources\ArmadaVehicleResource\Pages;

use App\Filament\Resources\ArmadaVehicleResource;
use App\Models\ArmadaVehicle;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListArmadaVehicles extends ListRecords
{
    protected static string $resource = ArmadaVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Data Contoh Kendaraan')
                ->modalDescription('Apakah Anda yakin ingin membuat 5 data kendaraan contoh?')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    try {
                        // Generate 5 fake vehicles
                        $vehicles = ArmadaVehicle::factory()
                            ->count(5)
                            ->make()
                            ->each(function ($vehicle) {
                                // Ensure unique plate numbers
                                do {
                                    $plate = strtoupper(fake()->bothify('??####??'));
                                } while (ArmadaVehicle::where('plate_number', $plate)->exists());

                                $vehicle->plate_number = $plate;
                                $vehicle->save();
                            });

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data kendaraan contoh telah ditambahkan')
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
