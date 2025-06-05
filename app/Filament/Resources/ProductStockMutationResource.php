<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductStockMutationResource\Pages;
use App\Filament\Resources\ProductStockMutationResource\RelationManagers;
use App\Models\ProductStockMutation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductStockMutationResource extends Resource
{
    protected static ?string $model = ProductStockMutation::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?string $navigationParentItem = 'Product Stock Items';
    protected static ?string $modelLabel = 'Stock Movement';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_stock_item_id')
                    ->relationship('productStockItem', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'adjustment' => 'Adjustment',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0.01),
                Forms\Components\TextInput::make('current_stock')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productStockItem.productType.product_name')
                    ->label('Product')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'in' => 'In',
                        'out' => 'Out',
                        'adjustment' => 'Adjust',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 2),
                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 2)
                    ->label('Stock After'),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('product_stock_item_id')
                //     ->relationship('productStockItem', 'productType.product_name')
                //     ->label('Product'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'in' => 'In',
                        'out' => 'Out',
                        'adjustment' => 'Adjustment',
                    ]),
                // Tables\Filters\DateRangeFilter::make('date'),
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductStockMutations::route('/'),
            'create' => Pages\CreateProductStockMutation::route('/create'),
            'edit' => Pages\EditProductStockMutation::route('/{record}/edit'),
        ];
    }
}
