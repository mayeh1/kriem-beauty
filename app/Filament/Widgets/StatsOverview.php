<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersThisWeek = Order::whereBetween('created_at', [$weekStart, now()])->count();
        $ordersThisMonth = Order::whereBetween('created_at', [$monthStart, now()])->count();

        $revenueToday = Order::where('status', '!=', 'pending')
            ->whereDate('paid_at', $today)->sum('total_cents');
        $revenueThisWeek = Order::where('status', '!=', 'pending')
            ->whereBetween('paid_at', [$weekStart, now()])->sum('total_cents');
        $revenueThisMonth = Order::where('status', '!=', 'pending')
            ->whereBetween('paid_at', [$monthStart, now()])->sum('total_cents');

        $lastMonthRevenue = Order::where('status', '!=', 'pending')
            ->whereBetween('paid_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->sum('total_cents');

        $avgOrderValue = Order::where('status', '!=', 'pending')->avg('total_cents') ?? 0;
        $pendingOrders = Order::where('status', 'pending')->count();
        $lowStockCount = Product::where('stock', '<', 10)->where('is_active', true)->count();
        $totalProducts = Product::where('is_active', true)->count();

        $revenueChange = $lastMonthRevenue > 0
            ? round((($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return [
            Stat::make('Revenue Today', '$' . number_format($revenueToday / 100, 2))
                ->icon('heroicon-o-currency-dollar')
                ->description('This week: $' . number_format($revenueThisWeek / 100, 2))
                ->color('success'),
            Stat::make('Revenue This Month', '$' . number_format($revenueThisMonth / 100, 2))
                ->icon('heroicon-o-banknotes')
                ->description($revenueChange >= 0 ? "+{$revenueChange}% vs last month" : "{$revenueChange}% vs last month")
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger'),
            Stat::make('Orders Today', $ordersToday)
                ->icon('heroicon-o-shopping-cart')
                ->description("Week: {$ordersThisWeek} · Month: {$ordersThisMonth}")
                ->color('primary'),
            Stat::make('Avg Order Value', '$' . number_format($avgOrderValue / 100, 2))
                ->icon('heroicon-o-calculator')
                ->color('info'),
            Stat::make('Pending Orders', $pendingOrders)
                ->icon('heroicon-o-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success')
                ->description($pendingOrders > 0 ? 'Awaiting payment' : 'All clear'),
            Stat::make('Low Stock', $lowStockCount . " / {$totalProducts}")
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success')
                ->description($lowStockCount > 0 ? 'Products under 10 units' : 'All well stocked'),
        ];
    }
}
