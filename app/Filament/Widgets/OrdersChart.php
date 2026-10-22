<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders (Last 30 Days)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $orders = $days->map(fn ($day) => Order::whereDate('created_at', $day)->count());

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->values()->toArray(),
                    'borderColor' => '#B07812',
                    'backgroundColor' => 'rgba(176, 120, 18, 0.6)',
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('M j'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
