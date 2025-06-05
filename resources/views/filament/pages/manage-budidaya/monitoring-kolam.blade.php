<x-filament-panels::page>
    <div>
        <div class="max-w-full mx-auto mt-1 text-gray-600">
            <div class="flex items-center justify-between text-sm text-gray-600 font-medium">
                <button class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50"
                    wire:click="goToPage({{ $currentPage - 1 }})" @disabled($currentPage === 1)
                    wire:loading.attr="disabled" wire:target="goToPage">
                    <x-heroicon-o-chevron-left class="inline-block w-4 h-4" />
                </button>
                <input type="text" wire:model.live="search" placeholder="Cari kolam..."
                    class="w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <button class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50"
                    wire:click="goToPage({{ $currentPage + 1 }})" @disabled($currentPage === $this->totalPages)
                    wire:loading.attr="disabled" wire:target="goToPage">
                    <x-heroicon-o-chevron-right class="inline-block w-4 h-4" />
                </button>
            </div>
        </div>

        <section class="py-1">
            <div class="w-full" wire:loading wire:target="search,goToPage">
                <ul
                    class="mt-1 flex overflow-x-auto gap-4 px-1 snap-x snap-mandatory sm:grid sm:grid-cols-3 sm:gap-8 sm:overflow-visible sm:px-0 sm:snap-none">
                    @for ($i = 0; $i < 3; $i++)
                        <li
                            class="snap-start shrink-0 w-[95%] sm:w-auto sm:shrink flex flex-col justify-between min-h-full rounded-xl border bg-white shadow-md hover:shadow-lg transition duration-300">
                            <div class="p-5 space-y-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="h-5 bg-gray-200 rounded w-1/2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-16"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-sm">
                                    @for ($j = 0; $j < 4; $j++)
                                        <div class="flex flex-col space-y-1">
                                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                            <div class="h-4 bg-gray-300 rounded w-full"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="border-t px-5 py-3 text-right">
                                <div class="h-4 bg-gray-200 rounded w-1/3 ml-auto"></div>
                            </div>
                        </li>
                    @endfor
                </ul>
            </div>
            <div class="max-w-full" wire:loading.remove wire:target="search,goToPage">
                <ul
                    class="mt-1 flex overflow-x-auto gap-4 px-1 snap-x snap-mandatory sm:grid sm:grid-cols-3 sm:gap-8 sm:overflow-visible sm:px-0 sm:snap-none">
                    @foreach ($this->paginatedData as $data)
                        <li wire:key="kolam-{{ $data['id'] }}"
                            class="snap-start shrink-0 w-[95%] sm:w-auto sm:shrink flex flex-col justify-between min-h-full rounded-xl border bg-white shadow-md hover:shadow-lg transition duration-300 {{ $data['id'] === $kolamId ? 'border-indigo-500' : '' }}">
                            <div class="p-5 space-y-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xl font-semibold text-gray-800">
                                        {{ $data['nama_kolam'] }}
                                    </h4>
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                        {{ $data['jenis_kolam'] }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-sm text-gray-700">
                                    <div class="flex flex-col">
                                        <span class="text-gray-500">Lokasi Kolam</span>
                                        <span class="font-medium">{{ $data['lokasi_kolam'] }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-500">Dimensi</span>
                                        <span class="font-medium">
                                            {{ $data['panjang'] }}m × {{ $data['lebar'] }}m ×
                                            {{ $data['kedalaman'] }}m
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-500">Volume Air</span>
                                        <span class="font-medium">{{ $data['volume_air'] }} m³</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-500">Kapasitas</span>
                                        <span class="font-medium">{{ $data['kapasitas'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t px-5 py-3 text-right">
                                <a href="javascript:void(0)" wire:click="goToMonitoring({{ $data['id'] }})"
                                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-500 font-medium transition">
                                    <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                                    Lihat Data
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>

    @if ($kolamId)
        <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        <section class="">
            <div class="max-w-full text-gray-600 gap-x-6 gap-y-4 items-stretch flex flex-col lg:flex-row">
                {{-- Detail Kolam --}}
                <div
                    class="w-full lg:flex-1 bg-white shadow rounded-xl p-6 space-y-6 border border-slate-200 flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="text-lg font-bold text-slate-800">Detail Kolam</h3>
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                            {{ $dataKolam->jenis_kolam }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700 mt-auto">
                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Nama Kolam</p>
                            <p class="font-semibold">{{ $dataKolam->nama_kolam }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Lokasi Kolam</p>
                            <p class="font-semibold">{{ $dataKolam->lokasi_kolam }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Dimensi Kolam</p>
                            <p class="font-semibold">
                                {{ $dataKolam->panjang }}m × {{ $dataKolam->lebar }}m × {{ $dataKolam->kedalaman }}m
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Volume Air</p>
                            <p class="font-semibold">{{ $dataKolam->volume_air }} m³</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Kapasitas</p>
                            <p class="font-semibold">{{ $dataKolam->kapasitas }}</p>
                        </div>
                    </div>
                </div>

                {{-- Statistik --}}
                <div class="w-full lg:flex-1 bg-white rounded-xl shadow border border-slate-200 p-6 flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="text-lg font-bold text-slate-800">
                            <x-heroicon-o-chart-bar class="w-5 h-5 inline-block mr-1 text-indigo-500" />
                            Statistik Data Monitoring
                        </h3>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-y-6 gap-x-6 mt-auto">
                        @php
                            $stats = [
                                ['label' => 'Suhu Air', 'value' => $dataMonitoring?->temperature ?? '0'],
                                ['label' => 'pH Air', 'value' => $dataMonitoring?->ph ?? '0'],
                                ['label' => 'TDS Air', 'value' => $dataMonitoring?->tds ?? '0'],
                                ['label' => 'Amonia', 'value' => $dataMonitoring?->amonia ?? '0'],
                                ['label' => 'Nitrite', 'value' => $dataMonitoring?->nitrite ?? '0'],
                                ['label' => 'Nitrate', 'value' => $dataMonitoring?->nitrate ?? '0'],
                            ];
                        @endphp

                        @foreach ($stats as $item)
                            <div>
                                <p class="text-sm text-slate-500">{{ $item['label'] }}</p>
                                <h4 class="text-3xl font-bold text-indigo-600 mt-1">{{ $item['value'] }}</h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </section>
        {{ $this->table }}
    @endif

</x-filament-panels::page>
