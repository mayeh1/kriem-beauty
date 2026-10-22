<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();

        $revenueThisWeek = Order::where('status', '!=', 'pending')
            ->whereBetween('paid_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total_cents');

        $lowStockCount = Product::where('stock', '<', 10)->where('is_active', true)->count();

        return [
            Stat::make('Orders Today', $ordersToday)
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),
            Stat::make('Revenue This Week', '$' . number_format($revenueThisWeek / 100, 2))
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('Low Stock Products', $lowStockCount)
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success')
                ->description($lowStockCount > 0 ? 'Products with stock under 10' : 'All products well stocked'),
        ];
    }
}
