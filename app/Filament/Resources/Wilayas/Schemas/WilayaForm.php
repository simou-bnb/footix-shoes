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
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('home_delivery_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('stopdesk_delivery_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
