<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\ProductionWaste;
use App\Models\ProductionInput;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class ProductionWasteManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $title = 'Production Waste';
    protected static string $view = 'filament.pages.manage-produksi.production-waste-manager';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return ProductionWaste::query()->with('productionInput');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('productionInput.production_date')->label('Production Date')->date()->sortable(),
            TextColumn::make('waste_type')->label('Waste Type')->formatStateUsing(fn($state, $record) => $record->waste_type_name)->sortable(),
            TextColumn::make('waste_quantity')->label('Quantity (kg)')->sortable(),
            TextColumn::make('disposal_method')->label('Disposal Method')->formatStateUsing(fn($state, $record) => $record->disposal_method_name)->sortable(),
            TextColumn::make('note')->limit(30),
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
            Tables\Actions\CreateAction::make()->form($this->getFormSchema()),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('production_input_id')
                ->label('Production Input')
                ->options(ProductionInput::query()->orderBy('production_date', 'desc')->pluck('production_date', 'id'))
                ->searchable()
                ->required(),

            Select::make('waste_type')
                ->label('Waste Type')
                ->options(ProductionWaste::WASTE_TYPES)
                ->required(),

            TextInput::make('waste_quantity')
                ->label('Waste Quantity (kg)')
                ->numeric()
                ->required()
                ->minValue(0),

            Select::make('disposal_method')
                ->label('Disposal Method')
                ->options(ProductionWaste::DISPOSAL_METHODS)
                ->required(),

            Textarea::make('note')->rows(3),
        ];
    }
}
