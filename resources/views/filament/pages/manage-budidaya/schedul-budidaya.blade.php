<x-filament-panels::page>


    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    @endpush
    <div class="w-full space-y-6 grid grid-cols-1 md:grid-cols-5 gap-4">

        {{-- FILTERS --}}
        <div class="flex flex-col gap-4 mt-4 col-span-1">

            {{-- === Mode View Switcher === --}}
            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    <button wire:click="$set('viewMode', 'grid')"
                        class="px-3 py-1.5 rounded-md text-sm border shadow-sm {{ $viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-o-square-3-stack-3d class="w-4 h-4 inline-block mr-1" />
                        Grid
                    </button>
                    <button wire:click="$set('viewMode', 'calendar')"
                        class="px-3 py-1.5 rounded-md text-sm border shadow-sm {{ $viewMode === 'calendar' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-o-calendar-days class="w-4 h-4 inline-block mr-1" />
                        Kalender
                    </button>
                </div>
            </div>

            {{-- === Search Input === --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Judul</label>
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Cari aktivitas..."
                        class="w-full px-4 py-2 pr-10 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    @if ($search)
                        <button wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-red-500">
                            <x-heroicon-o-x-circle class="w-5 h-5" />
                        </button>
                    @else
                        <div class="absolute inset-y-0 right-2 flex items-center text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </div>
                    @endif
                </div>
            </div>

            {{-- === Jenis Aktivitas (Checkbox) === --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aktivitas</label>
                <div class="space-y-1">
                    @foreach (['seed' => 'Penebaran Benih', 'feed' => 'Pakan', 'treatment' => 'Perawatan', 'harvest' => 'Panen'] as $value => $label)
                        <label class="flex items-center space-x-2 text-sm text-gray-700">
                            <input type="checkbox" wire:model.live="filterActivity" value="{{ $value }}"
                                class="text-blue-600 border-gray-300 rounded shadow-sm focus:ring-blue-500" />
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- === Kolam Budidaya === --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kolam Budidaya</label>
                <select wire:model.live="filterKolam"
                    class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Semua Kolam</option>
                    @foreach ($listKolamBudidaya as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- === Status (Radio) === --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="space-y-1">
                    @foreach (['' => 'Semua', 'done' => 'Selesai', 'not_done' => 'Belum Selesai'] as $val => $label)
                        <label class="flex items-center space-x-2 text-sm text-gray-700">
                            <input type="radio" wire:model.live="filterStatus" value="{{ $val }}"
                                class="text-blue-600 border-gray-300 focus:ring-blue-500" />
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- === Tombol Add === --}}
            <div>
                <button wire:click="openModalAdd"
                    class="w-full mt-4 inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md shadow-sm">
                    <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                    Tambah Aktivitas
                </button>
            </div>
        </div>

        {{-- TODO LIST --}}
        <div wire:show="viewMode == 'grid'" class=" grid grid-cols-1 sm:grid-cols-1 gap-4 col-span-4">
            @forelse ($todos as $todo)
                <div
                    class="max-w-full flex flex-col bg-white border border-gray-200 border-t-4 {{ $todo->is_done ? 'border-green-500' : 'border-blue-500' }} shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70">
                    <div class="w-full  flex flex-col items-start justify-start gap-4 ">
                        <div class="w-full flex flex-row items-start justify-start gap-4 p-4 md:p-5 md:pb-0">
                            <input type="checkbox" wire:click="toggleDone({{ $todo->id }})"
                                {{ $todo->is_done ? 'checked' : '' }}
                                class="form-checkbox h-5 w-5 text-green-600 border-gray-300 focus:ring-green-500 mt-1" />
                            <div class="w-full">
                                <div class="flex flex-row items-center justify-between gap-x-2">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                                        </svg>
                                        {{ $todo->kolam_budidaya->nama_kolam }}
                                    </h3>
                                    @if ($todo->is_done)
                                        <span id="badge-dismiss-green"
                                            class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded-sm dark:bg-green-900 dark:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4 font-medium">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                                <div class="inline-flex flex-wrap gap-2 mt-3">
                                    <div>
                                        <span
                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg dark:bg-neutral-500/20 dark:text-neutral-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>
                                            {{ $todo->schedule_at }}
                                        </span>
                                    </div>

                                    <div>
                                        <span
                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-lg dark:bg-red-500/10 dark:text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            {{ $todo->reminder_at }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg dark:bg-blue-500/10 dark:text-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="shrink-0 size-3"
                                                width="24" height="24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 6h.008v.008H6V6Z" />
                                            </svg>
                                            {{ $todo->activity_type }}
                                        </span>
                                    </div>
                                </div>
                                <dl
                                    class="px-1 mt-3 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700 ">
                                    <div class="flex flex-col pb-3">
                                        <dt class="mb-1 text-sm font-bold ">Kegiatan:</dt>
                                        <dd class="text-xs font-medium">{{ $todo->title }}</dd>
                                    </div>
                                    <div class="flex flex-col pt-3">
                                        <dt class="mb-1 text-sm font-bold ">Catatan:</dt>
                                        <dd class="text-xs font-medium">{{ $todo->note }}</dd>
                                    </div>
                                </dl>

                            </div>
                        </div>

                        <div x-data="{}"
                            class="w-full border-t py-2 text-right bg-stone-200 p-4 md:p-5 gap-4 flex flex-row justify-between rounded-b-xl">
                            <a href="javascript:void(0)" wire:click="edit({{ $todo->id }})"
                                class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-500 font-medium transition">
                                <x-heroicon-o-pencil class="w-4 h-4 mr-1" />
                                Edit Schedul
                            </a>
                            <a href="javascript:void(0)" @click="$wire.deleteId = {{ $todo->id }}; $wire.modalDelete = true"
                                class="inline-flex items-center text-sm text-red-600 hover:text-red-500 font-medium transition">
                                <x-heroicon-o-trash class="w-4 h-4 mr-1" />
                                Hapus Schedul
                            </a>
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center text-gray-400 py-10">
                    <x-heroicon-o-clipboard-document-list class="mx-auto w-10 h-10 mb-3 text-gray-300" />
                    <p class="text-sm">Belum ada aktivitas yang dijadwalkan.</p>
                </div>
            @endforelse
        </div>
        <div wire:ignore class="mt-6 col-span-4 bg-gray-100 p-2 shadow rounded" wire:show="viewMode == 'calendar'">
            <div id="budidaya-calendar"></div>
        </div>
    </div>

    <div wire:show="showModal" x-cloak x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false"
        class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-sm md:inset-0 sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
        <!-- Modal Dialog -->
        <div x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
            class="flex w-[100%] sm:w-[50%] flex-col gap-4 overflow-hidden  text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">

            <div
                class="max-w-full flex flex-col bg-white border border-gray-200 border-t-4 {{ $dataModal?->is_done ? 'border-green-500' : 'border-blue-500' }} shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70">
                <div class="w-full  flex flex-col items-start justify-start gap-4 ">
                    <div class="w-full flex flex-row items-start justify-start gap-4 p-4 md:p-5 md:pb-0">

                        <div class="w-full min-h-64">
                            <div class="flex flex-row items-center justify-between gap-x-2">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                                    </svg>
                                    {{ $dataModal?->kolam_budidaya->nama_kolam }}
                                </h3>
                                @if ($dataModal?->is_done)
                                    <span id="badge-dismiss-green"
                                        class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 font-medium">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        Selesai
                                    </span>
                                @endif
                            </div>
                            <div class="inline-flex flex-wrap gap-2 mt-3">
                                <div>
                                    <span
                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg dark:bg-neutral-500/20 dark:text-neutral-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                        </svg>
                                        {{ $dataModal?->schedule_at }}
                                    </span>
                                </div>

                                <div>
                                    <span
                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-lg dark:bg-red-500/10 dark:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ $dataModal?->reminder_at }}
                                    </span>
                                </div>
                                <div>
                                    <span
                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg dark:bg-blue-500/10 dark:text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="shrink-0 size-3"
                                            width="24" height="24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 6h.008v.008H6V6Z" />
                                        </svg>
                                        {{ $dataModal?->activity_type }}
                                    </span>
                                </div>
                            </div>
                            <dl
                                class="px-1 mt-3 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700 ">
                                <div class="flex flex-col pb-3">
                                    <dt class="mb-1 text-sm font-bold ">Kegiatan:</dt>
                                    <dd class="text-xs font-medium">{{ $dataModal?->title }}</dd>
                                </div>
                                <div class="flex flex-col pt-3">
                                    <dt class="mb-1 text-sm font-bold ">Catatan:</dt>
                                    <dd class="text-xs font-medium">{{ $dataModal?->note }}</dd>
                                </div>
                            </dl>

                        </div>
                        <button x-data="" @click="$wire.showModal = false; $wire.dataModal = null" type="button"
                            aria-label="close modal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                                stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-filament::modal id="edit-user" width="lg">
        <form wire:submit="create">
            {{ $this->form }}

            <div class="flex items-center gap-2 mt-4">
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
                <x-filament::button type="button" wire:click="closeModalAdd">
                    Cancel
                </x-filament::button>
            </div>
        </form>
    </x-filament::modal>

    
    <div wire:show="modalDelete === true" class="fixed inset-0 w-full h-full z-50">
        <div class="fixed inset-0 w-full h-full bg-black opacity-40" ></div>

        <div class="fixed top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] px-4 w-full max-w-lg z-50">
            <div class="bg-white rounded-md shadow-lg px-4 py-6 sm:flex">
                <div class="flex items-center justify-center flex-none w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <!-- SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="mt-2 text-center sm:ml-4 sm:text-left">
                    <h2 class="text-lg font-medium text-gray-800">Are you sure?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">
                        Data yang telah dihapus tidak dapat dikembalikan. Anda yakin ingin menghapus data ini?
                    </p>
                    <div class="items-center gap-2 mt-3 text-sm sm:flex" x-data="">
                        <button wire:click="delete"
                            class="w-full mt-2 p-2.5 flex-1 text-white bg-red-600 rounded-md ring-offset-2 ring-red-600 focus:ring-2">
                            Delete
                        </button>
                        <button  aria-label="Close" @click="$wire.deleteId = null; $wire.modalDelete = false"
                            class="w-full mt-2 p-2.5 flex-1 text-gray-800 rounded-md border ring-offset-2 ring-indigo-600 focus:ring-2">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                let calendar;
                const calendarEl = document.getElementById('budidaya-calendar');

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek',
                    },
                    events: @json($this->calendarEvents),
                    eventClick: function(info) {
                        const props = info.event.extendedProps;
                        Livewire.dispatch('show-modal', {
                            id: info.event.id
                        });
                    }
                });

                calendar.render();
                if (calendar) {
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 10);
                }
                Livewire.on('calendarViewShown', () => {
                    if (calendar) {
                        setTimeout(() => {
                            calendar.updateSize();
                        }, 10);
                    }
                });
                Livewire.on('calendarViewRefresh', () => {
                    Livewire.on('calendarViewRefreshEvents', (data) => {
                        calendar.removeAllEvents();
                        calendar.addEventSource(data[0]);
                        setTimeout(() => {
                            calendar.updateSize();
                        }, 10);
                    });
                    // calendar.re; // Ambil ulang event dari Livewire
                });
            });
        </script>
    @endpush
</x-filament-panels::page>
