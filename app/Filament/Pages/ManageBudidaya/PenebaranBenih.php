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
use App\Models\KolamSiklus;
use App\Models\KolamBudidaya;
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
    use Faker\Factory;

class PenebaranBenih extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.penebaran-benih';

    public $dataKolam;
    public $dataKolamAll = [];
    public $dataSiklus = [];
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
        $faker = Factory::create(); // Inisialisasi Faker

        return [
            Action::make('generateFakeData')
                ->label('Generate Data Contoh')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->action(function () use ($faker) { // Gunakan $faker di closure
                    try {
                        if (!KolamBudidaya::exists()) {
                            throw new \Exception('Tidak ada data Kolam Budidaya. Silahkan buat data Kolam Budidaya terlebih dahulu.');
                        }

                        $totalKolam = KolamBudidaya::count();
                        $siklusPerKolam = 3;

                        $progress = Notification::make()
                            ->title('Sedang membuat data contoh...')
                            ->body("0 dari {$totalKolam} kolam diproses")
                            ->persistent()
                            ->send();

                        $processed = 0;

                        KolamBudidaya::chunk(100, function ($kolams) use (&$processed, $siklusPerKolam, $progress, $totalKolam, $faker) {
                            $kolams->each(function ($kolam) use ($siklusPerKolam, $faker) {
                                KolamSiklus::factory()
                                    ->count($siklusPerKolam)
                                    ->for($kolam)
                                    ->state([
                                        'status' => $faker->randomElement(['berjalan', 'selesai'])
                                    ])
                                    ->create();
                            });

                            $processed += $kolams->count();
                            $progress->body("{$processed} dari {$totalKolam} kolam diproses");
                        });

                        Notification::make()
                            ->title('Data contoh berhasil dibuat')
                            ->body("{$siklusPerKolam} data siklus per kolam telah ditambahkan untuk {$totalKolam} kolam")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
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
        $this->kolamId = null;
    }

    public function goToMonitoring($id = null)
    {
        $this->kolamId = $id;
        $this->dataKolam = KolamBudidaya::find($this->kolamId);
        $this->dataSiklus = KolamSiklus::where('kolam_budidaya_id', $this->kolamId)->latest()->first();
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
                            Forms\Components\TextInput::make('strain')
                                ->label('Jenis Ikan')
                                ->maxLength(255),
                            Forms\Components\DateTimePicker::make('start_date')
                                ->native(false),
                            Forms\Components\TextInput::make('initial_stock')
                                ->numeric(),
                            Forms\Components\TextInput::make('initial_avg_weight')
                                ->numeric(),
                            Forms\Components\TextInput::make('stocking_density')
                                ->numeric(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'berjalan' => 'Berjalan',
                                    'selese' => 'Selesai',
                                ])
                                ->required(),
                        ]),
                    ])
                    ->action(function (array $data) {
                        KolamSiklus::create($data);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::Medium)
            ])
            ->query(KolamSiklus::query()->where('kolam_budidaya_id', $this->kolamId)->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('strain')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('initial_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('initial_avg_weight')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stocking_density')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'berjalan' => 'Berjalan',
                        'selesai' => 'Selesai',
                    ])
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
