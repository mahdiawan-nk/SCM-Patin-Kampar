<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\ProductionInput;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\{DatePicker, TextInput, Textarea, Select};
use Filament\Tables\Columns\{TextColumn, DateColumn};
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class ProductionInputManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static string $view = 'filament.pages.manage-produksi.production-input-manager';
    protected static ?string $title = 'Production Inputs';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return ProductionInput::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('production_date')->sortable(),
            TextColumn::make('source_type_name')
                ->label('Source Type')
                ->sortable()
                ->searchable(),
            TextColumn::make('source_name')->searchable(),
            TextColumn::make('total_weight_kg')->label('Weight (kg)')->sortable(),
            TextColumn::make('note')->limit(30),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Edit Production Input'),
            DeleteAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->form($this->getFormSchema())
                ->modalHeading('Create Production Input')
                ->modalWidth('md'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('production_date')
                ->required()
                ->label('Production Date'),
            Select::make('source_type')
                ->options(ProductionInput::SOURCE_TYPES)
                ->required()
                ->label('Source Type'),
            TextInput::make('source_name')
                ->required()
                ->maxLength(255),
            TextInput::make('total_weight_kg')
                ->numeric()
                ->required()
                ->label('Total Weight (kg)'),
            Textarea::make('note')
                ->label('Note')
                ->maxLength(500)
                ->rows(3),
        ];
    }
}
