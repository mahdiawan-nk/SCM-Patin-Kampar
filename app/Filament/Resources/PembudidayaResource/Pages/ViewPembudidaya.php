<?php

namespace App\Filament\Resources\PembudidayaResource\Pages;

use App\Filament\Resources\PembudidayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\RepeatableEntry;

class ViewPembudidaya extends ViewRecord
{
    protected static string $resource = PembudidayaResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                    'xl' => 6,
                    '2xl' => 8,
                ])
                    ->schema([

                        Section::make('Biodata Pembudidaya')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('nama_lengkap'),
                                TextEntry::make('nik'),
                                TextEntry::make('jenis_kelamin')
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    }),
                                TextEntry::make('tanggal_lahir')
                                    ->date('d-m-Y'),
                                TextEntry::make('no_hp'),
                                TextEntry::make('email'),
                                TextEntry::make('tgl_bergabung')
                                    ->date('d-m-Y'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'aktif' => 'success',
                                        'tidak aktif' => 'danger',
                                    }),
                                TextEntry::make('alamat_lengkap')
                                    ->columnSpanFull(),
                            ])->columns(1),

                        Tabs::make('Usaha Pembudidaya')
                            ->columnSpan(6)
                            ->tabs([
                                Tabs\Tab::make('Usaha Pembudidaya')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('usaha.nama_usaha')
                                            ->label('Nama Usaha'),
                                        TextEntry::make('usaha.jenis_usaha')
                                            ->label('Jenis Usaha'),
                                        TextEntry::make('usaha.luas_lahan')
                                            ->label('Luas Lahan'),
                                        TextEntry::make('usaha.jumlah_kolam')
                                            ->label('Jumlah Kolam'),
                                        TextEntry::make('usaha.sistem_budidaya')
                                            ->label('Sistem Budidaya'),
                                        TextEntry::make('usaha.jenis_izin_usaha')
                                            ->label('Jenis Izin Usaha'),
                                        TextEntry::make('usaha.tahun_mulai_usaha')
                                            ->label('Tahun Mulai Usaha'),
                                        TextEntry::make('usaha.status_kepemilikan_usaha')
                                            ->label('Status Kepemilikan Usaha'),
                                        TextEntry::make('usaha.nama_kelompok')
                                            ->label('Nama Kelompok'),
                                        TextEntry::make('usaha.jabatan_di_nama_kelompok')
                                            ->label('Jabatan di Nama Kelompok'),
                                        TextEntry::make('usaha.alamat_usaha')
                                            ->label('Alamat Usaha')
                                            ->columnSpan(2),
                                        TextEntry::make('usaha.provinsi')
                                            ->label('Provinsi'),
                                        TextEntry::make('usaha.kabupaten')
                                            ->label('Kabupaten'),
                                        TextEntry::make('usaha.kecamatan')
                                            ->label('Kecamatan'),
                                        TextEntry::make('usaha.desa')
                                            ->label('Desa'),
                                        TextEntry::make('usaha.no_izin_usaha')
                                            ->label('No Izin Usaha'),
                                        TextEntry::make('usaha.ktp_scan')
                                            ->label('KTP Scan')
                                            ->html()
                                            ->formatStateUsing(function ($state) {
                                                return '<a href="' . asset($state) . '" target="_blank">Lihat</a>';
                                            }),
                                        TextEntry::make('usaha.foto_lokasi')
                                            ->label('Foto Lokasi')
                                            ->html()
                                            ->formatStateUsing(function ($state) {
                                                return '<a href="' . asset($state) . '" target="_blank">Lihat</a>';
                                            }),
                                        TextEntry::make('usaha.surat_izin')
                                            ->label('Surat Izin')
                                            ->html()
                                            ->formatStateUsing(function ($state) {
                                                return '<a href="' . asset($state) . '" target="_blank">Lihat</a>';
                                            }),
                                    ]),
                                Tabs\Tab::make('Kolam Budidaya')
                                    ->schema([
                                        RepeatableEntry::make('kolam')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('nama_kolam')
                                                    ->label('Nama Kolam'),
                                                TextEntry::make('lokasi_kolam')
                                                    ->label('Lokasi Kolam'),
                                                TextEntry::make('panjang')
                                                    ->label('Panjang Kolam'),
                                                TextEntry::make('lebar')
                                                    ->label('Lebar Kolam'),
                                                TextEntry::make('kedalaman')
                                                    ->label('Kedalaman Kolam'),
                                                TextEntry::make('volume_air')
                                                    ->label('Volume Air'),
                                                TextEntry::make('kapasitas')
                                                    ->label('Kapasitas Kolam'),
                                                TextEntry::make('jenis_kolam')
                                                    ->label('Jenis Kolam'),
                                                TextEntry::make('status')
                                                    ->label('Status'),
                                            ])->columns(2)->grid(2)
                                    ])
                            ])

                    ])

            ]);
    }
}
