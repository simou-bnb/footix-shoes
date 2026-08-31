<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('N° commande'))
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label(__('Client'))
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->label(__('Téléphone'))
                    ->searchable(),
                TextColumn::make('wilaya.name')
                    ->label(__('Wilaya'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('delivery_type')
                    ->label(__('Livraison'))
                    ->badge(),
                TextColumn::make('total')
                    ->label(__('Total'))
                    ->money('DZD')
                    ->sortable(),
                SelectColumn::make('status')
                    ->label(__('Statut'))
                    ->options(OrderStatus::class),
                TextColumn::make('created_at')
                    ->label(__('Reçue le'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Statut'))
                    ->options(OrderStatus::class),
                SelectFilter::make('wilaya_id')
                    ->label(__('Wilaya'))
                    ->relationship('wilaya', 'name')
                    ->searchable(),
                SelectFilter::make('delivery_type')
                    ->label(__('Livraison'))
                    ->options(DeliveryType::class),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('Voir')),
            ])
            ->emptyStateHeading(__('Aucune commande pour le moment'))
            ->emptyStateDescription(__('Les commandes passées sur le site apparaîtront ici.'));
    }
}
