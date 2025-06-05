<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\ProductionDaily;
use App\Models\ProductType;
use App\Models\ProductionInput;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Tables\Columns\{DateColumn, TextColumn};
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class ProductionDailyManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $title = 'Daily Productions';
    protected static string $view = 'filament.pages.manage-produksi.production-daily-manager';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return ProductionDaily::query()->with(['productType', 'productionInput']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('production_date')->sortable()->label('Date'),
            TextColumn::make('productType.product_name')->label('Product Type')->searchable(),
            TextColumn::make('productionInput.source_name')->label('Input Source')->searchable(),
            TextColumn::make('production_quantity')->label('Qty (kg)')->sortable(),
            TextColumn::make('batch_code')->label('Batch Code')->copyable(),
            TextColumn::make('note')->label('Note')->limit(30),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()->form($this->getFormSchema()),
            DeleteAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->form($this->getFormSchema())
                ->mutateFormDataUsing(function (array $data): array {
                    if (empty($data['batch_code'])) {
                        $data['batch_code'] = "BATCH-" . now()->format('Ymd') . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));
                    }
                    return $data;
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('production_date')
                ->required()
                ->label('Production Date'),

            Select::make('product_type_id')
                ->label('Product Type')
                ->options(ProductType::pluck('product_name', 'id'))
                ->searchable()
                ->required(),

            Select::make('production_input_id')
                ->label('Production Input')
                ->options(ProductionInput::pluck('source_name', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('production_quantity')
                ->numeric()
                ->required()
                ->label('Production Quantity (kg)'),

            TextInput::make('batch_code')
                ->label('Batch Code')
                ->helperText('Leave blank to auto-generate'),

            Textarea::make('note')
                ->label('Note')
                ->rows(3),
        ];
    }
}
