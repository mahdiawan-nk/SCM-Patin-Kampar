<?php

namespace App\Filament\Pages\ManageBudidaya;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\KolamMonitoring;
use App\Models\KolamBudidaya;
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class MonitoringKolam extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.monitoring-kolam';

    public $dataKolam;
    public $dataKolamAll = [];
    public $dataMonitoring = [];
    public $kolamId;
    public ?array $dataSearch = [];
    public $perPage = 3;
    public $currentPage = 1;
    public $search = '';
    public function mount(): void
    {
        $this->form->fill();
        $this->dataKolam = KolamBudidaya::withTrashed()->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->action(function () {
                    try {
                        if (!KolamBudidaya::exists()) {
                            throw new \Exception('Tidak ada data Pengiriman. Silahkan buat data Pengiriman terlebih dahulu.');
                        }
                        KolamBudidaya::all()->each(function ($kolam) {
                            KolamMonitoring::factory()
                                ->count(3) // 3 data per kolam
                                ->for($kolam)
                                ->create();
                        });

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body('5 data jadwal contoh telah ditambahkan')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        dd($e);
                        Notification::make()
                            ->title('Gagal membuat data contoh')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }

    public function getPaginatedDataProperty()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;

        $query = KolamBudidaya::withTrashed();

        if (!empty($this->search)) {
            $query->where('nama_kolam', 'like', '%' . $this->search . '%');
        }

        // Jangan simpan hasil ke property, cukup return saja
        return $query
            ->offset($offset)
            ->limit($this->perPage)
            ->get()
            ->toArray();
    }

    public function getTotalPagesProperty()
    {
        $query = KolamBudidaya::withTrashed();

        if (!empty($this->search)) {
            $query->where('nama_kolam', 'like', '%' . $this->search . '%');
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

    public function goToMonitoring($id = null)
    {
        $this->kolamId = $id;
        $this->dataKolam = KolamBudidaya::find($this->kolamId);
        $this->dataMonitoring = KolamMonitoring::where('kolam_budidaya_id', $this->kolamId)->latest()->first();
        $this->resetTable();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kolam_budidaya_id')
                    ->hiddenLabel()
                    ->searchable()
                    ->maxWidth('xl')
                    ->options(KolamBudidaya::all()->pluck('nama_kolam', 'id')),
            ])
            ->statePath('dataSearch');
    }

    public function table(Table $table): Table
    {

        return $table
            ->headerActions([
                Tables\Actions\Action::make('Tambah Data')
                    ->form([
                        Forms\Components\Hidden::make('kolam_budidaya_id')
                            ->default($this->kolamId),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('tgl_monitoring')
                                ->columnSpan(2)
                                ->native(false),

                            Forms\Components\TextInput::make('temperature')
                                ->numeric(),

                            Forms\Components\TextInput::make('ph')
                                ->numeric(),

                            Forms\Components\TextInput::make('do')
                                ->numeric(),

                            Forms\Components\TextInput::make('tds')
                                ->numeric(),

                            Forms\Components\TextInput::make('turbidity')
                                ->numeric(),

                            Forms\Components\TextInput::make('humidity')
                                ->numeric(),

                            Forms\Components\TextInput::make('brightness')
                                ->numeric(),

                            Forms\Components\TextInput::make('amonia')
                                ->numeric(),

                            Forms\Components\TextInput::make('nitrite')
                                ->numeric(),

                            Forms\Components\TextInput::make('nitrate')
                                ->numeric(),
                        ]),
                    ])
                    ->action(function (array $data) {
                        KolamMonitoring::create($data);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::Medium)
            ])
            ->query(KolamMonitoring::query()->where('kolam_budidaya_id', $this->kolamId)->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('tgl_monitoring')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('temperature')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('Suhu (°C)')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ph')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('pH')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('do')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('DO (mg/L)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tds')
                    ->label('TDS (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('turbidity')
                    ->label('Turbidity (NTU)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('humidity')
                    ->label('Kelembaban (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('brightness')
                    ->label('Terang (lux)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amonia')
                    ->label('Amonia (mg/L)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nitrite')
                    ->label('Nitrite (mg/L)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nitrate')
                    ->label('Nitrate (mg/L)')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
