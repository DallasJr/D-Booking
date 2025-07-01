<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages\CreateProperty;
use App\Filament\Resources\PropertyResource\Pages\EditProperty;
use App\Filament\Resources\PropertyResource\Pages\ListProperties;
use App\Models\Property;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion des propriétés';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nom de la propriété'),

                Textarea::make('description')
                    ->required()
                    ->label('Description'),

                TextInput::make('city')
                    ->required()
                    ->label('Ville'),

                TextInput::make('price_per_night')
                    ->numeric()
                    ->required()
                    ->label('Prix par nuit (€)'),

                FileUpload::make('image')
                    ->directory('properties')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->visibility('public')
                    ->label('Image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Ville')
                    ->sortable(),
                TextColumn::make('price_per_night')
                    ->label('Prix par nuit (€)')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }
}
