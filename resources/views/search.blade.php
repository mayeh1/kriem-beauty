@extends('layouts.app')
@section('title', 'Search')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
    <h1 class="font-heading text-3xl font-bold mb-1">Search Results</h1>
    @if($q)
        <p class="text-sm text-kriem-muted dark:text-dark-muted mb-6">
            {{ $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count() }} result{{ ($products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count()) !== 1 ? 's' : '' }} for "<strong>{{ $q }}</strong>"
        </p>
    @else
        <p class="text-sm text-kriem-muted dark:text-dark-muted mb-6">Enter a search term above.</p>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            @if($q)
                <p class="col-span-full text-center py-12 text-kriem-muted dark:text-dark-muted">No products found. Try a different search term.</p>
            @endif
        @endforelse
    </div>

    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
