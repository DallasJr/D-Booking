<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages\CreateBooking;
use App\Filament\Resources\BookingResource\Pages\EditBooking;
use App\Filament\Resources\BookingResource\Pages\ListBookings;
use App\Models\Booking;
use App\Models\Property;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion des réservations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('property_id')
                    ->label('Propriété')
                    ->options(Property::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->disabled(fn ($record) => $record && $record->status === 'property_removed')
                    ->visible(fn ($record) => $record === null || $record->status !== 'property_removed'),

                DatePicker::make('start_date')
                    ->label("Date d'arrivée")
                    ->required()
                    ->afterOrEqual('today')
                    ->disabled(fn (Get $get) => !$get('property_id'))
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set, Get $get, $state) => self::dateCheck($set, $get, $state, true))
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotalPrice($set, $get)),

                DatePicker::make('end_date')
                    ->label("Date de départ")
                    ->required()
                    ->afterOrEqual('start_date')
                    ->disabled(fn (Get $get) => !$get('property_id'))
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set, Get $get, $state) => self::dateCheck($set, $get, $state, false))
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotalPrice($set, $get)),

                TextInput::make('reduction')
                    ->label('Réduction (€)')
                    ->numeric()
                    ->default(0)
                    ->lazy()
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotalPrice($set, $get)),

                TextInput::make('total_price')
                    ->label('Prix total (€)')
                    ->numeric()
                    ->readonly()
                    ->required(),

                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmée',
                        'cancelled' => 'Annulée',
                        'property_removed' => 'Propriété supprimé',
                    ])
                    ->required(),

                Textarea::make('note')
                    ->required()
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('property.name')
                    ->label('Bien')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label("Début")
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label("Fin")
                    ->date()
                    ->sortable(),

                TextColumn::make('reduction')
                    ->label('Réduction (€)')
                    ->money('eur'),

                TextColumn::make('total_price')
                    ->label('Prix total (€)')
                    ->money('eur')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->label('Statut')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'confirmed',
                        'danger'  => 'cancelled',
                        'gray'    => 'property_removed',
                    ])
                    ->sortable(),

                TextColumn::make('note')
                    ->label('Note')
                    ->limit(20),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
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
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }

    protected static function updateTotalPrice(Set $set, Get $get)
    {
        $propertyId = $get('property_id');
        $startDate = $get('start_date');
        $endDate = $get('end_date');
        $reduction = (float) ($get('reduction') ?? 0);

        if ($propertyId && $startDate && $endDate) {
            $property = Property::find($propertyId);
            if ($property) {
                $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                if ($days > 0) {
                    $total = ($days * $property->price_per_night) - $reduction;
                    $total = max($total, 0);
                    $set('total_price', $total);
                    return;
                }
            }
        }
        $set('total_price', 0);
    }

    protected static function dateCheck(Set $set, Get $get, $state, bool $isStartDate)
    {
        $propertyId = $get('property_id');
        $startDate = $get('start_date');
        $endDate = $get('end_date');
        $recordId = $get('id');

        Log::debug('dateCheck called', [
            'property_id' => $propertyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'editing_record_id' => $recordId,
            'state' => $state,
            'isStartDate' => $isStartDate,
        ]);

        if (!$propertyId) {
            return;
        }

        if ($isStartDate) {
            if ($state && $endDate) {
                if ($state == $endDate) {
                    $set('start_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body("Il vous faut au moins un jour d'écart")
                        ->danger()
                        ->send();
                }
                if ($state > $endDate) {
                    $set('start_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body("La date d'arriver ne peut pas être après la date de départ")
                        ->danger()
                        ->send();
                }
                if (Booking::isOverlapping($propertyId, $state, $endDate, $recordId)) {
                    $set('start_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body('Des dates sélectionnées sont déjà occupées')
                        ->danger()
                        ->send();
                }
            }
        } else {
            if ($startDate && $state) {
                if ($state == $startDate) {
                    $set('end_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body("Il vous faut au moins un jour d'écart")
                        ->danger()
                        ->send();
                }
                if ($state < $startDate) {
                    $set('end_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body("La date de départ ne peut pas être avant la date d'arriver")
                        ->danger()
                        ->send();
                }
                if (Booking::isOverlapping($propertyId, $startDate, $state, $recordId)) {
                    $set('end_date', null);
                    Notification::make()
                        ->title('Conflit de dates')
                        ->body('Des dates sélectionnées sont déjà occupées')
                        ->danger()
                        ->send();
                }
            }
        }
    }
}
