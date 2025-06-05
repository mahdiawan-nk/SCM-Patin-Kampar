<x-filament::widget>
    <x-filament::card class="bg-white shadow-md rounded-lg p-6">
        {{-- Ringkasan Produksi --}}
        <section class="mb-10">
            <h2
                class="text-3xl font-semibold text-gray-900 mb-6 border-b-2 border-gray-300 pb-2 flex items-center gap-3">
                <x-heroicon-o-chart-bar class="w-7 h-7 text-gray-700" />
                Ringkasan Produksi
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach (['harian' => 'Panen Harian', 'mingguan' => 'Panen Mingguan', 'bulanan' => 'Panen Bulanan'] as $key => $label)
                    <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">{{ $label }}</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $totalPanen[$key] }} kg</div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Ringkasan Penjualan --}}
        <section class="mb-10">
            <h2
                class="text-3xl font-semibold text-gray-900 mb-6 border-b-2 border-gray-300 pb-2 flex items-center gap-3">
                <x-heroicon-o-currency-dollar class="w-7 h-7 text-gray-700" />
                Ringkasan Penjualan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">Pendapatan Harian</div>
                    <div class="text-2xl font-extrabold text-gray-900">
                        Rp{{ number_format($pendapatan['harian'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">Pendapatan Bulanan</div>
                    <div class="text-2xl font-extrabold text-gray-900">
                        Rp{{ number_format($pendapatan['bulanan'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </section>

        {{-- Produk Terlaris --}}
        <section class="mb-10">
            <h2
                class="text-3xl font-semibold text-gray-900 mb-6 border-b-2 border-gray-300 pb-2 flex items-center gap-3">
                <x-heroicon-o-star class="w-7 h-7 text-gray-700" />
                Produk Terlaris
            </h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700 pl-5 text-lg font-medium">
                @foreach ($produkTerlaris as $produk)
                    <li>
                        {{ $produk['produk'] }}
                        <span class="font-extrabold text-gray-900">- {{ $produk['jumlah'] }} kg</span>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Logistik --}}
        <section>
            <h2
                class="text-3xl font-semibold text-gray-900 mb-6 border-b-2 border-gray-300 pb-2 flex items-center gap-3">
                <x-heroicon-o-truck class="w-7 h-7 text-gray-700" />
                Logistik
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">Pengiriman dalam Proses</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $pengiriman['dalamProses'] }}</div>
                </div>
                <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">Armada Tersedia</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $pengiriman['armadaTersedia'] }}</div>
                </div>
                <div class="p-5 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="text-sm text-gray-500 mb-2 font-medium tracking-wide">Armada Tidak Tersedia</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $pengiriman['armadaTidakTersedia'] }}</div>
                </div>
            </div>
        </section>
    </x-filament::card>
</x-filament::widget>
