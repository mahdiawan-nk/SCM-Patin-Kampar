<x-filament-panels::page>
    @if ($formCreate)
        <form wire:submit="create">
            {{ $this->form }}

            <button type="submit"
                class="mt-3 px-4 py-2 text-sm text-white duration-150 bg-indigo-600 rounded-md hover:bg-indigo-700 active:shadow-lg">
                Submit
            </button>
            <button type="button" wire:click="closeFormCreate"
                class="mt-3 px-4 py-2 text-sm text-stone-900 duration-150 bg-stone-100 rounded-md hover:bg-gray-200 active:shadow-lg">
                Cancel
            </button>
        </form>
    @endif
    @if (!$this->formCreate)
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
                    <div class="max-w-2xl">
                        <h3 class="text-gray-800 text-3xl font-semibold sm:text-4xl">
                            Statistik Data Monitoring
                        </h3>
                    </div>
                    <div class="flex-none mt-6 md:mt-0 lg:mt-6">
                        <ul class="inline-grid gap-y-8 gap-x-14 grid-cols-3">
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold">{{ $dataMonitoring->temperature }}</h4>
                                <p class="mt-3 font-medium" >Suhu Air</p>
                            </li>
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold" >{{ $dataMonitoring->ph }}</h4>
                                <p class="mt-3 font-medium" >pH Air</p>
                            </li>
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold" >{{ $dataMonitoring->tds }}</h4>
                                <p class="mt-3 font-medium" >TDS Air</p>
                            </li>
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold" >{{ $dataMonitoring->amonia }}</h4>
                                <p class="mt-3 font-medium" >Amonia</p>
                            </li>
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold" >{{ $dataMonitoring->nitrite }}</h4>
                                <p class="mt-3 font-medium" >Nitrite</p>
                            </li>
                            <li>
                                <h4 class="text-4xl text-indigo-600 font-semibold" >{{ $dataMonitoring->nitrate }}</h4>
                                <p class="mt-3 font-medium" >Nitrate</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <hr>
        <div class="mt-3 w-full flex justify-end gap-3">

            <button wire:click="openFormCreate"
                class="px-4 py-2 text-sm text-white duration-150 bg-indigo-600 rounded-md hover:bg-indigo-700 active:shadow-lg">
                Create Data Monitoring
            </button>
            <a href="{{ route('filament.panels.resources.kolam-budidayas.index') }}"
                class="px-4 py-2 text-sm text-white duration-150 bg-amber-300 rounded-md hover:bg-amber-500 active:shadow-lg">
                Kembali
            </a>


        </div>
        {{ $this->table }}
    @endif
</x-filament-panels::page>
