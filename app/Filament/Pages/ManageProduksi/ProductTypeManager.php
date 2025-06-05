<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\ProductType;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Textarea, Select};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\Builder;

class ProductTypeManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $title = 'Product Types';
    protected static string $view = 'filament.pages.manage-produksi.product-type-manager';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Manajemen Produksi';
    public $productTypeId;

    protected function getTableQuery(): Builder
    {
        return ProductType::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('product_name')->searchable()->sortable(),
            TextColumn::make('unit')->sortable(),
            TextColumn::make('description')->limit(30),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Edit Product Type'),
            DeleteAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Create Product Type')
                ->modalWidth('md'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('product_name')
                ->required()
                ->maxLength(255),
            TextInput::make('unit')
                ->required()
                ->maxLength(100),
            Textarea::make('description')
                ->maxLength(500)
                ->rows(3),
        ];
    }
}
