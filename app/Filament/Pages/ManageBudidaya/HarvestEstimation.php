<?php

namespace App\Filament\Pages\ManageBudidaya;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Models\HarvestEstimation as EstimasiPanen;
use App\Models\KolamBudidaya;
use App\Models\KolamSiklus;
use Filament\Forms\Get;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

class HarvestEstimation extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.harvest-estimation';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\Action::make('Tambah Data')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('kolam_budidaya_id')
                                    ->label('Kolam Budidaya')
                                    ->options(fn() => KolamBudidaya::pluck('nama_kolam', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Reset kolam siklus setiap kali kolam budidaya berubah
                                        $set('kolam_siklus_id', null);
                                        $set('stock_awal', null);
                                    }),

                                Forms\Components\Select::make('kolam_siklus_id')
                                    ->label('Kolam Siklus')
                                    ->required()
                                    ->disabled(fn(Get $get) => empty($get('kolam_budidaya_id')))
                                    ->options(fn(Get $get) => $get('kolam_budidaya_id')
                                        ? KolamSiklus::where('kolam_budidaya_id', $get('kolam_budidaya_id'))
                                        ->get()
                                        ->mapWithKeys(fn($item) => [
                                            $item->id => "{$item->strain} - Jumlah Benih: {$item->initial_stock}"
                                        ])
                                        : [])
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $stockAwal = KolamSiklus::find($state)?->initial_stock;
                                        $set('stock_awal', $stockAwal);
                                    }),

                                Forms\Components\DatePicker::make('estimation_harvest_at')
                                    ->label('Estimasi Tanggal Panen')
                                    ->native(false)
                                    ->required(),

                                Forms\Components\TextInput::make('stock_awal')
                                    ->label('Total Sebar Benih Dalam Kolam')
                                    ->numeric()
                                    ->readOnly()
                                    ->required(),

                                Forms\Components\TextInput::make('estimation_harvest_amount')
                                    ->label('Estimasi Jumlah Panen Ikan')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $stockAwal = $get('stock_awal') ?: 1; // Hindari pembagian nol
                                        $srEstimate = ($state / $stockAwal) * 100;
                                        $set('estimate_survival_rate', round($srEstimate, 2));
                                    }),

                                Forms\Components\TextInput::make('estimation_harvest_weight')
                                    ->label('Estimasi Berat Ikan Panen (Kg)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('estimation_harvest_percentage')
                                    ->label('Estimasi Persentase Panen (%)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('estimate_survival_rate')
                                    ->label('Estimasi Persentase Hidup (%)')
                                    ->numeric()
                                    ->readOnly()
                                    ->required(),

                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan')
                                    ->placeholder('Tambahkan catatan tambahan jika perlu...')
                                    ->columnSpanFull(),
                            ])

                    ])
                    ->action(function (array $data) {
                        EstimasiPanen::create($data);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::ExtraLarge)
            ])
            ->query(EstimasiPanen::query()->with(['kolamBudidaya', 'kolamSiklus']))
            ->columns([
                Tables\Columns\TextColumn::make('kolamBudidaya.nama_kolam')
                    ->label('Kolam Budidaya')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimation_harvest_at')
                    ->label('Estimasi Tanggal Panen')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimation_harvest_amount')
                    ->label('Estimasi Jumlah Panen Ikan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimation_harvest_weight')
                    ->label('Estimasi Berat Ikan Panen')
                    ->numeric()
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimation_harvest_percentage')
                    ->label('Estimasi Persentase Panen')
                    ->suffix(' %')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimate_survival_rate')
                    ->suffix(' %')
                    ->label('Persentase Ikan Hidup (%)'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan'),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make()
                SelectFilter::make('kolam_budidaya_id')
                    ->label('Kolam Budidaya')
                    ->searchable()
                    ->relationship('kolamBudidaya', 'nama_kolam'),
                SelectFilter::make('kolam_siklus_id')
                    ->label('Kolam Siklus')
                    ->options(function () {
                        $list = [];
                        KolamSiklus::with('kolam_budidaya')->get()->each(function ($item, $key) use (&$list) {
                            $list[$item->id] = $item->kolam_budidaya->nama_kolam . '-' . $item->strain . ' - Jumlah Benih :' . $item->initial_stock;
                        });
                        return $list;
                    })


            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->color('warning')
                    ->icon('heroicon-s-pencil')
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->modalHeading('Edit Estimasi Panen')
                    ->modalButton('Simpan')
                    ->mountUsing(function (Form $form, EstimasiPanen $record) {
                        $initalStok = KolamSiklus::find($record->kolam_siklus_id)->initial_stock;
                        $form->fill([
                            'kolam_budidaya_id' => $record->kolam_budidaya_id,
                            'kolam_siklus_id' => $record->kolam_siklus_id,
                            'stock_awal' => $initalStok,
                            'estimation_harvest_at' => $record->estimation_harvest_at,
                            'estimation_harvest_amount' => $record->estimation_harvest_amount,
                            'estimation_harvest_weight' => $record->estimation_harvest_weight,
                            'estimation_harvest_percentage' => $record->estimation_harvest_percentage,
                            'estimate_survival_rate' => $record->estimate_survival_rate,
                            'note' => $record->note,
                        ]);

                        // ...
                    })
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('kolam_budidaya_id')
                                    ->label('Kolam Budidaya')
                                    ->options(fn() => KolamBudidaya::pluck('nama_kolam', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Reset kolam siklus setiap kali kolam budidaya berubah
                                        $set('kolam_siklus_id', null);
                                        $set('stock_awal', null);
                                    }),

                                Forms\Components\Select::make('kolam_siklus_id')
                                    ->label('Kolam Siklus')
                                    ->required()
                                    ->disabled(fn(Get $get) => empty($get('kolam_budidaya_id')))
                                    ->options(fn(Get $get) => $get('kolam_budidaya_id')
                                        ? KolamSiklus::where('kolam_budidaya_id', $get('kolam_budidaya_id'))
                                        ->get()
                                        ->mapWithKeys(fn($item) => [
                                            $item->id => "{$item->strain} - Jumlah Benih: {$item->initial_stock}"
                                        ])
                                        : [])
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $stockAwal = KolamSiklus::find($state)?->initial_stock;
                                        $set('stock_awal', $stockAwal);
                                    }),

                                Forms\Components\DatePicker::make('estimation_harvest_at')
                                    ->label('Estimasi Tanggal Panen')
                                    ->native(false)
                                    ->required(),

                                Forms\Components\TextInput::make('stock_awal')
                                    ->label('Total Sebar Benih Dalam Kolam')
                                    ->numeric()
                                    ->readOnly()
                                    ->required(),

                                Forms\Components\TextInput::make('estimation_harvest_amount')
                                    ->label('Estimasi Jumlah Panen Ikan')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $stockAwal = $get('stock_awal') ?: 1; // Hindari pembagian nol
                                        $srEstimate = ($state / $stockAwal) * 100;
                                        $set('estimate_survival_rate', round($srEstimate, 2));
                                    }),

                                Forms\Components\TextInput::make('estimation_harvest_weight')
                                    ->label('Estimasi Berat Ikan Panen (Kg)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('estimation_harvest_percentage')
                                    ->label('Estimasi Persentase Panen (%)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('estimate_survival_rate')
                                    ->label('Estimasi Persentase Hidup (%)')
                                    ->numeric()
                                    ->readOnly()
                                    ->required(),

                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan')
                                    ->placeholder('Tambahkan catatan tambahan jika perlu...')
                                    ->columnSpanFull(),
                            ])
                    ]),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
