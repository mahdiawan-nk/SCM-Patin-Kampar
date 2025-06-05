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
                    ->relationship('kolam_budidaya', 'nama_kolam')
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('tgl_monitoring')
                    ->native(false),
                Forms\Components\TextInput::make('temperature')
                    ->numeric(),
                Forms\Components\TextInput::make('ph')
                    ->numeric(),
                Forms\Components\TextInput::make('do')
                    ->numeric(),
                Forms\Components\TextInput::make('tds')
                    ->numeric(),
                Forms\Components\TextInput::make('turbidity')
                    ->numeric(),
                Forms\Components\TextInput::make('humidity')
                    ->numeric(),
                Forms\Components\TextInput::make('brightness')
                    ->numeric(),
                Forms\Components\TextInput::make('amonia')
                    ->numeric(),
                Forms\Components\TextInput::make('nitrite')
                    ->numeric(),
                Forms\Components\TextInput::make('nitrate')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('kolam_budidaya.nama_kolam')
            ->columns([
                Tables\Columns\TextColumn::make('kolam_budidaya.nama_kolam')
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_monitoring')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('temperature')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('Suhu (°C)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ph')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('pH')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('do')
                ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('DO (mg/L)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tds')
                    ->label('TDS (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('turbidity')
                    ->label('Turbidity (NTU)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('humidity')
                    ->label('Kelembaban (%)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brightness')
                    ->label('Terang (lux)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amonia')
                    ->label('Amonia (mg/L)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nitrite')
                    ->label('Nitrite (mg/L)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nitrate')
                    ->label('Nitrate (mg/L)')
                    ->numeric()
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
