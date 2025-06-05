<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductPriceResource\Pages;
use App\Filament\Resources\ProductPriceResource\RelationManagers;
use App\Models\ProductPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPriceResource extends Resource
{
    protected static ?string $model = ProductPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Harga Produk';

    protected static ?string $navigationLabel = 'Manajemen Harga';

    protected static ?string $navigationGroup = 'Manajemen Produksi';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_type_id')
                    ->relationship('productType', 'product_name')
                    ->required()
                    ->label('Jenis Produk')
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('cost_price')
                    ->numeric()
                    ->required()
                    ->label('Harga Pokok')
                    ->prefix('Rp')
                    ->step(0.01),

                Forms\Components\TextInput::make('selling_price')
                    ->numeric()
                    ->required()
                    ->label('Harga Jual')
                    ->prefix('Rp')
                    ->step(0.01)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($get('cost_price') && $state) {
                            $margin = (($state - $get('cost_price')) / $get('cost_price') * 100);
                            $set('margin', number_format($margin, 2));
                        }
                    }),

                Forms\Components\TextInput::make('margin')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->label('Margin (%)')
                    ->suffix('%')
                    ->step(0.01),
                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productType.product_name')
                    ->label('Jenis Produk')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('formatted_cost_price')
                    ->label('Harga Pokok')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('formatted_margin')
                    ->label('Margin')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('formatted_selling_price')
                    ->label('Harga Jual')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('effective_date')
                    ->label('Tgl Efektif')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type_id')
                    ->relationship('productType', 'product_name')
                    ->label('Filter Jenis Produk'),

                Tables\Filters\Filter::make('active_prices')
                    ->label('Harga Aktif')
                    ->query(fn(Builder $query): Builder => $query->active()),
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
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductPrices::route('/'),
        ];
    }
}
