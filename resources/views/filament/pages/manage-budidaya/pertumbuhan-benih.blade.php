<x-filament-panels::page>
    <div>
        <div class="max-w-full mx-auto mt-1 text-gray-600">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm text-gray-600 font-medium">

                {{-- Tombol Sebelumnya --}}
                <div class="flex justify-between sm:justify-start gap-2">
                    <button class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50"
                        wire:click="goToPage({{ $currentPage - 1 }})" @disabled($currentPage === 1)
                        wire:loading.attr="disabled" wire:target="goToPage">
                        <x-heroicon-o-chevron-left class="inline-block w-4 h-4" />
                    </button>

                    {{-- Tombol Selanjutnya di Mobile --}}
                    <button class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50 sm:hidden"
                        wire:click="goToPage({{ $currentPage + 1 }})" @disabled($currentPage === $this->totalPages)
                        wire:loading.attr="disabled" wire:target="goToPage">
                        <x-heroicon-o-chevron-right class="inline-block w-4 h-4" />
                    </button>
                </div>

                {{-- Input Pencarian dan Tanggal --}}
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 sm:gap-4">
                    <div class="relative w-full sm:w-64">
                        <!-- Ikon Search -->
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                            </svg>
                        </div>

                        <!-- Input -->
                        <input type="text" wire:model.live="search" placeholder="Cari kolam..."
                            class="w-full pl-10 pr-10 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">

                        <!-- Tombol Clear -->
                        @if ($search)
                            <button type="button" wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-red-500"
                                title="Hapus pencarian">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="relative w-full sm:w-64">
                        <input type="date" wire:model.live="date"
                            class="w-full pr-10 pl-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">

                        @if ($date)
                            <!-- Tombol clear (X icon) -->
                            <button type="button" wire:click="$set('date', null)"
                                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-red-500"
                                title="Hapus filter tanggal">
                                <!-- Heroicons X (optional: bisa ganti dengan lucide icon) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="relative w-full sm:w-64">
                        <select id="os" name="os" wire:model.live="status"
                            class="w-full pr-10 pl-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                            {{-- <option value="">Semua</option> --}}
                            <option value="berjalan">Berjalan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                </div>

                {{-- Tombol Selanjutnya di Desktop --}}
                <button class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50 hidden sm:inline-block"
                    wire:click="goToPage({{ $currentPage + 1 }})" @disabled($currentPage === $this->totalPages)
                    wire:loading.attr="disabled" wire:target="goToPage">
                    <x-heroicon-o-chevron-right class="inline-block w-4 h-4" />
                </button>
            </div>
        </div>
        <section class="py-1">
            <div class="w-full" wire:loading wire:target="search,goToPage,date,status">
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
            <div class="max-w-full" wire:loading.remove wire:target="search,goToPage,date,status">
                @if (count($this->paginatedData) === 0)
                    <div
                        class="mt-10 flex flex-col items-center justify-center text-gray-500 border border-dashed rounded-xl py-5">
                        <!-- Ikon ilustrasi kosong -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-1a3 3 0 016 0v1m-3 4a4 4 0 100-8 4 4 0 000 8zm6-10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v6m16 0H3" />
                        </svg>

                        <!-- Pesan -->
                        <p class="text-base font-medium">Tidak ada data</p>
                        <p class="text-sm text-gray-400">Coba sesuaikan pencarian atau filter Anda</p>
                    </div>
                @else
                    <ul
                        class="mt-1 flex overflow-x-auto gap-4 px-1 snap-x snap-mandatory sm:grid sm:grid-cols-3 sm:gap-8 sm:overflow-visible sm:px-0 sm:snap-none">
                        @foreach ($this->paginatedData as $data)
                            <li wire:key="kolam-{{ $data['id'] }}"
                                class="snap-start shrink-0 w-[95%] sm:w-auto sm:shrink flex flex-col justify-between min-h-full rounded-xl border bg-white shadow-md hover:shadow-lg transition duration-300 {{ $data['id'] === $siklusId ? 'border-indigo-500' : '' }}">
                                <div class="p-5 space-y-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xl font-semibold text-gray-800">
                                            {{ $data['kolam_budidaya']['nama_kolam'] }}
                                        </h4>
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                            {{ $data['kolam_budidaya']['jenis_kolam'] }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-sm text-gray-700">
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Jenis Benih</span>
                                            <span class="font-medium">{{ $data['strain'] }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Tanggal Penebaran</span>
                                            <span class="font-medium">
                                                {{ Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}
                                            </span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">jumlah Awal Benih</span>
                                            <span class="font-medium">{{ $data['initial_stock'] }} m³</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Berat rata-rata awal</span>
                                            <span class="font-medium">{{ $data['initial_avg_weight'] }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Kepadatan penebaran (ekor/m²)</span>
                                            <span class="font-medium">{{ $data['stocking_density'] }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Status</span>
                                            <span class="font-medium ">
                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-medium {{ $data['status'] == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} rounded-full">
                                                    {{ $data['status'] }}
                                                </span>

                                            </span>
                                        </div>
                                        @if ($data['status'] == 'berjalan')
                                            <div
                                                class="flex items-start sm:items-center col-span-2 bg-yellow-50 border border-yellow-300 text-yellow-800 text-sm rounded-lg p-3 space-x-2">
                                                <x-heroicon-o-exclamation-triangle
                                                    class="w-5 h-5 mt-0.5 sm:mt-0 text-yellow-500 flex-shrink-0" />
                                                <span class="font-medium text-xs">
                                                    Siklus budidaya masih aktif/produktif — ikan masih dalam proses
                                                    pembesaran.
                                                </span>
                                            </div>
                                        @else
                                            <div
                                                class="flex items-start sm:items-center col-span-2 bg-green-50 border border-green-300 text-green-800 text-sm rounded-lg p-3 space-x-2">
                                                <x-heroicon-o-check-circle
                                                    class="w-5 h-5 mt-0.5 sm:mt-0 text-green-500 flex-shrink-0" />
                                                <span class="font-medium text-xs">
                                                    Siklus budidaya telah selesai — ikan sudah dipanen.
                                                </span>
                                            </div>
                                        @endif
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
                @endif
            </div>
        </section>
    </div>
    @if ($siklusId)
        <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        <section class="">
            <div class="max-w-full text-gray-600 gap-x-6 gap-y-4 items-stretch flex flex-col lg:flex-row">
                {{-- Detail Kolam --}}
                <div
                    class="w-full lg:flex-1 bg-white shadow rounded-xl p-6 space-y-6 border border-slate-200 flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="text-lg font-bold text-slate-800">{{ $dataSiklus->kolam_budidaya->nama_kolam }}
                        </h3>
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                            {{ $dataSiklus->kolam_budidaya->jenis_kolam }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700 mt-auto">
                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Jenis Benih</p>
                            <p class="font-semibold">{{ $dataSiklus->strain }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Tanggal Penebaran</p>
                            <p class="font-semibold">
                                {{ Carbon\Carbon::parse($dataSiklus->start_date)->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Jumlah Awal Benih</p>
                            <p class="font-semibold">
                                {{ $dataSiklus->initial_stock }} m3
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Berat Rata-Rata Awal</p>
                            <p class="font-semibold">{{ $dataSiklus->initial_avg_weight }} m³</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-slate-500 font-medium">Kepadatan Penebaran (ekor/m²)</p>
                            <p class="font-semibold">{{ $dataSiklus->stocking_density }}</p>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium ">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium {{ $dataSiklus->status == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} rounded-full">
                                    {{ $dataSiklus->status }}
                                </span>

                            </span>
                        </div>
                        @if ($dataSiklus->status == 'berjalan')
                            <div
                                class="flex items-start sm:items-center col-span-2 bg-yellow-50 border border-yellow-300 text-yellow-800 text-sm rounded-lg p-3 space-x-2">
                                <x-heroicon-o-exclamation-triangle
                                    class="w-5 h-5 mt-0.5 sm:mt-0 text-yellow-500 flex-shrink-0" />
                                <span class="font-medium text-xs">
                                    Siklus budidaya masih aktif/produktif — ikan masih dalam proses
                                    pembesaran.
                                </span>
                            </div>
                        @else
                            <div
                                class="flex items-start sm:items-center col-span-2 bg-green-50 border border-green-300 text-green-800 text-sm rounded-lg p-3 space-x-2">
                                <x-heroicon-o-check-circle
                                    class="w-5 h-5 mt-0.5 sm:mt-0 text-green-500 flex-shrink-0" />
                                <span class="font-medium text-xs">
                                    Siklus budidaya telah selesai — ikan sudah dipanen.
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Statistik --}}

            </div>

        </section>
        {{ $this->table }}
    @endif
</x-filament-panels::page>
