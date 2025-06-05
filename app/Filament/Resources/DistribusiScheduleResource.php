<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistribusiScheduleResource\Pages;
use App\Filament\Resources\DistribusiScheduleResource\RelationManagers;
use App\Models\DistribusiSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class DistribusiScheduleResource extends Resource
{
    protected static ?string $model = DistribusiSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $modelLabel = 'Jadwal Distribusi';

    protected static ?string $navigationLabel = 'Jadwal Distribusi';

    protected static ?string $navigationGroup = 'Manajemen Distribusi';
    protected static ?int $navigationSort = 7;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jadwal')
                    ->schema([
                        Forms\Components\Select::make('delivery_id')
                            ->relationship('delivery', 'id')
                            ->required()
                            ->label('Pengiriman'),

                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->label('Tanggal Jadwal'),

                        Forms\Components\TextInput::make('time_window')
                            ->required()
                            ->label('Jangka Waktu')
                            ->placeholder('HH:MM-HH:MM')
                            ->helperText('Format: 08:00-12:00'),

                        Forms\Components\Select::make('status')
                            ->options(DistribusiSchedule::getStatusOptions())
                            ->required()
                            ->label('Status'),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Waktu')
                    ->schema([
                        Forms\Components\TextInput::make('start_time')
                            ->label('Waktu Mulai')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('end_time')
                            ->label('Waktu Selesai')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('is_active')
                            ->label('Status Aktif')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3)
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('delivery.id')
                    ->label('ID Pengiriman')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date()
                    ->label('Tanggal')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_window')
                    ->label('Jangka Waktu'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        DistribusiSchedule::STATUS_DIJADWALKAN => 'gray',
                        DistribusiSchedule::STATUS_BERLANGSUNG => 'warning',
                        DistribusiSchedule::STATUS_SELESAI => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => DistribusiSchedule::getStatusText($state)),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif Sekarang')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(DistribusiSchedule::getStatusOptions())
                    ->label('Status'),

                Tables\Filters\Filter::make('active_schedules')
                    ->label('Sedang Berlangsung')
                    ->query(fn(Builder $query): Builder => $query->where('status', DistribusiSchedule::STATUS_BERLANGSUNG)),
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
            'index' => Pages\ListDistribusiSchedules::route('/'),
            'create' => Pages\CreateDistribusiSchedule::route('/create'),
            'edit' => Pages\EditDistribusiSchedule::route('/{record}/edit'),
        ];
    }
}
