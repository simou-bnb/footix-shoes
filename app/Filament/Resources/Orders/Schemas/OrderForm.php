<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client')
                    ->columns(2)
                    ->components([
                        TextInput::make('order_number')
                            ->label('N° de commande')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('customer_name')
                            ->label('Nom du client')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('customer_phone')
                            ->label('Téléphone')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('wilaya.name')
                            ->label('Wilaya')
                            ->formatStateUsing(fn ($state, $record) => $state ?: $record?->wilaya?->name)
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('commune')
                            ->label('Commune')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('delivery_type')
                            ->label('Type de livraison')
                            ->formatStateUsing(fn ($state) => $state instanceof DeliveryType ? $state->getLabel() : DeliveryType::tryFrom((string) $state)?->getLabel())
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('address')
                            ->label('Adresse')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Montants')
                    ->columns(3)
                    ->components([
                        TextInput::make('subtotal')
                            ->label('Sous-total')
                            ->numeric()
                            ->suffix('DA')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('delivery_price')
                            ->label('Livraison')
                            ->numeric()
                            ->suffix('DA')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->suffix('DA')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make('Suivi')
                    ->columns(1)
                    ->components([
                        Select::make('status')
                            ->label('Statut')
                            ->options(OrderStatus::class)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notes internes')
                            ->helperText('Ex: "Appelé, pas de réponse", "Confirmé par téléphone"...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
