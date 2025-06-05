<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralStockItemResource\Pages;
use App\Filament\Resources\GeneralStockItemResource\RelationManagers;
use App\Filament\Resources\GeneralStockItemResource\RelationManagers\MutationsRelationManager;
use App\Models\GeneralStockItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Get;

class GeneralStockItemResource extends Resource
{
    protected static ?string $model = GeneralStockItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Stock Item';

    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 7;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Information')
                    ->schema([
                        Forms\Components\Toggle::make('auto_generate_code')
                            ->default(true)
                            ->hidden()
                            ->live(),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Item Code')
                            ->default(fn() => GeneralStockItem::generateCode())
                            ->disabled(fn(Get $get): bool => $get('auto_generate_code'))
                            ->dehydrated()
                            ->suffixAction(
                                Action::make('toggleAutoGenerate')
                                    ->icon(fn(Get $get) => $get('auto_generate_code')
                                        ? 'heroicon-m-lock-open'
                                        : 'heroicon-m-lock-closed')
                                    ->action(function (Set $set, Get $get) {
                                        $set('auto_generate_code', !$get('auto_generate_code'));
                                        if (!$get('auto_generate_code')) {
                                            $set('code', GeneralStockItem::generateCode());
                                        }
                                    })
                            ),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->options([
                                'bibit' => 'Bibit',
                                'pakan' => 'Pakan',
                                'obat' => 'Obat',
                                'alat' => 'Alat',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Select::make('unit')
                            ->options([
                                'kg' => 'Kilogram',
                                'g' => 'Gram',
                                'liter' => 'Liter',
                                'ml' => 'Mililiter',
                                'pcs' => 'Pieces',
                                'pack' => 'Pack',
                                'sak' => 'Sak',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Stock Information')
                    ->schema([
                        Forms\Components\TextInput::make('minimum_stock')
                            ->numeric()
                            ->inputMode('decimal')
                            ->step(0.01),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('Code'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'bibit' => 'primary',
                        'pakan' => 'success',
                        'obat' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('unit')
                    ->badge(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->numeric(decimalPlaces: 3)
                    ->label('Current Stock'),

                Tables\Columns\TextColumn::make('minimum_stock')
                    ->numeric(decimalPlaces: 2),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
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
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'bibit' => 'Bibit',
                        'pakan' => 'Pakan',
                        'obat' => 'Obat',
                        'alat' => 'Alat',
                        'lainnya' => 'Lainnya',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListGeneralStockItems::route('/'),
            'create' => Pages\CreateGeneralStockItem::route('/create'),
            // 'view' => Pages\ViewGeneralStockItem::route('/{record}'),
            'edit' => Pages\EditGeneralStockItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
