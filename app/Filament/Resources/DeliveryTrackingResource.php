<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryTrackingResource\Pages;
use App\Filament\Resources\DeliveryTrackingResource\RelationManagers;
use App\Models\DeliveryTracking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryTrackingResource extends Resource
{
    protected static ?string $model = DeliveryTracking::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $modelLabel = 'Pelacakan Pengiriman';

    protected static ?string $navigationLabel = 'Pelacakan Pengiriman';

    protected static ?string $navigationGroup = 'Manajemen Distribusi';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelacakan')
                    ->schema([
                        Forms\Components\Select::make('delivery_id')
                            ->relationship('delivery', 'id')
                            ->required()
                            ->label('Pengiriman'),

                        Forms\Components\Select::make('status')
                            ->options(DeliveryTracking::getStatusOptions())
                            ->required()
                            ->label('Status'),

                        Forms\Components\DateTimePicker::make('timestamp')
                            ->required()
                            ->label('Waktu'),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->label('Latitude')
                            ->step(0.000001),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->label('Longitude')
                            ->step(0.000001),

                        Forms\Components\TextInput::make('formatted_coordinates')
                            ->label('Koordinat')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('delivery.id')
                    ->label('ID Pengiriman')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        DeliveryTracking::STATUS_MULAI => 'info',
                        DeliveryTracking::STATUS_DALAM_PERJALANAN => 'warning',
                        DeliveryTracking::STATUS_TIBA => 'success',
                        DeliveryTracking::STATUS_TERLAMBAT => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => DeliveryTracking::getStatusText($state)),

                Tables\Columns\TextColumn::make('timestamp')
                    ->dateTime()
                    ->label('Waktu')
                    ->sortable(),

                Tables\Columns\TextColumn::make('formatted_coordinates')
                    ->label('Lokasi')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(DeliveryTracking::getStatusOptions())
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('delivery_id')
                    ->relationship('delivery', 'id')
                    ->label('Pengiriman'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveryTrackings::route('/'),
            'create' => Pages\CreateDeliveryTracking::route('/create'),
            'edit' => Pages\EditDeliveryTracking::route('/{record}/edit'),
        ];
    }
}
