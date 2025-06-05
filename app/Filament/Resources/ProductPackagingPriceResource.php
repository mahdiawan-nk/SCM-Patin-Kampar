<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductPackagingPriceResource\Pages;
use App\Filament\Resources\ProductPackagingPriceResource\RelationManagers;
use App\Models\ProductPackagingPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPackagingPriceResource extends Resource
{
    protected static ?string $model = ProductPackagingPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Harga Kemasan Produk';

    protected static ?string $navigationLabel = 'Harga Kemasan';

    protected static ?string $navigationParentItem = 'Manajemen Harga';

    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_type_id')
                    ->relationship('productType', 'product_name')
                    ->required()
                    ->label('Jenis Produk')
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),

                Forms\Components\TextInput::make('package_type')
                    ->required()
                    ->label('Jenis Kemasan')
                    ->maxLength(255)
                    ->columnSpan(2),

                Forms\Components\TextInput::make('net_weight')
                    ->numeric()
                    ->required()
                    ->label('Berat Bersih (kg)')
                    ->suffix('kg')
                    ->step(0.01),

                Forms\Components\TextInput::make('cost_price')
                    ->numeric()
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
                        if ($get('net_weight') > 0 && $state) {
                            $pricePerKg = $state / $get('net_weight');
                            $set('price_per_kg', number_format($pricePerKg, 2));
                        }
                    }),

                Forms\Components\TextInput::make('price_per_kg')
                    ->label('Harga per kg')
                    ->prefix('Rp')
                    ->suffix('/kg')
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\DatePicker::make('effective_date')
                    ->label('Tanggal Efektif')
                    ->nullable()
                    ->columnSpan(2),

                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productType.product_name')
                    ->label('Jenis Produk')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('package_type')
                    ->label('Kemasan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('formatted_net_weight')
                    ->label('Berat Bersih')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('formatted_cost_price')
                    ->label('Harga Pokok')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('formatted_selling_price')
                    ->label('Harga Jual')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('formatted_price_per_kg')
                    ->label('Harga/kg')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('effective_date')
                    ->label('Tgl Efektif')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type_id')
                    ->relationship('productType', 'product_name')
                    ->label('Filter Jenis Produk'),

                Tables\Filters\SelectFilter::make('package_type')
                    ->options(fn() => ProductPackagingPrice::distinct('package_type')->pluck('package_type', 'package_type'))
                    ->label('Filter Jenis Kemasan'),

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
            'index' => Pages\ManageProductPackagingPrices::route('/'),
        ];
    }
}
