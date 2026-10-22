<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockTable extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Alert';
    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where('is_active', true)->where('stock', '<', 10)->orderBy('stock'))
            ->columns([
                Tables\Columns\TextColumn::make('sku')->label('SKU'),
                Tables\Columns\TextColumn::make('name')->limit(30),
                Tables\Columns\TextColumn::make('stock')
                    ->color(fn ($state) => $state === 0 ? 'danger' : 'warning')
                    ->badge(),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
            ])
            ->paginated(false)
            ->emptyStateHeading('All stocked up!')
            ->emptyStateDescription('No products are running low.');
    }
}
