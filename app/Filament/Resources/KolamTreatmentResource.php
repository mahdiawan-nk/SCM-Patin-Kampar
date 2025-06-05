<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamTreatmentResource\Pages;
use App\Filament\Resources\KolamTreatmentResource\RelationManagers;
use App\Models\KolamTreatment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KolamTreatmentResource extends Resource
{
    protected static ?string $model = KolamTreatment::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('kolam_siklus_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('treat_at'),
                Forms\Components\TextInput::make('disease')
                    ->maxLength(255),
                Forms\Components\TextInput::make('medication')
                    ->maxLength(255),
                Forms\Components\TextInput::make('dosage')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('treat_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disease')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medication')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosage')
                    ->searchable(),
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
            'index' => Pages\ListKolamTreatments::route('/'),
            'create' => Pages\CreateKolamTreatment::route('/create'),
            'edit' => Pages\EditKolamTreatment::route('/{record}/edit'),
        ];
    }
}
