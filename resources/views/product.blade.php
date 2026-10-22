@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6" x-data="{ qty: 1, activeImage: '{{ $product->image_path ? asset('storage/' . $product->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '') }}' }">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-kriem-muted dark:text-dark-muted mb-4">
        <a href="{{ route('home') }}" class="hover:text-kriem-berry">Home</a>
        <span class="mx-1">›</span>
        <a href="{{ route('category.show', $product->category) }}" class="hover:text-kriem-berry">{{ $product->category->name }}</a>
        <span class="mx-1">›</span>
        <span class="text-kriem-text dark:text-dark-text">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Product Image Gallery --}}
        <div>
            <div class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border overflow-hidden aspect-square flex items-center justify-center">
                <template x-if="activeImage">
                    <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </template>
                <template x-if="!activeImage">
                    <svg class="w-32 h-32 text-kriem-border dark:text-dark-border" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </template>
            </div>
            @php
                $allImages = collect();
                if ($product->image_path) {
                    $allImages->push(asset('storage/' . $product->image_path));
                }
                foreach ($product->images as $img) {
                    $allImages->push(asset('storage/' . $img->image_path));
                }
            @endphp
            @if($allImages->count() > 1)
                <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                    @foreach($allImages as $imgUrl)
                        <button @click="activeImage = '{{ $imgUrl }}'"
                                class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 rounded-lg border-2 overflow-hidden transition"
                                :class="activeImage === '{{ $imgUrl }}' ? 'border-kriem-berry' : 'border-kriem-border dark:border-dark-border hover:border-kriem-berry/50'">
                            <img src="{{ $imgUrl }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <p class="text-sm text-kriem-muted dark:text-dark-muted">{{ $product->brand->name }}</p>
            <h1 class="font-heading text-2xl md:text-3xl font-bold mt-1">{{ $product->name }}</h1>

            <p class="text-3xl font-bold text-kriem-berry mt-4">{{ $product->priceFormatted() }}</p>

            @if($product->description)
                <p class="mt-4 text-kriem-muted dark:text-dark-muted leading-relaxed">{{ $product->description }}</p>
            @endif

            <div class="mt-6 space-y-3 text-sm">
                @if($product->sku)
                    <div class="flex gap-2"><span class="text-kriem-muted dark:text-dark-muted w-24">SKU:</span><span>{{ $product->sku }}</span></div>
                @endif
                @if($product->model_no)
                    <div class="flex gap-2"><span class="text-kriem-muted dark:text-dark-muted w-24">Model:</span><span>{{ $product->model_no }}</span></div>
                @endif
                @if($product->weight_g)
                    <div class="flex gap-2"><span class="text-kriem-muted dark:text-dark-muted w-24">Weight:</span><span>{{ $product->weight_g }}g</span></div>
                @endif
                @if($product->unit_size_cm)
                    <div class="flex gap-2"><span class="text-kriem-muted dark:text-dark-muted w-24">Size:</span><span>{{ str_replace('x', ' × ', $product->unit_size_cm) }} cm</span></div>
                @endif
                <div class="flex gap-2">
                    <span class="text-kriem-muted dark:text-dark-muted w-24">Stock:</span>
                    <span class="{{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-kriem-gold' : 'text-red-600') }}">
                        {{ $product->stock > 10 ? 'In Stock' : ($product->stock > 0 ? "Only {$product->stock} left" : 'Out of Stock') }}
                    </span>
                </div>
            </div>

            @if($product->stock > 0)
            <div class="mt-6 flex items-center gap-4">
                <div class="flex items-center border border-kriem-border dark:border-dark-border rounded-full">
                    <button @click="qty = Math.max(1, qty - 1)" class="px-3 py-2 text-lg hover:text-kriem-berry">−</button>
                    <span class="px-3 py-2 min-w-[2rem] text-center" x-text="qty"></span>
                    <button @click="qty = Math.min(99, qty + 1)" class="px-3 py-2 text-lg hover:text-kriem-berry">+</button>
                </div>
                <button @click="addToCart({{ $product->id }}, qty)"
                        class="flex-1 bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold py-3 rounded-full transition">
                    Add to Cart
                </button>
            </div>
            @endif

            <div class="mt-4 p-3 bg-kriem-surface dark:bg-dark-surface rounded-xl text-sm text-kriem-muted dark:text-dark-muted">
                🚚 {{ \App\Models\Setting::get('shipping_info_text', 'Free shipping on orders over $50 · Flat $5.99 otherwise') }}
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->isNotEmpty())
    <section class="mt-12 mb-8">
        <h2 class="font-heading text-xl font-bold mb-4">More in {{ $product->category->name }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($related as $relProduct)
                <x-product-card :product="$relProduct" />
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
