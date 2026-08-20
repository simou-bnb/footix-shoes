<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends TableWidget
{
    protected static ?string $heading = 'Dernières commandes';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('order_number')
                    ->label('N° commande'),
                TextColumn::make('customer_name')
                    ->label('Client'),
                TextColumn::make('wilaya.name')
                    ->label('Wilaya'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('DZD'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Reçue le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Aucune commande pour le moment');
    }
}
