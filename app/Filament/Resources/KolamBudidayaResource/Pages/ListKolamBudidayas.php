<?php

namespace App\Filament\Resources\KolamBudidayaResource\Pages;

use App\Filament\Resources\KolamBudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Pembudidaya;
use Filament\Forms\Form;
use Filament\Forms;
use App\Models\KolamBudidaya;

class ListKolamBudidayas extends ListRecords
{
    protected static string $resource = KolamBudidayaResource::class;

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
                ->modalDescription('Buat contoh data Pembudidaya?')
                ->modalSubmitActionLabel('Ya, Generate')
                ->form([
                    Forms\Components\TextInput::make('jumlah')->required(),
                ])
                ->action(function (array $data) {
                    try {
                        if (!Pembudidaya::exists()) {
                            throw new \Exception('Tidak ada data Pengiriman. Silahkan buat data Pengiriman terlebih dahulu.');
                        }
                        Pembudidaya::all()->each(function ($pembudidaya) {
                            KolamBudidaya::factory()
                                ->count(5) // 3 data per kolam
                                ->for($pembudidaya)
                                ->hasKolamMonitoring(10)
                                ->hasKolamSiklus(10)
                                ->create();
                        });
                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data jadwal contoh telah ditambahkan')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        dd($e);
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
