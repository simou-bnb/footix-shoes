<?php

namespace App\Filament\Resources\Wilayas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WilayaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('Code Wilaya (ex: 16)'))
                    ->required()
                    ->maxLength(2),
                TextInput::make('name')
                    ->label(__('Nom de la Wilaya'))
                    ->required(),
                TextInput::make('home_delivery_price')
                    ->label(__('Prix livraison à domicile'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix('DA'),
                TextInput::make('stopdesk_delivery_price')
                    ->label(__('Prix livraison stop desk'))
                    ->numeric()
                    ->default(null)
                    ->nullable()
                    ->suffix('DA')
                    ->placeholder(__('Laisser vide si non disponible')),
                Toggle::make('is_active')
                    ->label(__('Wilaya active'))
                    ->default(true)
                    ->required(),
            ]);
    }
}
