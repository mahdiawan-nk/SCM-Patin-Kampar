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
use App\Models\KolamMonitoring;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use App\Models\KolamBudidaya;

class MonitoringKolam extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = KolamBudidayaResource::class;

    protected static string $view = 'filament.resources.kolam-budidaya-resource.pages.monitoring-kolam';

    public $record;
    public ?array $data = [];
    public $buttonCreate = false;
    public $formCreate = false;
    public $dataKolam = [];
    public $dataMonitoring = [];
    public function mount($record)
    {
        $this->record = $record;
        $this->form->fill([
            'kolam_budidaya_id' => $this->record
        ]);
        $this->dataKolam = KolamBudidaya::find($this->record);
        $this->dataMonitoring = KolamMonitoring::where('kolam_budidaya_id', $this->record)->latest()->first();
    }

    public function pollingMonitoring(): void
    {
        $this->dataMonitoring = KolamMonitoring::where('kolam_budidaya_id', $this->record)->latest()->first();
    }

    public function openFormCreate(): void
    {
        $this->formCreate = true;
    }

    public function closeFormCreate(): void
    {
        $this->formCreate = false;
        $this->form->fill([
            'kolam_budidaya_id' => $this->record
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kolam Budidaya Monitoring')
                    ->schema([
                        Forms\Components\Hidden::make('kolam_budidaya_id'),
                        Forms\Components\DateTimePicker::make('tgl_monitoring')
                            ->columnSpan(3)
                            ->native(false),
                        Forms\Components\TextInput::make('temperature')
                            ->columnSpan(2)
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
                    ])->columns(7)


            ])
            ->statePath('data');
    }

    public function create(): void
    {
        KolamMonitoring::create($this->form->getState());
        $this->form->fill([
            'kolam_budidaya_id' => $this->record
        ]);
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(KolamMonitoring::query()->where('kolam_budidaya_id', $this->record)->orderBy('tgl_monitoring', 'desc'))
            ->columns([
                TextColumn::make('tgl_monitoring')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('temperature')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('Suhu (°C)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ph')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->label('pH')
                    ->numeric()
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
