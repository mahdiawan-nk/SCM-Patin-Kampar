<x-filament-panels::page>
    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            @if ($containerSiklus)
                Data Siklus Kolam
            @else
                Data Siklus Growth Kolam
            @endif

        </h1>
        <div class="flex gap-2">
            @if ($containerSiklus)
                <x-filament::button wire:click="openFormCreate('siklus')">Tambah Kolam Siklus</x-filament::button>
                <x-filament::button href="{{ route('filament.panels.resources.kolam-budidayas.index') }}" tag="a"
                    color="gray">Kembali Kolam Budidaya</x-filament::button>
            @elseif ($containerGrowth)
                <x-filament::button wire:click="openFormCreate('growth')">Tambah Kolam Growth</x-filament::button>
                <x-filament::button wire:click="goToSiklus" color="gray">Kembali Kolam Siklus</x-filament::button>
            @endif
        </div>
    </div>
    @if ($containerSiklus)
        <section class="py-2">
            <div class="max-w-screen-xl mx-auto px-2 text-gray-600 gap-x-12 items-start lg:flex md:px-2">
                <div class="lg:block hidden w-full max-w-md bg-slate-50 shadow-sm rounded-lg p-4 space-y-2">
                    <h3 class="text-lg font-bold text-slate-700 border-b pb-2 mb-2">Detail Kolam</h3>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-1 font-medium text-slate-600">Nama Kolam</div>
                        <div class="col-span-2">: {{ $dataKolam->nama_kolam }}</div>

                        <div class="col-span-1 font-medium text-slate-600">Lokasi Kolam</div>
                        <div class="col-span-2">: {{ $dataKolam->lokasi_kolam }}</div>

                        <div class="col-span-1 font-medium text-slate-600">Dimensi</div>
                        <div class="col-span-2">: {{ $dataKolam->panjang }}m × {{ $dataKolam->lebar }}m ×
                            {{ $dataKolam->kedalaman }}m</div>

                        <div class="col-span-1 font-medium text-slate-600">Volume Air</div>
                        <div class="col-span-2">: {{ $dataKolam->volume_air }} m³</div>

                        <div class="col-span-1 font-medium text-slate-600">Kapasitas</div>
                        <div class="col-span-2">: {{ $dataKolam->kapasitas }}</div>

                        <div class="col-span-1 font-medium text-slate-600">Jenis Kolam</div>
                        <div class="col-span-2">: {{ $dataKolam->jenis_kolam }}</div>
                    </div>
                </div>
                <div class="mt-6 gap-12 sm:mt-0 md:flex lg:block">

                </div>
            </div>
        </section>
    @elseif ($containerGrowth)
        <section class="py-2">
            <div class="max-w-screen-xl mx-auto px-2 text-gray-600 gap-x-12 items-start lg:flex md:px-2">
                <div class="lg:block hidden w-full max-w-xl bg-slate-50 shadow-sm rounded-lg p-4 space-y-2">
                    <h3 class="text-lg font-bold text-slate-700 border-b pb-2 mb-2">Detail Awal Penebaran Benih</h3>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2 font-medium text-slate-600">Nama Kolam</div>
                        <div class="col-span-1">: {{ $dataKolam->nama_kolam }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Jenis Ikan</div>
                        <div class="col-span-1">: {{ $dataSiklus->strain }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Tanggal Tebar Benih</div>
                        <div class="col-span-1">:
                            {{ Carbon\Carbon::parse($dataSiklus->start_date)->translatedFormat('d F Y') }} </div>

                        <div class="col-span-2 font-medium text-slate-600">Jumlah Benih Awal Tebar</div>
                        <div class="col-span-1">: {{ $dataSiklus->initial_stock }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Berat rata-rata benih (gram)</div>
                        <div class="col-span-1">: {{ $dataSiklus->initial_avg_weight }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Kepadatan tebar (ekor/m²)</div>
                        <div class="col-span-1">: {{ $dataSiklus->stocking_density }}</div>
                    </div>
                </div>
                <div class="lg:block hidden w-full max-w-xl bg-slate-50 shadow-sm rounded-lg p-4 space-y-2">
                    <h3 class="text-lg font-bold text-slate-700 border-b pb-2 mb-2">Detail Siklus Pertumbuhan Benih</h3>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2 font-medium text-slate-600">Nama Kolam</div>
                        <div class="col-span-1">: {{ $dataKolam->nama_kolam }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Jenis Ikan</div>
                        <div class="col-span-1">: {{ $dataSiklus->strain }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Tanggal Pertumbuhan</div>
                        <div class="col-span-1">:
                            {{ $dataGrowth && $dataGrowth->grow_at ? \Carbon\Carbon::parse($dataGrowth->grow_at)->translatedFormat('d F Y') : '-' }}
                        </div>

                        <div class="col-span-2 font-medium text-slate-600">Berat Rata-rata</div>
                        <div class="col-span-1">: {{ $dataGrowth?->avg_weight ?? '-' }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Panjang Rata-rata</div>
                        <div class="col-span-1">: {{ $dataGrowth?->avg_length ?? '-' }}</div>

                        <div class="col-span-2 font-medium text-slate-600">Total Kematian</div>
                        <div class="col-span-1">: {{ $dataGrowth?->mortality ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{ $this->table }}

    @if ($containerSiklus && $formCreateSiklus)
        <div class="fixed inset-0 w-full h-full bg-black bg-opacity-40 z-50" @click.outside="open = false">
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl mx-auto px-4">
                <form wire:submit="create" class="bg-white rounded-md shadow-lg">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h2 class="text-lg font-medium text-gray-800">Tambah
                            {{ $containerSiklus ? 'Siklus' : 'Growth' }}
                        </h2>
                        <button class="p-2 text-gray-400 rounded-md hover:bg-gray-100" type="button"
                            wire:click="closeFormCreate('siklus')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-2 p-4 mt-3 text-[15.5px] leading-relaxed text-gray-500">
                        {{ $this->form }}
                    </div>
                    <div class="flex items-center gap-3 p-4 border-t">
                        <button type="submit"
                            class="px-6 py-2 text-white bg-indigo-600 rounded-md outline-none ring-offset-2 ring-indigo-600 focus:ring-2">
                            Accept
                        </button>
                        <button type="button" aria-label="Close" wire:click="closeFormCreate('siklus')"
                            class="px-6 py-2 text-gray-800 border rounded-md outline-none ring-offset-2 ring-indigo-600 focus:ring-2">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($containerGrowth && $formCreateGrowth)
        <div class="fixed inset-0 w-full h-full bg-black bg-opacity-40 z-50" @click.outside="open = false">
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl mx-auto px-4">
                <form wire:submit="createGrowth" class="bg-white rounded-md shadow-lg">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h2 class="text-lg font-medium text-gray-800">Tambah Growth

                        </h2>
                        <button class="p-2 text-gray-400 rounded-md hover:bg-gray-100" type="button"
                            wire:click="closeFormCreate('growth')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-2 p-4 mt-3 text-[15.5px] leading-relaxed text-gray-500">
                        {{ $this->form }}
                    </div>
                    <div class="flex items-center gap-3 p-4 border-t">
                        <button type="submit"
                            class="px-6 py-2 text-white bg-indigo-600 rounded-md outline-none ring-offset-2 ring-indigo-600 focus:ring-2">
                            Accept
                        </button>
                        <button type="button" aria-label="Close" wire:click="closeFormCreate('growth')"
                            class="px-6 py-2 text-gray-800 border rounded-md outline-none ring-offset-2 ring-indigo-600 focus:ring-2">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-filament-panels::page>
