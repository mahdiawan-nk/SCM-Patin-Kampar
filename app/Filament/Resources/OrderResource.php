<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Customer;
use App\Models\ProductType;
use App\Models\PackagingBatch;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $navigationLabel = 'Daftar Pesanan';
    protected static ?string $navigationGroup = 'Manajemen Distribusi';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Nomor Pesanan')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->label('Pelanggan')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nama Pelanggan'),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telepon')
                                    ->tel()
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat')
                                    ->columnSpanFull(),
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Buat Pelanggan Baru')
                                    ->modalButton('Simpan Pelanggan');
                            })
                            ->columnSpan(1),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'batal' => 'Batal',
                            ])
                            ->required()
                            ->label('Status')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->label('Total Amount')
                            ->prefix('Rp')
                            ->step(0.01)
                            ->columnSpan(1)
                            ->dehydrated()
                            ->hiddenOn('create'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Items Pesanan')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_type_id')
                                    ->relationship('productType', 'product_name')
                                    ->required()
                                    ->label('Produk')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $productType = ProductType::find($state);
                                        if ($productType) {
                                            $set('price', $productType->latestPrice?->selling_price);
                                            $set('unit', 'kg');
                                        }
                                    })
                                    ->columnSpan(2),

                                Forms\Components\Select::make('packaging_batch_id')
                                    ->relationship('packagingBatch', 'label_code')
                                    ->label('Batch Kemasan')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->label('Jumlah')
                                    ->step(0.01)
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                        self::updateItemTotal($set, $get);
                                    })
                                    ->columnSpan(1),

                                Forms\Components\Select::make('unit')
                                    ->options([
                                        'kg' => 'Kilogram',
                                        'g' => 'Gram',
                                        'pcs' => 'Pieces',
                                    ])
                                    ->required()
                                    ->label('Satuan')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->label('Harga Satuan')
                                    ->prefix('Rp')
                                    ->step(0.01)
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                        self::updateItemTotal($set, $get);
                                    })
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('item_total')
                                    ->numeric()
                                    ->label('Subtotal')
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->columnSpan(2),
                            ])
                            ->columns(9)
                            ->itemLabel(fn(array $state): ?string =>
                            ProductType::find($state['product_type_id'])?->name ?? 'Item Baru')
                            ->addActionLabel('Tambah Item')
                            ->minItems(1)
                            ->collapsible()
                            ->collapsed()
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                            ),
                    ]),

                Forms\Components\Section::make('Total & Catatan')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->label('Total Pesanan')
                            ->prefix('Rp')
                            ->step(0.01)
                            ->columnSpan(1)
                            ->dehydrated(),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    protected static function updateItemTotal(Forms\Set $set, Forms\Get $get): void
    {
        $quantity = (float) $get('quantity');
        $price = (float) $get('price');
        $set('item_total', number_format($quantity * $price, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'diproses' => 'info',
                        'dikirim' => 'warning',
                        'selesai' => 'success',
                        'batal' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                    }),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Filter Pelanggan'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                    ])
                    ->label('Filter Status'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function afterCreate(Model $record): void
    {
        $total = $record->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        $record->update(['total_amount' => $total]);
    }

    public static function afterSave(Model $record): void
    {
        $total = $record->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        $record->update(['total_amount' => $total]);
    }
}
