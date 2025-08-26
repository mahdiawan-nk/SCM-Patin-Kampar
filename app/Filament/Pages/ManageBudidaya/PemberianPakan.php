<?php

namespace App\Filament\Pages\ManageBudidaya;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\KolamFeeding;
use App\Models\KolamSiklus;
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Models\StockFeedingDrug as FeedingStock;
use App\Models\GeneralStockItem;
use App\Models\GeneralStockMutation;

class PemberianPakan extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.pemberian-pakan';

    public $dataSiklus;
    public $dataSiklusAll = [];
    public $dataMonitoring = [];
    public $siklusId;
    public ?array $dataSearch = [];
    public $perPage = 3;
    public $currentPage = 1;
    public $search = '';
    public $date = '';
    public $status = 'berjalan';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getPaginatedDataProperty()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;

        $query = KolamSiklus::withTrashed()->with('kolam_budidaya');

        if (!empty($this->search)) {
            $query->whereHas('kolam_budidaya', function ($q) {
                $q->where('nama_kolam', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->date)) {
            $query->whereDate('start_date', $this->date); // Filter berdasarkan tanggal
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return $query
            ->offset($offset)
            ->limit($this->perPage)
            ->get()
            ->toArray();
    }


    public function getTotalPagesProperty()
    {
        $query = KolamSiklus::withTrashed();

        if (!empty($this->search)) {
            $query->whereHas('kolam_budidaya', function ($q) {
                $q->where('nama_kolam', 'like', '%' . $this->search . '%');
            });
        }
        if (!empty($this->date)) {
            $query->whereDate('start_date', $this->date); // Filter berdasarkan tanggal
        }
        $total = $query->count();

        return (int) ceil($total / $this->perPage);
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
        }
    }

    public function updatingSearch()
    {
        $this->currentPage = 1; // Reset ke halaman pertama saat search diubah
    }
    public function updatingStatus()
    {
        $this->currentPage = 1;
        $this->resetTable();
    }

    public function goToMonitoring($id = null)
    {
        $this->siklusId = $id;
        $this->dataSiklus = KolamSiklus::find($this->siklusId);
        $this->dataMonitoring = KolamFeeding::where('kolam_siklus_id', $this->siklusId)->latest()->first();
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\Action::make('Tambah Data')
                    ->visible($this->status == 'berjalan')
                    ->form([
                        Forms\Components\Hidden::make('kolam_siklus_id')
                            ->default($this->siklusId),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('general_stock_item_id')
                                    ->label('Pakan')
                                    ->options(function () {
                                        return GeneralStockItem::query()
                                            ->with(['mutations', 'balance'])
                                            ->where('category', 'pakan')
                                            ->get()
                                            ->mapWithKeys(function ($item) {
                                                return [
                                                    $item->id => sprintf(
                                                        '%s - %s (Stok: %s %s)',
                                                        $item->name,
                                                        $item->code,
                                                        floor($item->current_stock ?? 0),
                                                        $item->unit
                                                    )
                                                ];
                                            });
                                    })
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $item = $state ? GeneralStockItem::find($state) : null;
                                        $set('feeding_name', $item?->name ?? '');
                                    })
                                    ->required(),

                                Forms\Components\TextInput::make('feeding_name')
                                    ->label('Nama Pakan')
                                    ->readOnly()
                                    ->maxLength(255),

                                Forms\Components\DateTimePicker::make('feed_at')
                                    ->label('Waktu Pemberian')
                                    ->native(false)
                                    ->required(),

                                Forms\Components\TextInput::make('feed_amount')
                                    ->label('Jumlah Pakan (kg)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('frequency')
                                    ->label('Frekuensi (kali)')
                                    ->numeric()
                                    ->default(1),

                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan')
                                    ->columnSpanFull(),
                            ]),

                    ])
                    ->action(function (array $data) {
                        $feeding = KolamFeeding::create($data);
                        GeneralStockMutation::create([
                            'general_stock_item_id' => $data['general_stock_item_id'],
                            'date' => $data['feed_at'] ?? now(),
                            'type' => 'out',
                            'quantity' => $data['feed_amount'],
                            'reference_type' => KolamFeeding::class,
                            'reference_id' => $feeding->id,
                            'source' => 'kolam_feeding',
                            'usage_type' => 'pakan',
                            'note' => $data['note'] ?? null,
                            'created_by' => auth()->id(),
                        ]);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::Large)
            ])
            ->query(KolamFeeding::query()->with(['generalStockItem'])->where('kolam_siklus_id', $this->siklusId)->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('generalStockItem.name')
                    ->label('Nama Pakan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('feeding_name')
                    ->label('Nama Pakan (Custom)')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('feed_at')
                    ->label('Waktu Pemberian')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('feed_amount')
                    ->label('Jumlah Pakan')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2) . ' kg'),

                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frekuensi')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(50) // batasi agar tabel lebih rapi
                    ->tooltip(fn($record) => $record->note) // tampilkan full di tooltip
                    ->sortable(),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Hidden::make('kolam_siklus_id')
                            ->default($this->siklusId),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('general_stock_item_id')
                                    ->label('Pakan')
                                    ->options(function () {
                                        return GeneralStockItem::query()
                                            ->with(['mutations', 'balance'])
                                            ->where('category', 'pakan')
                                            ->get()
                                            ->mapWithKeys(function ($item) {
                                                return [
                                                    $item->id => sprintf(
                                                        '%s - %s (Stok: %s %s)',
                                                        $item->name,
                                                        $item->code,
                                                        floor($item->current_stock ?? 0),
                                                        $item->unit
                                                    )
                                                ];
                                            });
                                    })
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $item = $state ? GeneralStockItem::find($state) : null;
                                        $set('feeding_name', $item?->name ?? '');
                                    })
                                    ->required(),

                                Forms\Components\TextInput::make('feeding_name')
                                    ->label('Nama Pakan')
                                    ->readOnly()
                                    ->maxLength(255),

                                Forms\Components\DateTimePicker::make('feed_at')
                                    ->label('Waktu Pemberian')
                                    ->native(false)
                                    ->required(),

                                Forms\Components\TextInput::make('feed_amount')
                                    ->label('Jumlah Pakan (kg)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('frequency')
                                    ->label('Frekuensi (kali)')
                                    ->numeric()
                                    ->default(1),

                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan')
                                    ->columnSpanFull(),
                            ]),

                    ])
                    ->action(function ($record, array $data) {
                        $feeding = KolamFeeding::findOrFail($record->id);
                        $feeding->update($data);

                        GeneralStockMutation::create([
                            'general_stock_item_id' => $data['general_stock_item_id'],
                            'date' => $data['feed_at'] ?? now(),
                            'type' => 'out',
                            'quantity' => $data['feed_amount'],
                            'reference_type' => KolamFeeding::class,
                            'reference_id' => $feeding->id,
                            'source' => 'kolam_feeding',
                            'usage_type' => 'pakan',
                            'note' => $data['note'] ?? null,
                            'created_by' => auth()->id(),
                        ]);
                        $this->resetTable();
                        Notification::make()
                            ->title('Updated successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::Medium),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
