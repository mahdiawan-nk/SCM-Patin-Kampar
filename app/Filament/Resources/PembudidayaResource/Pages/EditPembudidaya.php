<?php

namespace App\Filament\Resources\PembudidayaResource\Pages;

use App\Filament\Resources\PembudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Notifications\Notification;

class EditPembudidaya extends EditRecord
{
    protected static string $resource = PembudidayaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // dd($data);
        $data['jenis_kelamin']= $data['jenis_kelamin'] == 'Perempuan' ? 'P' : 'L';

        return $data;
    }

    protected function afterSave(): void
    {

        $pembudidaya = $this->record;
        $email = $pembudidaya->email;

        if (!$email) {
            return;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            // Buat user baru jika belum ada
            $user = User::create([
                'name' => $pembudidaya->nama_lengkap,
                'email' => $email,
                'password' => bcrypt('default123'),
            ]);

            $user->assignRole('pembudidaya');

            Notification::make()
                ->title('Success')
                ->body('User berhasil dibuat, dengan password default: default123')
                ->success()
                ->send();
        }

        $pembudidaya->update(['user_id' => $user->id]);
    }
}
