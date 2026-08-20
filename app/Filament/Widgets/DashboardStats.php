<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected const LOW_STOCK_THRESHOLD = 5;

    protected function getStats(): array
    {
        return [
            Stat::make('Commandes aujourd\'hui', Order::whereDate('created_at', today())->count()),

            Stat::make('En attente de confirmation', Order::where('status', OrderStatus::Pending)->count())
                ->color('warning'),

            Stat::make('Stock faible', ProductVariant::where('is_active', true)
                ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
                ->count())
                ->color('danger'),
        ];
    }
}
