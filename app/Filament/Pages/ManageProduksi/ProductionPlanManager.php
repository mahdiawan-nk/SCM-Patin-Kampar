<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\ProductionPlan;
use App\Models\ProductType;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\{DatePicker, TextInput, Select, Textarea};
use Filament\Tables\Columns\{TextColumn, DateColumn};
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class ProductionPlanManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static string $view = 'filament.pages.manage-produksi.production-plan-manager';
    protected static ?string $title = 'Production Plans';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return ProductionPlan::query()->with('productType');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('plan_date')->label('Plan Date')->sortable(),
            TextColumn::make('productType.product_name')->label('Product Type')->searchable(),
            TextColumn::make('planned_quantity')->label('Quantity')->sortable(),
            TextColumn::make('status_name')->label('Status')->badge(),
            TextColumn::make('note')->limit(30)->wrap(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Edit Plan'),
            DeleteAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Create Plan')
                ->modalWidth('md'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('plan_date')
                ->required()
                ->label('Plan Date'),
            Select::make('product_type_id')
                ->label('Product Type')
                ->options(ProductType::pluck('product_name', 'id'))
                ->required(),
            TextInput::make('planned_quantity')
                ->label('Planned Quantity (kg)')
                ->numeric()
                ->required(),
            Select::make('status')
                ->options(ProductionPlan::STATUSES)
                ->required(),
            Textarea::make('note')
                ->label('Note')
                ->maxLength(500)
                ->rows(3),
        ];
    }
}
