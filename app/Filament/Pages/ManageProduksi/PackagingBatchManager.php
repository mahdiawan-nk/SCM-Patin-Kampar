<?php

namespace App\Filament\Pages\ManageProduksi;

use App\Models\PackagingBatch;
use App\Models\ProductionDaily;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Str;
class PackagingBatchManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationLabel = 'Packaging Batch';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static string $view = 'filament.pages.manage-produksi.packaging-batch-manager';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected function getTableQuery(): Builder
    {
        return PackagingBatch::query()->with('dailyProduction');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('dailyProduction.batch_code')
                ->label('Batch Code')
                ->searchable(),

            TextColumn::make('package_type')
                ->label('Package Type')
                ->searchable(),

            TextColumn::make('quantity_pack')
                ->label('Qty (pack)')
                ->sortable(),

            TextColumn::make('label_code')
                ->label('Label Code')
                ->copyable(),
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
                    if (empty($data['label_code'])) {
                        $data['label_code'] = 'PKG-' . now()->format('ymd') . '-' . Str::upper(Str::random(3));
                    }
                    return $data;
                })
                ->modalHeading('Create Packaging Batch')
                ->modalWidth('md'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('daily_production_id')
                ->label('Production Batch')
                ->options(ProductionDaily::pluck('batch_code', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('package_type')
                ->required()
                ->label('Package Type'),

            TextInput::make('quantity_pack')
                ->numeric()
                ->required()
                ->label('Quantity (pack)'),

            TextInput::make('label_code')
                ->label('Label Code')
                ->helperText('Leave blank to auto-generate'),
        ];
    }
}
