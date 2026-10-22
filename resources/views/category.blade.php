@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="font-heading text-3xl font-bold">{{ $category->name }}</h1>
            <p class="text-sm text-kriem-muted dark:text-dark-muted mt-1">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-kriem-muted dark:text-dark-muted">Sort by:</label>
            <select onchange="window.location.href = this.value"
                    class="rounded-full border border-kriem-border dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                <option value="{{ route('category.show', [$category, 'sort' => 'name']) }}" {{ $sort === 'name' ? 'selected' : '' }}>Name</option>
                <option value="{{ route('category.show', [$category, 'sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                <option value="{{ route('category.show', [$category, 'sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="col-span-full text-center py-12 text-kriem-muted dark:text-dark-muted">No products found in this category.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
