<?php

namespace App\Filament\Pages\ManageBudidaya;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Models\HarvestRealization as RealisasiPanen;
use App\Models\KolamBudidaya;
use App\Models\KolamSiklus;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

class HarvestRealization extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.harvest-realization';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\Action::make('Tambah Data')
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('kolam_budidaya_id')
                                ->required()
                                ->label('Kolam Budidaya')
                                ->options(KolamBudidaya::all()->pluck('nama_kolam', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {}),
                            Forms\Components\Select::make('kolam_siklus_id')
                                ->label('Kolam Siklus')
                                ->required()
                                ->disabled(function (callable $get) {
                                    return empty($get('kolam_budidaya_id'));
                                })
                                ->options(function (callable $get) {
                                    $list = [];
                                    KolamSiklus::where('kolam_budidaya_id', $get('kolam_budidaya_id'))->get()->each(function ($item, $key) use (&$list, $get) {
                                        $list[$item->id] = $item->strain . ' - Jumlah Benih :' . $item->initial_stock;
                                    });
                                    return $list;
                                })
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $dataStockAwal = KolamSiklus::find($state)->initial_stock;
                                    $set('stock_awal', $dataStockAwal);
                                }),
                            Forms\Components\DatePicker::make('actual_harvest_at')
                                ->required()
                                ->label('Tanggal Panen')
                                ->native(false),
                            Forms\Components\TextInput::make('stock_awal')
                                ->required()
                                ->label('Total Sebar Benih Dalam Kolam')
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_amount')
                                ->required()
                                ->label('Jumlah Panen Ikan')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $dataStockAwal = $get('stock_awal');
                                    $srEstimate = ($state / $dataStockAwal) * 100;
                                    $set('actual_survival_rate', $srEstimate);
                                })
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_weight')
                                ->required()
                                ->label('Berat Ikan Panen')
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_percentage')
                                ->required()
                                ->label('Persentase Panen')
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_survival_rate')
                                ->label(' Persentase Ikan Tidak Mati')
                                ->required()
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\Textarea::make('note')
                                ->label('Catatan')
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->action(function (array $data) {
                        RealisasiPanen::create($data);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::ExtraLarge)
            ])
            ->query(RealisasiPanen::query()->with(['kolamBudidaya', 'kolamSiklus', 'estimation']))
            ->columns([
                Tables\Columns\TextColumn::make('kolamBudidaya.nama_kolam')
                    ->label('Kolam Budidaya')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_harvest_at')
                    ->label('Tanggal')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $est = $record->estimation->estimation_harvest_at ?? null;
                        $actual = $record->actual_harvest_at ?? null;

                        return '
                                <div class="text-sm space-y-1">
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Estimasi</div>
                                        <div>: ' . ($est ? \Carbon\Carbon::parse($est)->translatedFormat('d F Y') : '-') . '</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Aktual</div>
                                        <div>: ' . ($actual ? \Carbon\Carbon::parse($actual)->translatedFormat('d F Y') : '-') . '</div>
                                    </div>
                                </div>
                            ';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_harvest_amount')
                    ->label('Jumlah Ikan')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $est = $record->estimation->estimation_harvest_amount ?? null;
                        $actual = $record->actual_harvest_amount ?? null;

                        return '
                                <div class="text-sm space-y-1">
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Estimasi</div>
                                        <div>: ' . ($est ?? '-') . '</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Aktual</div>
                                        <div>: ' . ($actual ?? '-') . '</div>
                                    </div>
                                </div>
                            ';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_harvest_weight')
                    ->label('Berat Ikan')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $est = $record->estimation->estimation_harvest_weight ?? null;
                        $actual = $record->actual_harvest_weight  ?? null;

                        return '
                                <div class="text-sm space-y-1">
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Estimasi</div>
                                        <div>: ' . ($est ?? '-') . ' kg</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Aktual</div>
                                        <div>: ' . ($actual ?? '-') . ' kg</div>
                                    </div>
                                </div>
                            ';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_harvest_percentage')
                    ->label('Persentase')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $est = $record->estimation->estimation_harvest_percentage ?? null;
                        $actual = $record->actual_harvest_percentage ?? null;

                        return '
                                <div class="text-sm space-y-1">
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Estimasi</div>
                                        <div>: ' . ($est ?? '-') . ' %</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Aktual</div>
                                        <div>: ' . ($actual ?? '-') . ' %</div>
                                    </div>
                                </div>
                            ';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_survival_rate')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $est = $record->estimation->estimate_survival_rate ?? null;
                        $actual = $record->actual_survival_rate ?? null;

                        return '
                                <div class="text-sm space-y-1">
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Estimasi</div>
                                        <div>: ' . ($est ?? '-') . ' %</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="text-gray-500 font-medium">Aktual</div>
                                        <div>: ' . ($actual ?? '-') . ' %</div>
                                    </div>
                                </div>
                            ';
                    })
                    ->label('Persentase Ikan Hidup (%)'),
                Tables\Columns\TextColumn::make('estimation')
                    ->formatStateUsing(fn ($record) => $record->actual_survival_rate - $record->estimation->estimate_survival_rate)
                    ->label('Selisih'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan Aktual Panen'),
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
                    ->mountUsing(function (Form $form, RealisasiPanen $record) {
                        $initalStok = KolamSiklus::find($record->kolam_siklus_id)->initial_stock;
                        $form->fill([
                            'kolam_budidaya_id' => $record->kolam_budidaya_id,
                            'kolam_siklus_id' => $record->kolam_siklus_id,
                            'stock_awal' => $initalStok,
                            'actual_harvest_at' => $record->actual_harvest_at,
                            'actual_harvest_amount' => $record->actual_harvest_amount,
                            'actual_harvest_weight' => $record->actual_harvest_weight,
                            'actual_harvest_percentage' => $record->actual_harvest_percentage,
                            'actual_survival_rate' => $record->actual_survival_rate,
                            'note' => $record->note,
                        ]);

                        // ...
                    })
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('kolam_budidaya_id')
                                ->required()
                                ->label('Kolam Budidaya')
                                ->options(KolamBudidaya::all()->pluck('nama_kolam', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {}),
                            Forms\Components\Select::make('kolam_siklus_id')
                                ->label('Kolam Siklus')
                                ->required()
                                ->disabled(function (callable $get) {
                                    return empty($get('kolam_budidaya_id'));
                                })
                                ->options(function (callable $get) {
                                    $list = [];
                                    KolamSiklus::where('kolam_budidaya_id', $get('kolam_budidaya_id'))->get()->each(function ($item, $key) use (&$list, $get) {
                                        $list[$item->id] = $item->strain . ' - Jumlah Benih :' . $item->initial_stock;
                                    });
                                    return $list;
                                })
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $dataStockAwal = KolamSiklus::find($state)->initial_stock;
                                    $set('stock_awal', $dataStockAwal);
                                }),
                            Forms\Components\DatePicker::make('actual_harvest_at')
                                ->required()
                                ->label('Tanggal Panen')
                                ->native(false),
                            Forms\Components\TextInput::make('stock_awal')
                                ->required()
                                ->label('Total Sebar Benih Dalam Kolam')
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_amount')
                                ->required()
                                ->label('Jumlah Panen Ikan')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $dataStockAwal = $get('stock_awal');
                                    $srEstimate = ($state / $dataStockAwal) * 100;
                                    $set('actual_survival_rate', $srEstimate);
                                })
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_weight')
                                ->required()
                                ->label('Berat Ikan Panen')
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_harvest_percentage')
                                ->required()
                                ->label('Persentase Panen')
                                ->numeric(),
                            Forms\Components\TextInput::make('actual_survival_rate')
                                ->label(' Persentase Ikan Tidak Mati')
                                ->required()
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\Textarea::make('note')
                                ->label('Catatan')
                                ->columnSpanFull(),
                        ]),
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
