<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamFeedingResource\Pages;
use App\Filament\Resources\KolamFeedingResource\RelationManagers;
use App\Models\KolamFeeding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KolamFeedingResource extends Resource
{
    protected static ?string $model = KolamFeeding::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 7;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36),
                Forms\Components\Select::make('kolam_siklus_id')
                    ->relationship('kolam_siklus', 'id')
                    ->required(),
                Forms\Components\TextInput::make('feeding_stock_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('feeding_name')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('feed_at'),
                Forms\Components\TextInput::make('feed_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('frequency')
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
                Tables\Columns\TextColumn::make('kolam_siklus.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feeding_stock_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feeding_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('feed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('frequency')
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
            'index' => Pages\ListKolamFeedings::route('/'),
            'create' => Pages\CreateKolamFeeding::route('/create'),
            'edit' => Pages\EditKolamFeeding::route('/{record}/edit'),
        ];
    }
}
