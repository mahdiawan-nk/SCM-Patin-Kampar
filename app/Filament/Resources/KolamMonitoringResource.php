<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamMonitoringResource\Pages;
use App\Filament\Resources\KolamMonitoringResource\RelationManagers;
use App\Models\KolamMonitoring;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;

class KolamMonitoringResource extends Resource
{
    protected static ?string $model = KolamMonitoring::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kolam_budidaya_id')
                    ->label('Kolam Budidaya')
                    ->relationship('kolam_budidaya', 'nama_kolam')
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('tgl_monitoring')
                    ->label('Tanggal Monitoring')
                    ->native(false),

                Forms\Components\TextInput::make('temperature')
                    ->label('Suhu (°C)')
                    ->numeric(),

                Forms\Components\TextInput::make('ph')
                    ->label('pH Air')
                    ->numeric(),

                Forms\Components\TextInput::make('do')
                    ->label('DO (Oksigen Terlarut)')
                    ->numeric(),

                Forms\Components\TextInput::make('tds')
                    ->label('TDS (Total Padatan Terlarut)')
                    ->numeric(),

                Forms\Components\TextInput::make('turbidity')
                    ->label('Kekeruhan Air (NTU)')
                    ->numeric(),

                Forms\Components\TextInput::make('humidity')
                    ->label('Kelembaban Udara (%)')
                    ->numeric(),

                Forms\Components\TextInput::make('brightness')
                    ->label('Kecerahan Cahaya (lux)')
                    ->numeric(),

                Forms\Components\TextInput::make('amonia')
                    ->label('Amonia (mg/L)')
                    ->numeric(),

                Forms\Components\TextInput::make('nitrite')
                    ->label('Nitrit (mg/L)')
                    ->numeric(),

                Forms\Components\TextInput::make('nitrate')
                    ->label('Nitrat (mg/L)')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('kolam_budidaya.nama_kolam')
            ->columns([
                Tables\Columns\TextColumn::make('kolam_budidaya.nama_kolam')
                    ->label('Kolam Budidaya')
                    ->hidden()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tgl_monitoring')
                    ->label('Tanggal Monitoring')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('temperature')
                    ->label('Suhu')
                    ->suffix(' °C')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('ph')
                    ->label('pH Air')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('do')
                    ->label('Oksigen Terlarut')
                    ->suffix(' mg/L')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('tds')
                    ->label('TDS')
                    ->suffix(' mg/L')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('turbidity')
                    ->label('Kekeruhan')
                    ->suffix(' NTU')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('humidity')
                    ->label('Kelembaban')
                    ->suffix(' %')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('brightness')
                    ->label('Kecerahan Cahaya')
                    ->suffix(' lux')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('amonia')
                    ->label('Amonia')
                    ->suffix(' mg/L')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('nitrite')
                    ->label('Nitrit')
                    ->suffix(' mg/L')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('nitrate')
                    ->label('Nitrat')
                    ->suffix(' mg/L')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                ])
                    ->button()
                    ->label('Actions')
            ], position: ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListKolamMonitorings::route('/'),
            'create' => Pages\CreateKolamMonitoring::route('/create'),
            'edit' => Pages\EditKolamMonitoring::route('/{record}/edit'),
        ];
    }
}
