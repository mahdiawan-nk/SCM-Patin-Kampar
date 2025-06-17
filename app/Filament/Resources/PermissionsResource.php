<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionsResource\Pages;
use App\Filament\Resources\PermissionsResource\RelationManagers;
use App\Models\Permissions;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Select, Repeater};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsResource extends Resource
{
    protected static ?string $model = Permissions::class;
    protected static ?string $navigationGroup = 'Management Users';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('permissions')
                    ->hiddenLabel()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('guard_name')
                            ->readOnly()
                            ->default('web'),
                    ])

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('guard_name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('name'),
                        TextInput::make('guard_name')->readOnly()->default('web'),
                    ])

                    ->label('Edit'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePermissions::route('/'),
        ];
    }
}
