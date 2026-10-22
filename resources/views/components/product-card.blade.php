@props(['product'])

<div class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border overflow-hidden hover:shadow-lg transition group">
    <a href="{{ route('product.show', $product) }}">
        <div class="aspect-square bg-kriem-surface dark:bg-dark-surface flex items-center justify-center">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <svg class="w-16 h-16 text-kriem-border dark:text-dark-border" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            @endif
        </div>
    </a>
    <div class="p-3">
        <p class="text-xs text-kriem-muted dark:text-dark-muted">{{ $product->brand->name }}</p>
        <a href="{{ route('product.show', $product) }}" class="text-sm font-medium leading-tight line-clamp-2 hover:text-kriem-berry transition">
            {{ $product->name }}
        </a>
        <div class="flex items-center justify-between mt-2">
            <span class="text-base font-bold text-kriem-berry">{{ $product->priceFormatted() }}</span>
            <button @click="addToCart({{ $product->id }})"
                    class="bg-kriem-berry hover:bg-kriem-berry-dark text-white text-xs font-semibold px-3 py-1.5 rounded-full transition">
                Add
            </button>
        </div>
    </div>
</div>
