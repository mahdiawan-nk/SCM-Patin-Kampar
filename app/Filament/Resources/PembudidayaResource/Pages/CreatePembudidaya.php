<?php

namespace App\Filament\Resources\PembudidayaResource\Pages;

use App\Filament\Resources\PembudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Models\Pembudidaya;
class CreatePembudidaya extends CreateRecord
{
    protected static string $resource = PembudidayaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $isExitNik = $this->isExitNik();

        if ($isExitNik) {
            Notification::make()
                ->warning()
                ->title('NIK already exists')
                ->body('NIK already exists')
                ->send();

            $this->halt();
        }
    }

    protected function isExitNik(): bool
    {
        return Pembudidaya::where('nik', $this->data['nik'])->exists();
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
        $pembudidaya = $this->record;
        $email = $pembudidaya->email;

        if ($email) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $pembudidaya->nama,
                    'password' => bcrypt('default123'),
                ]
            );

            // Beri role default
            $user->assignRole('pembudidaya');

            // Hubungkan ke pembudidaya
            $pembudidaya->update(['user_id' => $user->id]);
        }
    }
}
