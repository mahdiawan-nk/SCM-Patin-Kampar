<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductStockItemResource\Pages;
use App\Filament\Resources\ProductStockItemResource\RelationManagers;
// use App\Filament\Resources\ProductStockItemResource\RelationManagers\MutationsRelationManager;
use App\Models\ProductStockItem;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductStockItemResource extends Resource
{
    protected static ?string $model = ProductStockItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 7;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_type_id')
                    ->relationship('productType', 'product_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('minimum_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                // Forms\Components\Section::make('Current Stock')
                //     ->schema([
                //         Forms\Components\TextInput::make('current_stock')
                //             ->default(fn(ProductStockItem $record) => $record->current_stock),
                //         Forms\Components\TextInput::make('status')
                //             ->default(fn(ProductStockItem $record) => $record->isBelowMinimumStock()
                //                 ? 'Below minimum stock'
                //                 : 'Stock level OK')

                //     ])
                //     ->columns(2)
                //     ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productType.product_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 2)
                    ->state(fn(ProductStockItem $record) => $record->current_stock),
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type_id')
                    ->label('Product Type')
                    ->options(ProductType::pluck('product_name', 'id')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Only Active')
                    ->falseLabel('Only Inactive')
                    ->native(false),
                Tables\Filters\Filter::make('below_minimum_stock')
                    ->label('Below Minimum Stock')
                    ->query(fn(Builder $query) => $query->whereColumn(
                        'minimum_stock',
                        '>',
                        DB::raw('(SELECT quantity FROM product_stock_balances WHERE product_stock_balances.product_stock_item_id = product_stock_items.id)')
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('stockAdjustment')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                                'in' => 'Stock In',
                                'out' => 'Stock Out',
                                'adjustment' => 'Adjustment',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),
                    ])
                    ->action(function (ProductStockItem $record, array $data): void {
                        $record->mutations()->create([
                            'date' => now(),
                            'type' => $data['type'],
                            'quantity' => $data['quantity'],
                            'source' => 'manual-adjustment',
                            'note' => $data['note'],
                            'created_by' => auth()->id(),
                        ]);
                    }),
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
            // MutationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductStockItems::route('/'),
            'create' => Pages\CreateProductStockItem::route('/create'),
            'edit' => Pages\EditProductStockItem::route('/{record}/edit'),
        ];
    }
}
