<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KolamBudidayaResource\Pages;
use App\Filament\Resources\KolamBudidayaResource\RelationManagers;
use App\Models\KolamBudidaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ActionGroup;

class KolamBudidayaResource extends Resource
{
    protected static ?string $model = KolamBudidaya::class;
    protected static ?string $navigationGroup = 'Management Budidaya (Hulu)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pembudidaya_id')
                    ->relationship('pembudidaya', 'nama_lengkap')
                    ->required(),
                Forms\Components\TextInput::make('nama_kolam')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_kolam')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('panjang')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lebar')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kedalaman')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('volume_air')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kapasitas')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('jenis_kolam')
                    ->options([
                        'tanah' => 'Tanah',
                        'terpal' => 'Terpal',
                        'beton' => 'Beton',
                        'keramba' => 'Keramba',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'maintenance' => 'Maintenance',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('pembudidaya.nama_lengkap')
            ->columns([
                Tables\Columns\TextColumn::make('pembudidaya.nama_lengkap')
                    ->hidden()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kolam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_kolam')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('panjang')
                    ->label('Keterangan Dimensi')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $listHtml = '<ul class="space-y-1 text-gray-500 list-none list-inside dark:text-gray-400">';
                        $listHtml .= '<li>Panjang : ' . $record->panjang . ' m</li>';
                        $listHtml .= '<li>Lebar : ' . $record->lebar . ' m2</li>';
                        $listHtml .= '<li>Kedalaman : ' . $record->kedalaman . '</li>';
                        $listHtml .= '<li>Volume Air : ' . $record->volume_air . '</li>';
                        $listHtml .= '</ul>';
                        return $listHtml;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kolam'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                        'maintenance' => 'warning',
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
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\Action::make('monitoring')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->url(fn(KolamBudidaya $record): string => self::getUrl('monitoring', ['record' => $record->getKey()])),
                    Tables\Actions\Action::make('siklus')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->url(fn(KolamBudidaya $record): string => self::getUrl('siklus', ['record' => $record->getKey()])),
                ])
                    ->extraAttributes(['id' => 'btn-costum'])
                    ->button()
                    ->label('Actions')
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKolamBudidayas::route('/'),
            'create' => Pages\CreateKolamBudidaya::route('/create'),
            'edit' => Pages\EditKolamBudidaya::route('/{record}/edit'),
            'monitoring' => Pages\MonitoringKolam::route('/{record}/monitoring'),
            'siklus' => Pages\SiklusKolam::route('/{record}/siklus'),
        ];
    }
}
