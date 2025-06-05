<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use App\Models\ArmadaDriver;
use App\Models\ArmadaVehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $navigationLabel = 'Manajemen Pengiriman';

    protected static ?string $navigationGroup = 'Manajemen Distribusi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->required()
                            ->label('Nomor Pesanan'),

                        Forms\Components\Select::make('delivery_type')
                            ->options(Delivery::getDeliveryTypes())
                            ->required()
                            ->label('Jenis Pengiriman')
                            ->live(),

                        Forms\Components\DatePicker::make('delivery_date')
                            ->required()
                            ->label('Tanggal Pengiriman'),

                        Forms\Components\DateTimePicker::make('estimated_arrival')
                            ->required()
                            ->label('Estimasi Tiba'),

                        Forms\Components\DateTimePicker::make('actual_arrival')
                            ->label('Tiba Pada')
                            ->nullable(),

                        Forms\Components\Select::make('status')
                            ->options(Delivery::getStatuses())
                            ->required()
                            ->label('Status'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('driver_id')
                            ->relationship('driver', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Driver')
                            ->nullable(),

                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'plate_number')
                            ->searchable()
                            ->preload()
                            ->label('Kendaraan')
                            ->nullable(),

                        Forms\Components\TextInput::make('courier_service')
                            ->label('Layanan Kurir')
                            ->maxLength(255)
                            ->nullable(),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Tracking')
                            ->maxLength(255)
                            ->nullable(),

                        Forms\Components\TextInput::make('tracking_url')
                            ->label('Link Tracking')
                            ->url()
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Nomor Pesanan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('delivery_type')
                    ->formatStateUsing(fn($state) => Delivery::getDeliveryTypes()[$state] ?? $state)
                    ->label('Jenis'),

                Tables\Columns\TextColumn::make('delivery_date')
                    ->date()
                    ->label('Tanggal Kirim')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimated_arrival')
                    ->dateTime()
                    ->label('Estimasi Tiba'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Delivery::STATUS_DIPROSES => 'info',
                        Delivery::STATUS_DIKIRIM => 'warning',
                        Delivery::STATUS_TIBA => 'success',
                        Delivery::STATUS_GAGAL => 'danger',
                    })
                    ->formatStateUsing(fn($state) => Delivery::getStatuses()[$state] ?? $state),

                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable(),

                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Kendaraan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('delivery_type')
                    ->options(Delivery::getDeliveryTypes())
                    ->label('Jenis Pengiriman'),

                Tables\Filters\SelectFilter::make('status')
                    ->options(Delivery::getStatuses())
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label('Driver'),

                Tables\Filters\Filter::make('is_late')
                    ->label('Terlambat')
                    ->query(fn(Builder $query): Builder => $query->where('actual_arrival', '>', 'estimated_arrival'))
                    ->hidden(fn() => !request()->has('is_late')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
