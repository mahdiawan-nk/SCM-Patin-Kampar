<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamGrowthResource\Pages;
use App\Filament\Resources\KolamGrowthResource\RelationManagers;
use App\Models\KolamGrowth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KolamGrowthResource extends Resource
{
    protected static ?string $model = KolamGrowth::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 6;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kolam_siklus_id')
                    ->required()
                    ->relationship('kolam_siklus.kolam_budidaya', 'nama_kolam'),
                Forms\Components\DateTimePicker::make('grow_at'),
                Forms\Components\TextInput::make('avg_weight')
                    ->numeric(),
                Forms\Components\TextInput::make('avg_length')
                    ->numeric(),
                Forms\Components\TextInput::make('mortality')
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kolam_siklus_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grow_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_length')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mortality')
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
            'index' => Pages\ListKolamGrowths::route('/'),
            'create' => Pages\CreateKolamGrowth::route('/create'),
            'edit' => Pages\EditKolamGrowth::route('/{record}/edit'),
        ];
    }
}
