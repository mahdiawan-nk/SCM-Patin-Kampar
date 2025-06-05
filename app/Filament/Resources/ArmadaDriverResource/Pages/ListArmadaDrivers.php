<?php

namespace App\Filament\Resources\ArmadaDriverResource\Pages;

use App\Filament\Resources\ArmadaDriverResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\ArmadaDriver;
use Filament\Actions\Action;

class ListArmadaDrivers extends ListRecords
{
    protected static string $resource = ArmadaDriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Data Contoh Driver')
                ->modalDescription('Apakah Anda yakin ingin membuat 5 data driver contoh? Data yang sudah ada tidak akan terpengaruh.')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    try {
                        DB::beginTransaction();

                        // Get the next available license number
                        $lastLicenseNo = ArmadaDriver::orderBy('license_no', 'desc')
                            ->value('license_no');
                        $nextNumber = $lastLicenseNo ? (int) str_replace('SIM', '', $lastLicenseNo) + 1 : 1;

                        for ($i = 0; $i < 5; $i++) {
                            ArmadaDriver::create([
                                'name' => fake()->name(),
                                'phone' => fake()->unique()->phoneNumber(),
                                'license_no' => 'SIM' . str_pad($nextNumber + $i, 8, '0', STR_PAD_LEFT),
                                'is_active' => fake()->boolean(80),
                            ]);
                        }

                        DB::commit();

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data driver contoh telah ditambahkan')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        DB::rollBack();

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
