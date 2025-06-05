<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFeedbackResource\Pages;
use App\Filament\Resources\CustomerFeedbackResource\RelationManagers;
use App\Models\CustomerFeedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class CustomerFeedbackResource extends Resource
{
    protected static ?string $model = CustomerFeedback::class;

    protected static ?string $navigationLabel = 'Feedback Pelanggan';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Manajemen Pemasaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->wrap()
                    ->label('Pesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('submited_at')
                    ->label('Dikirim Pada')
                    ->date('d-m-Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('read_at')
                    ->label('Dibaca Pada')
                    ->date('d-m-Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('response')
                    ->wrap()
                    ->label('Balasan Admin')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Tanggapi')
                    ->modalHeading('Tanggapi Pesan')
                    ->mountUsing(function (Tables\Actions\EditAction $action, CustomerFeedback $record, Form $form) {
                        $record->read_at = now();
                        $record->status = 'dibaca';
                        $record->save();
                        $form->fill([
                            'response' => $record->response,
                        ]);
                    })
                    ->form(function (Form $form) {
                        return $form
                            ->schema([
                                Forms\Components\TextArea::make('response')
                                    ->required()
                                    ->maxLength(255),
                            ]);
                    })
                    ->action(function (CustomerFeedback $record, array $data) {
                        $record->response = $data['response'];
                        $record->status = 'ditanggapi';
                        $record->save();
                        Notification::make()
                            ->title('Balasan berhasil disimpan')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomerFeedback::route('/'),
        ];
    }
}
