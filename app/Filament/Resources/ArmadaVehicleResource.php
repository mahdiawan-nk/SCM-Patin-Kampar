<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArmadaVehicleResource\Pages;
use App\Filament\Resources\ArmadaVehicleResource\RelationManagers;
use App\Models\ArmadaVehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ArmadaVehicleResource extends Resource
{
    protected static ?string $model = ArmadaVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Armada Vehicle';

    protected static ?string $navigationLabel = 'Kendaraan Armada';

    protected static ?string $navigationGroup = 'Manajemen Distribusi';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vehicle Information')
                    ->schema([
                        Forms\Components\TextInput::make('plate_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->label('Nomor Plat'),

                        Forms\Components\Select::make('vehicle_type')
                            ->options(ArmadaVehicle::getVehicleTypes())
                            ->required()
                            ->label('Jenis Kendaraan'),

                        Forms\Components\TextInput::make('capacity_kg')
                            ->numeric()
                            ->required()
                            ->label('Kapasitas (kg)')
                            ->step(0.01),

                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->label('Status Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate_number')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Plat'),

                Tables\Columns\TextColumn::make('vehicle_type')
                    ->formatStateUsing(fn($state) => ArmadaVehicle::getVehicleTypes()[$state] ?? $state)
                    ->label('Jenis Kendaraan'),

                Tables\Columns\TextColumn::make('formatted_capacity')
                    ->label('Kapasitas')
                    ->sortable(['capacity_kg']),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->options(ArmadaVehicle::getVehicleTypes())
                    ->label('Jenis Kendaraan'),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListArmadaVehicles::route('/'),
            'create' => Pages\CreateArmadaVehicle::route('/create'),
            'edit' => Pages\EditArmadaVehicle::route('/{record}/edit'),
        ];
    }
}
