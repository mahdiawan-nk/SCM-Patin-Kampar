<?php

namespace App\Filament\Resources\KolamBudidayaResource\Pages;

use App\Filament\Resources\KolamBudidayaResource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use App\Models\KolamBudidaya;
use App\Models\KolamSiklus;
use App\Models\KolamGrowth;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions\Action;

class SiklusKolam extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = KolamBudidayaResource::class;

    protected static string $view = 'filament.resources.kolam-budidaya-resource.pages.siklus-kolam';

    public $record;
    public $idSiklus;
    public ?array $data = [];
    public $formCreateSiklus = false;
    public $formCreateGrowth = false;
    public $dataKolam = [];
    public $containerSiklus = true;
    public $containerGrowth = false;
    public $dataSiklus = [];
    public $dataGrowth = [];
    public function mount($record)
    {
        $this->record = $record;
        $this->form->fill([
            'kolam_budidaya_id' => $this->record
        ]);

        $this->dataKolam = KolamBudidaya::find($this->record);
    }

    public function getTitle(): string | Htmlable
    {
        return '';
    }

    public function getKolamSiklus()
    {
        $this->dataSiklus = KolamSiklus::where('kolam_budidaya_id', $this->record)->where('id', $this->idSiklus)->first();
    }

    public function getKolamGrowth()
    {
        $this->dataGrowth = KolamGrowth::where('kolam_siklus_id', $this->idSiklus)->latest()->first();
    }
    public function openFormCreate($type): void
    {
        if ($type == 'siklus') {
            $this->formCreateSiklus = true;
            $this->form->fill([
                'kolam_budidaya_id' => $this->record
            ]);
        } else {
            $this->formCreateGrowth = true;
            $this->form->fill([
                'kolam_siklus_id' => $this->idSiklus
            ]);
        }
    }

    public function closeFormCreate($type): void
    {
        if ($type == 'siklus') {
            $this->formCreateSiklus = false;
            $this->form->fill([
                'kolam_budidaya_id' => $this->record
            ]);
        } else {
            $this->formCreateGrowth = false;
            $this->form->fill([
                'kolam_siklus_id' => $this->idSiklus
            ]);
        }
    }

    public function goToSiklus(): void
    {
        $this->switchToSiklus();
    }

    public function form(Form $form): Form
    {
        if ($this->containerSiklus) {
            return $form
                ->schema([
                    Forms\Components\Hidden::make('kolam_budidaya_id'),
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
                ])->columns(2)
                ->statePath('data');
        } else {
            return $form
                ->schema([
                    Forms\Components\Hidden::make('kolam_siklus_id'),
                    Forms\Components\DateTimePicker::make('grow_at')
                        ->required()
                        ->native(false),
                    Forms\Components\TextInput::make('avg_weight')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('avg_length')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('mortality')
                        ->required()
                        ->numeric(),
                    Forms\Components\Textarea::make('note')
                        ->columnSpanFull(),
                ])->columns(2)
                ->statePath('data');
        }
    }

    public function create(): void
    {
        KolamSiklus::create($this->form->getState());
        $this->form->fill([
            'kolam_budidaya_id' => $this->record
        ]);
        $this->formCreateSiklus = false;
        Notification::make()
            ->title('Saved successfully')
            ->body('Siklus berhasil disimpan')
            ->success()
            ->send();
    }

    public function createGrowth(): void
    {
        KolamGrowth::create($this->form->getState());
        $this->form->fill([
            'kolam_siklus_id' => $this->idSiklus
        ]);
        $this->formCreateGrowth = false;
        Notification::make()
            ->title('Saved successfully')
            ->body('Growth berhasil disimpan')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        if ($this->containerSiklus) {
            return $table
                ->query(KolamSiklus::query()->where('kolam_budidaya_id', $this->record)->orderBy('created_at', 'desc'))
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
                        ->afterStateUpdated(function (KolamSiklus $record, string $state) {
                            $record->update(['status' => $state]);
                            Notification::make()
                                ->title('Saved successfully')
                                ->body('Status Kolam Siklus Berhasil Diubah')
                                ->success()
                                ->send();
                        })
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    Tables\Actions\Action::make('history_growth')
                        ->icon('heroicon-s-chart-bar')
                        ->label('History Growth')
                        ->action(function (KolamSiklus $record) {
                            self::switchToGrowth($record->id);
                        })
                ], position: ActionsPosition::BeforeColumns)
                ->bulkActions([
                    // ...
                ]);
        } else {
            return $table
                ->query(KolamGrowth::query()->where('kolam_siklus_id', $this->idSiklus)->orderBy('grow_at', 'desc'))
                ->columns([
                    Tables\Columns\TextColumn::make('grow_at')
                        ->dateTime()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('avg_weight')
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('avg_length')
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('mortality')
                        ->numeric()
                        ->sortable(),
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    
                ], position: ActionsPosition::BeforeColumns)
                ->bulkActions([
                    // ...
                ]);
        }
    }

    public function switchToGrowth($idSiklus)
    {
        
        $this->idSiklus = $idSiklus;
        $this->containerSiklus = false;
        $this->containerGrowth = true;
        $this->resetTable(); // penting untuk mereset state tabel
        $this->getKolamSiklus();
        $this->getKolamGrowth();
    }

    public function switchToSiklus()
    {
        $this->containerSiklus = true;
        $this->containerGrowth = false;
        $this->resetTable();
    }
}
