<?php

namespace App\Filament\Pages\ManageBudidaya;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\{Textarea,TextInput,Select,Radio,DatePicker,TimePicker};
use App\Models\SchedulBudidaya as JadwalBudidaya;
use App\Models\KolamBudidaya;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;

class SchedulBudidaya extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.manage-budidaya.schedul-budidaya';

    public $search = '';
    public $filterActivity = [];
    public $filterKolam = '';
    public $filterStatus = '';
    public $todos = [];
    public $viewMode = 'grid';
    public $listKolamBudidaya = [];
    public $showModal = false;
    public $dataModal;
    public ?array $data = [];
    public $idEdit;
    public $modalDelete = false;
    public $deleteId = null;
    public function mount(): void
    {
        $this->form->fill();
        $this->listKolamBudidaya = KolamBudidaya::pluck('nama_kolam', 'id')->toArray();
        $this->loadTodos();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kolam_budidaya_id')
                    ->relationship('kolam_budidaya', 'nama_kolam')
                    ->columnSpanFull()
                    ->required(),
                Radio::make('activity_type')
                    ->required()
                    ->columnSpanFull()
                    ->options([
                        'seed' => 'Seeding',
                        'feed' => 'Feeding',
                        'treatment' => 'Treatment',
                        'harvest' => 'Harvest',
                    ])
                    ->descriptions([
                        'seed' => 'Penebaran Benih',
                        'feed' => 'Pemberian Makanan',
                        'treatment' => 'Pemberian Obat dan Penanangan',
                        'harvest' => 'Pengumpulan Hasil (Panen)',
                    ])
                    ->columns(2),
                Textarea::make('title') // Text::make('note')
                    ->columnSpanFull(),
                DatePicker::make('schedule_at')
                    ->required(),
                TimePicker::make('reminder_at')
                    ->required(),
                Textarea::make('note') // Forms\Components\Text::make('note')
                    ->columnSpanFull(),

            ])
            ->columns(2)
            ->statePath('data')
            ->model(JadwalBudidaya::class);
    }

    public function create(): void
    {
        if ($this->idEdit) {
            JadwalBudidaya::where('id', $this->idEdit)->update($this->form->getState());
        } else {
            JadwalBudidaya::create($this->form->getState());
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'edit-user');
        $this->loadTodos();
    }

    public function edit($id)
    {
        $this->openModalAdd();
        $this->idEdit = $id;
        $dataActivity = JadwalBudidaya::with('kolam_budidaya')->findOrFail($id);
        $this->form->fill([
            'kolam_budidaya_id' => $dataActivity->kolam_budidaya_id,
            'activity_type' => $dataActivity->activity_type,
            'title' => $dataActivity->title,
            'schedule_at' => $dataActivity->schedule_at,
            'reminder_at' => $dataActivity->reminder_at,
            'note' => $dataActivity->note
        ]);
    }

    public function updatedFilterActivity($vvalue): void
    {
        $this->loadTodos();
        if ($this->viewMode === 'calendar') {
            $this->dispatch('calendarViewRefresh'); // Dispatch ke JS
        }
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'filterKolam', 'filterStatus'])) {
            $this->loadTodos();

            if ($this->viewMode === 'calendar') {
                $this->dispatch('calendarViewRefresh'); // Dispatch ke JS
            }
        }
    }
    #[On('show-modal')]
    public function showModal($id): void
    {
        $this->dataModal = JadwalBudidaya::with('kolam_budidaya')->findOrFail($id);
        $this->showModal = true;
    }

    public function getCalendarEventsProperty()
    {
        $data = JadwalBudidaya::with('kolam_budidaya')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filterActivity,
                fn($q) =>
                $q->whereIn('activity_type', $this->filterActivity)
            )
            ->when(
                $this->filterKolam,
                fn($q) =>
                $q->where('kolam_budidaya_id', $this->filterKolam)
            )
            ->when(
                $this->filterStatus === 'done',
                fn($q) =>
                $q->where('is_done', true)
            )
            ->when(
                $this->filterStatus === 'not_done',
                fn($q) =>
                $q->where('is_done', false)
            )
            ->orderBy('schedule_at', 'desc')
            ->get();

        $listEvent = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title . ' - ' . ucfirst($item->activity_type),
                'start' => $item->schedule_at,
                'color' => $item->is_done ? '#22c55e' : '#f59e0b',
                'extendedProps' => [
                    'reminder' => $item->reminder_at,
                    'kolam' => $item->kolam_budidaya->nama_kolam ?? '-',
                    'status' => $item->is_done ? 'Selesai' : 'Belum Selesai',
                ],
            ];
        });
        if ($this->viewMode === 'calendar') {
            $this->dispatch('calendarViewRefreshEvents', $listEvent); // Dispatch ke JS
        }
        return $listEvent;
    }

    public function updatedViewMode($value)
    {
        if ($value === 'calendar') {
            $this->dispatch('calendarViewShown');
        }
    }

    public function loadTodos(): void
    {
        $this->todos = JadwalBudidaya::with('kolam_budidaya')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filterActivity,
                fn($q) =>
                $q->whereIn('activity_type', $this->filterActivity)
            )
            ->when(
                $this->filterKolam,
                fn($q) =>
                $q->where('kolam_budidaya_id', $this->filterKolam)
            )
            ->when(
                $this->filterStatus === 'done',
                fn($q) =>
                $q->where('is_done', true)
            )
            ->when(
                $this->filterStatus === 'not_done',
                fn($q) =>
                $q->where('is_done', false)
            )
            ->limit(3)
            ->orderBy('schedule_at', 'desc')
            ->get();
    }

    public function openModalAdd()
    {
        $this->dispatch('open-modal', id: 'edit-user');
    }

    public function closeModalAdd()
    {
        $this->dispatch('close-modal', id: 'edit-user');
    }

    public function delete()
    {
        JadwalBudidaya::find($this->deleteId)->delete();
        Notification::make()
            ->title('Deleted successfully')
            ->success()
            ->send();
        $this->loadTodos();
        $this->modalDelete = false;
        $this->deleteId = null;
    }
    public function toggleDone($id)
    {
        $todo = JadwalBudidaya::findOrFail($id);
        $todo->is_done = !$todo->is_done;
        $todo->save();

        $this->loadTodos(); // refresh after toggle
    }
}
