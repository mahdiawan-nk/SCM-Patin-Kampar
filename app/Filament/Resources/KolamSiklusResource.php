<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamSiklusResource\Pages;
use App\Filament\Resources\KolamSiklusResource\RelationManagers;
use App\Models\KolamSiklus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KolamSiklusResource extends Resource
{
    protected static ?string $model = KolamSiklus::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kolam_budidaya_id')
                    ->relationship('kolam_budidaya', 'nama_kolam')
                    ->required(),
                Forms\Components\TextInput::make('strain')
                    ->label('Jenis Ikan')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('start_date')
                ->native(false),
                Forms\Components\TextInput::make('initial_stock')
                    ->numeric(),
                Forms\Components\TextInput::make('initial_avg_weight')
                    ->numeric(),
                Forms\Components\TextInput::make('stocking_density')
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'berjalan' => 'Berjalan',
                        'selese' => 'Selesai',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kolam_budidaya.nama_kolam')
                    ->sortable(),
                Tables\Columns\TextColumn::make('strain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_avg_weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stocking_density')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
                //
            ])
            ->actions([
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
            'index' => Pages\ListKolamSikluses::route('/'),
            'create' => Pages\CreateKolamSiklus::route('/create'),
            'edit' => Pages\EditKolamSiklus::route('/{record}/edit'),
        ];
    }
}
