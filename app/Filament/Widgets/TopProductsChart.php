<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;

class TopProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Selling Products';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $topProducts = OrderItem::selectRaw('name_snapshot, SUM(qty) as total_qty')
            ->groupBy('name_snapshot')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $colors = ['#B02A66', '#B07812', '#4F46E5', '#059669', '#DC2626', '#7C3AED', '#DB2777', '#0891B2', '#CA8A04', '#6366F1'];

        return [
            'datasets' => [
                [
                    'data' => $topProducts->pluck('total_qty')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $topProducts->count()),
                ],
            ],
            'labels' => $topProducts->pluck('name_snapshot')->map(fn ($n) => strlen($n) > 25 ? substr($n, 0, 25) . '...' : $n)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
