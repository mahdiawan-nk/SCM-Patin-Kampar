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
use Filament\Tables;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Models\KolamTreatment;
use App\Models\KolamSiklus;

class MonitoringHealthBenih extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.monitoring-health-benih';

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
        $this->dataMonitoring = KolamTreatment::where('kolam_siklus_id', $this->siklusId)->latest()->first();
        $this->resetTable();
    }

    public function table(Table $table): Table
    {

        return $table
            ->headerActions([
                Tables\Actions\Action::make('Tambah Data')
                    ->visible($this->status == 'berjalan')
                    ->form([
                        Forms\Components\TextInput::make('kolam_siklus_id')
                            ->default($this->siklusId),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('treat_at')
                                ->native(false),
                            Forms\Components\TextInput::make('disease')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('medication')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('dosage')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('note')
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->action(function (array $data) {
                        KolamTreatment::create($data);
                        $this->resetTable();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth(MaxWidth::Medium)
            ])
            ->query(KolamTreatment::query()->where('kolam_siklus_id', $this->siklusId)->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('treat_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disease')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medication')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
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
