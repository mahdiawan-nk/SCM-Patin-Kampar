<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Data Pelangagan';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Manajemen Pemasaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Costumer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->columnSpanFull(),
                Forms\Components\Select::make('segment')
                    ->options([
                        'new' => 'New',
                        'regular' => 'Regular',
                        'loyal' => 'Loyal',
                        'premium' => 'Premium',
                        'vip' => 'Vip',
                    ]),
                Forms\Components\Hidden::make('is_edited')->default(false),
                Forms\Components\Checkbox::make('create_account')
                    ->label(fn(callable $get) => $get('is_edited') ? 'Create Account' : 'No Create Account')
                    ->visible(fn(callable $get) => !$get('is_edited'))
                    ->default(false)
                    ->reactive()
                    ->required(),
                Section::make('Account')
                    ->hidden(fn(callable $get) => !$get('create_account'))
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('segment')
                    ->searchable(),
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
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('segment')
                    ->options([
                        'new' => 'New',
                        'regular' => 'Regular',
                        'loyal' => 'Loyal',
                        'premium' => 'Premium',
                        'vip' => 'Vip',
                    ])
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->fillForm(function (Model $record, Table $table): array {
                        return [
                            'is_edited' => true,
                            'name' => $record->name,
                            'email' => $record->email,
                            'phone' => $record->phone,
                            'address' => $record->address,
                            'segment' => $record->segment,
                        ];
                    })
                    ->action(function (array $data, Model $record) {
                        if ($record->user_id != null) {
                            $record->user()->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                            ]);
                        }
                        $record->update([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'],
                            'address' => $data['address'],
                            'segment' => $data['segment'],
                        ]);

                        Notification::make()
                            ->title('Updated successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
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
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomer::route('/create'),
            // 'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
