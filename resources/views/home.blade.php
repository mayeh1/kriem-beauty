@extends('layouts.app')
@section('title', 'Shop Beauty Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">
    {{-- Hero --}}
    <section class="relative bg-linear-to-r from-kriem-berry to-kriem-berry-dark rounded-3xl overflow-hidden mt-6 p-8 md:p-12 text-white">
        <div class="relative z-10 max-w-lg">
            <h1 class="font-heading text-3xl md:text-5xl font-bold leading-tight">{{ \App\Models\Setting::get('hero_title', 'Beauty That Radiates Confidence') }}</h1>
            <p class="mt-3 text-white/80 text-lg">{{ \App\Models\Setting::get('hero_subtitle', 'Premium skincare, makeup, and fragrances delivered to your door. Free shipping on orders over $50.') }}</p>
            <a href="{{ \App\Models\Setting::get('hero_cta_link', '/category/skincare') }}" class="inline-block mt-6 bg-white text-kriem-berry font-semibold px-6 py-3 rounded-full hover:bg-kriem-bg transition">
                {{ \App\Models\Setting::get('hero_cta_text', 'Shop Now') }}
            </a>
        </div>
        <div class="absolute right-0 top-0 w-1/2 h-full opacity-10">
            <svg viewBox="0 0 200 200" class="w-full h-full"><circle cx="150" cy="100" r="120" fill="white"/></svg>
        </div>
    </section>

    {{-- Category Tiles --}}
    <section class="mt-10">
        <h2 class="font-heading text-2xl font-bold mb-4">Shop by Category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category) }}"
                   class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border p-4 text-center hover:border-kriem-berry dark:hover:border-kriem-berry hover:shadow-md transition group">
                    <div class="w-10 h-10 bg-kriem-surface dark:bg-dark-surface rounded-full mx-auto mb-2 flex items-center justify-center">
                        <span class="text-kriem-berry text-lg">✦</span>
                    </div>
                    <span class="text-sm font-medium group-hover:text-kriem-berry transition">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured Products --}}
    @if($featured->isNotEmpty())
    <section class="mt-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-2xl font-bold">Featured Products</h2>
            <span class="text-xs bg-kriem-gold text-white px-3 py-1 rounded-full font-semibold">Staff Picks</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- All Products --}}
    <section class="mt-10 mb-8">
        <h2 class="font-heading text-2xl font-bold mb-4">New Arrivals</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
</div>
@endsection
