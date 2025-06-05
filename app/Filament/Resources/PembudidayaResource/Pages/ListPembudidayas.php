<?php

namespace App\Filament\Resources\PembudidayaResource\Pages;

use App\Filament\Resources\PembudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Pembudidaya;
use Filament\Forms\Form;
use Filament\Forms;
class ListPembudidayas extends ListRecords
{
    protected static string $resource = PembudidayaResource::class;

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
                        // if (!Pembudidaya::exists()) {
                        //     throw new \Exception('Tidak ada data Pengiriman. Silahkan buat data Pengiriman terlebih dahulu.');
                        // }
                        Pembudidaya::factory()
                            ->hasUsaha()
                            ->count($data['jumlah'])
                            ->create();

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
