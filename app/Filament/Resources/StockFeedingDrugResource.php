<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockFeedingDrugResource\Pages;
use App\Filament\Resources\StockFeedingDrugResource\RelationManagers;
use App\Models\StockFeedingDrug;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockFeedingDrugResource extends Resource
{
    protected static ?string $model = StockFeedingDrug::class;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'pakan' => 'Pakan',
                        'obat' => 'Obat',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric(),
                Forms\Components\TextInput::make('satuan'),
                Forms\Components\DateTimePicker::make('kadaluarsa_at')
                    ->native(false),
                Forms\Components\Textarea::make('note')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kadaluarsa_at')
                    ->dateTime()
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
            'index' => Pages\ListStockFeedingDrugs::route('/'),
            'create' => Pages\CreateStockFeedingDrug::route('/create'),
            'edit' => Pages\EditStockFeedingDrug::route('/{record}/edit'),
        ];
    }
}
