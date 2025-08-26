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
use App\Models\Pembudidaya;

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
    public $selectPembudidaya;
    public $listPembudidaya;
    public function mount(): void
    {
        $this->form->fill();
        $this->dataKolam = KolamBudidaya::withTrashed()->get();
        $this->listPembudidaya = Pembudidaya::all();
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

        if (!empty($this->selectPembudidaya) && $this->selectPembudidaya != 'all') {
            $query->where('pembudidaya_id', $this->selectPembudidaya);
        }

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

        if (!empty($this->selectPembudidaya) && $this->selectPembudidaya != 'all') {
            $query->where('pembudidaya_id', $this->selectPembudidaya);
        }


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
                                ->label('Tanggal & Waktu Monitoring')
                                ->columnSpan(2)
                                ->native(false),

                            Forms\Components\TextInput::make('temperature')
                                ->label('Suhu Air (°C)')
                                ->numeric(),

                            Forms\Components\TextInput::make('ph')
                                ->label('pH Air')
                                ->numeric(),

                            Forms\Components\TextInput::make('do')
                                ->label('DO (mg/L)')
                                ->numeric(),

                            Forms\Components\TextInput::make('tds')
                                ->label('TDS (ppm)')
                                ->numeric(),

                            Forms\Components\TextInput::make('turbidity')
                                ->label('Kekeruhan (NTU)')
                                ->numeric(),

                            Forms\Components\TextInput::make('humidity')
                                ->label('Kelembapan (%)')
                                ->numeric(),

                            Forms\Components\TextInput::make('brightness')
                                ->label('Kecerahan (lux)')
                                ->numeric(),

                            Forms\Components\TextInput::make('amonia')
                                ->label('Amonia (mg/L)')
                                ->numeric(),

                            Forms\Components\TextInput::make('nitrite')
                                ->label('Nitrit (mg/L)')
                                ->numeric(),

                            Forms\Components\TextInput::make('nitrate')
                                ->label('Nitrat (mg/L)')
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
                    ->label('Tanggal Monitoring')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime('d M Y H:i') // format lebih ramah pengguna
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('temperature')
                    ->label('Suhu (°C)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 1)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ph')
                    ->label('pH')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('do')
                    ->label('DO (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('tds')
                    ->label('TDS (ppm)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),

                TextColumn::make('turbidity')
                    ->label('Kekeruhan (NTU)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),

                TextColumn::make('humidity')
                    ->label('Kelembapan (%)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),

                TextColumn::make('brightness')
                    ->label('Kecerahan (lux)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),

                TextColumn::make('amonia')
                    ->label('Amonia (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                TextColumn::make('nitrite')
                    ->label('Nitrit (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                TextColumn::make('nitrate')
                    ->label('Nitrat (mg/L)')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->numeric(decimalPlaces: 3)
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
