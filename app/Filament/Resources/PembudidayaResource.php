<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembudidayaResource\Pages;
use App\Filament\Resources\PembudidayaResource\RelationManagers;
use App\Models\Pembudidaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ActionGroup;

class PembudidayaResource extends Resource
{
    protected static ?string $model = Pembudidaya::class;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Biodata Pembudidaya')
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 8,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nik')
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->maxLength(16),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ]),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ]),
                        Forms\Components\TextInput::make('no_hp')
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ]),
                        Forms\Components\DateTimePicker::make('tgl_bergabung')
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 2,
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'tidak aktif' => 'Tidak Aktif',
                            ])
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 2,
                            ])
                            ->required()
                    ]),
                Section::make('Data Usaha')

                    ->schema([
                        Repeater::make('usaha')
                            ->relationship()
                            ->addable(false)
                            ->schema([
                                Forms\Components\TextInput::make('nama_usaha')
                                    ->required()
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('jenis_usaha')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('luas_lahan')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('jumlah_kolam')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('sistem_budidaya')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('jenis_izin_usaha')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('tahun_mulai_usaha')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\Select::make('status_kepemilikan_usaha')
                                    ->options([
                                        'milik sendiri' => 'Milik Sendiri',
                                        'sewa' => 'Sewa',
                                        'kelompok' => 'Kelompok',
                                    ])
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('nama_kelompok')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('jabatan_di_kelompok')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('provinsi')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('kabupaten')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('kecamatan')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('desa')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextArea::make('alamat_usaha')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('no_izin_usaha')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\FileUpload::make('ktp_scan')
                                    ->disk('public')
                                    ->directory('pembudidaya')
                                    ->visibility('public')
                                    ->columnSpan(1)
                                    ->dehydrated(true),
                                Forms\Components\FileUpload::make('foto_lokasi')
                                    ->disk('public')
                                    ->directory('pembudidaya')
                                    ->visibility('public')
                                    ->columnSpan(2)
                                    ->dehydrated(true),
                                Forms\Components\FileUpload::make('surat_izin')
                                    ->disk('public')
                                    ->directory('pembudidaya')
                                    ->visibility('public')
                                    ->columnSpan(1)
                                    ->dehydrated(true),
                            ])
                            ->columns(4)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_bergabung')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                ])
                    ->button()
                    ->label('Actions')

            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembudidayas::route('/'),
            'create' => Pages\CreatePembudidaya::route('/create'),
            'edit' => Pages\EditPembudidaya::route('/{record}/edit'),
            'view' => Pages\ViewPembudidaya::route('/{record}/view'),
        ];
    }
}
