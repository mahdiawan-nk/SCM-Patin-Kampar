<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\QualityControl;
use App\Models\ProductionDaily;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class QualityControlManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $title = 'Quality Control';
    protected static string $view = 'filament.pages.manage-produksi.quality-control-manager';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return QualityControl::query()->with('productionDaily');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('productionDaily.batch_code')->label('Batch Code')->searchable(),
            TextColumn::make('inspector_name')->searchable()->label('Inspector'),
            TextColumn::make('inspection_date')->date()->sortable()->label('Inspection Date'),
            TextColumn::make('result')->label('Result')->formatStateUsing(fn($state, $record) => $record->result_name)->sortable(),
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
            Select::make('production_daily_id')
                ->label('Production Daily')
                ->options(ProductionDaily::pluck('batch_code', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('inspector_name')->required()->maxLength(255),

            DatePicker::make('inspection_date')->required(),

            Radio::make('result')
                ->options(QualityControl::RESULTS)
                ->required(),

            Textarea::make('note')->rows(3),
        ];
    }
}
