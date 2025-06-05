<?php

namespace App\Filament\Resources\GeneralStockItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MutationsRelationManager extends RelationManager
{
    protected static string $relationship = 'mutations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'adjustment' => 'Adjustment',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.001)
                    ->required(),

                Forms\Components\Select::make('adjustment_type')
                    ->options([
                        'tambah' => 'Tambah',
                        'kurang' => 'Kurang',
                    ])
                    ->visible(fn($get) => $get('type') === 'adjustment'),

                Forms\Components\TextInput::make('reason')
                    ->maxLength(255),

                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 3),

                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 3),

                Tables\Columns\TextColumn::make('adjustment_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'tambah' => 'success',
                        'kurang' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('reason'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'adjustment' => 'Adjustment',
                    ]),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
