<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralStockMutationResource\Pages;
use App\Filament\Resources\GeneralStockMutationResource\RelationManagers;
use App\Models\GeneralStockItem;
use App\Models\GeneralStockMutation;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GeneralStockMutationResource extends Resource
{
    protected static ?string $model = GeneralStockMutation::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $modelLabel = 'Stock Mutation';
    protected static ?string $navigationParentItem = 'Stock Items';
    protected static ?string $navigationGroup = 'Inventory Management';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('current_stock'),
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('general_stock_item_id')
                            ->label('Stock Item')
                            ->options(GeneralStockItem::query()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->columnSpan(2),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('type')
                            ->options([
                                'in' => 'Stock In',
                                'out' => 'Stock Out',
                                'adjustment' => 'Adjustment',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->inputMode('decimal')
                            ->step(0.001)
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\Select::make('adjustment_type')
                            ->options([
                                'tambah' => 'Increase',
                                'kurang' => 'Decrease',
                            ])
                            ->visible(fn(Forms\Get $get) => $get('type') === 'adjustment'),

                        Forms\Components\TextInput::make('source')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'in')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('usage_type')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'out')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('reason')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Reference Information')
                    ->schema([
                        Forms\Components\Select::make('reference_type')
                            ->options([
                                'purchase' => 'Purchase',
                                'production' => 'Production',
                                'sales' => 'Sales',
                                'transfer' => 'Transfer',
                            ])
                            ->live(),

                        // Forms\Components\TextInput::make('reference_id')
                        //     ->visible(fn(Forms\Get $get) => !empty($get('reference_type'))),

                        Forms\Components\Hidden::make('created_by')
                            ->default(auth()->id()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('item.name')
                    ->searchable()
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
                    ->numeric(decimalPlaces: 3)
                    ->color(fn(GeneralStockMutation $record) => match ($record->type) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => null,
                    }),

                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 3),

                Tables\Columns\TextColumn::make('adjustment_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'tambah' => 'success',
                        'kurang' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('source')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('usage_type')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('general_stock_item_id')
                    ->label('Stock Item')
                    ->options(GeneralStockItem::query()->pluck('name', 'id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'adjustment' => 'Adjustment',
                    ]),

                // Tables\Filters\DateRangeFilter::make('date'),

                Tables\Filters\SelectFilter::make('created_by')
                    ->label('Created By')
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable(),
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
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListGeneralStockMutations::route('/'),
            'create' => Pages\CreateGeneralStockMutation::route('/create'),
            // 'view' => Pages\ViewGeneralStockMutation::route('/{record}'),
            'edit' => Pages\EditGeneralStockMutation::route('/{record}/edit'),
        ];
    }
}
