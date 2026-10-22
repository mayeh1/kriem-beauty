<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;

class RevenueByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Category';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $data = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(order_items.unit_price_cents * order_items.qty) as revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        $colors = ['#B02A66', '#B07812', '#4F46E5', '#059669', '#DC2626', '#7C3AED', '#DB2777'];

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('revenue')->map(fn ($v) => round($v / 100, 2))->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                ],
            ],
            'labels' => $data->pluck('category')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
