<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KRIEM Beauty') - KRIEM Beauty</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-kriem-bg text-kriem-text dark:bg-dark-bg dark:text-dark-text min-h-screen flex flex-col"
      x-data="cartDrawer()" @cart-updated.window="fetchCart()">

    {{-- Navigation --}}
    <nav class="bg-white dark:bg-dark-card border-b border-kriem-border dark:border-dark-border sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/kriem-logo-horizontal.svg" alt="KRIEM Beauty" class="h-8 dark:hidden">
                    <img src="/kriem-logo-white.svg" alt="KRIEM Beauty" class="h-8 hidden dark:block">
                </a>

                <form action="{{ route('search') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Search products, brands..."
                               class="w-full rounded-full border border-kriem-border dark:border-dark-border bg-kriem-surface dark:bg-dark-surface pl-4 pr-10 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-kriem-muted dark:text-dark-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="p-2 rounded-full hover:bg-kriem-surface dark:hover:bg-dark-surface text-kriem-muted dark:text-dark-muted">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>

                    <button @click="open = true" class="relative p-2 rounded-full hover:bg-kriem-surface dark:hover:bg-dark-surface text-kriem-muted dark:text-dark-muted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span x-show="cartCount > 0" x-text="cartCount"
                              class="absolute -top-1 -right-1 bg-kriem-berry text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"></span>
                    </button>
                </div>
            </div>

            {{-- Mobile search --}}
            <form action="{{ route('search') }}" method="GET" class="md:hidden pb-3">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search..."
                       class="w-full rounded-full border border-kriem-border dark:border-dark-border bg-kriem-surface dark:bg-dark-surface px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
            </form>
        </div>

        {{-- Category navigation --}}
        <div class="border-t border-kriem-border dark:border-dark-border bg-white dark:bg-dark-card">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 overflow-x-auto">
                <div class="flex gap-6 py-2 text-sm whitespace-nowrap">
                    @foreach(\App\Models\Category::orderBy('sort_order')->get() as $cat)
                        <a href="{{ route('category.show', $cat) }}"
                           class="text-kriem-muted dark:text-dark-muted hover:text-kriem-berry dark:hover:text-kriem-berry transition {{ request()->is('category/'.$cat->slug) ? 'text-kriem-berry font-semibold' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    {{-- Cart Drawer --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="open = false" class="fixed inset-0 bg-black/50 z-50" x-cloak></div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 h-full w-full max-w-md bg-white dark:bg-dark-card shadow-xl z-50 flex flex-col" x-cloak>

        <div class="flex items-center justify-between p-4 border-b border-kriem-border dark:border-dark-border">
            <h2 class="font-heading text-lg font-bold">Your Cart</h2>
            <button @click="open = false" class="p-1 text-kriem-muted dark:text-dark-muted hover:text-kriem-text dark:hover:text-dark-text">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <template x-if="Object.keys(cartItems).length === 0">
                <p class="text-center text-kriem-muted dark:text-dark-muted py-8">Your cart is empty</p>
            </template>
            <template x-for="(item, key) in cartItems" :key="key">
                <div class="flex gap-3 p-3 bg-kriem-surface dark:bg-dark-surface rounded-xl">
                    <div class="w-16 h-16 bg-kriem-border dark:bg-dark-border rounded-lg flex items-center justify-center text-xs text-kriem-muted">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" x-text="item.name"></p>
                        <p class="text-sm text-kriem-berry font-semibold" x-text="'$' + (item.price_cents / 100).toFixed(2)"></p>
                        <div class="flex items-center gap-2 mt-1">
                            <button @click="updateQty(item.product_id, item.qty - 1)"
                                    class="w-6 h-6 rounded-full border border-kriem-border dark:border-dark-border flex items-center justify-center text-xs hover:bg-kriem-border dark:hover:bg-dark-border">−</button>
                            <span class="text-sm w-6 text-center" x-text="item.qty"></span>
                            <button @click="updateQty(item.product_id, item.qty + 1)"
                                    class="w-6 h-6 rounded-full border border-kriem-border dark:border-dark-border flex items-center justify-center text-xs hover:bg-kriem-border dark:hover:bg-dark-border">+</button>
                            <button @click="removeItem(item.product_id)" class="ml-auto text-red-500 text-xs hover:underline">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="Object.keys(cartItems).length > 0" class="border-t border-kriem-border dark:border-dark-border p-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span>Subtotal</span>
                <span x-text="cartSubtotal"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Shipping</span>
                <span x-text="cartShipping" :class="cartShippingCents === 0 ? 'text-green-600' : ''"></span>
            </div>
            <div class="flex justify-between font-bold text-base border-t border-kriem-border dark:border-dark-border pt-2">
                <span>Total</span>
                <span x-text="cartTotal"></span>
            </div>
            <a href="{{ route('checkout.form') }}"
               class="block w-full text-center bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold py-3 rounded-full transition mt-3">
                Checkout
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-dark-card border-t border-kriem-border dark:border-dark-border mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-heading text-xl font-bold text-kriem-berry mb-2">KRIEM Beauty</h3>
                    <p class="text-sm text-kriem-muted dark:text-dark-muted">Quality cosmetics and beauty products delivered to your door.</p>
                </div>
                <div>
                    <h4 class="font-heading font-semibold mb-2">Categories</h4>
                    <ul class="space-y-1 text-sm text-kriem-muted dark:text-dark-muted">
                        @foreach(\App\Models\Category::orderBy('sort_order')->get() as $cat)
                            <li><a href="{{ route('category.show', $cat) }}" class="hover:text-kriem-berry">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-semibold mb-2">Info</h4>
                    <ul class="space-y-1 text-sm text-kriem-muted dark:text-dark-muted">
                        <li>Free shipping on orders $50+</li>
                        <li>Flat rate $5.99 shipping</li>
                        <li>Secure payments via Stripe</li>
                        <li><a href="{{ route('contact.show') }}" class="hover:text-kriem-berry">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-4 border-t border-kriem-border dark:border-dark-border text-center text-xs text-kriem-muted dark:text-dark-muted">
                &copy; {{ date('Y') }} KRIEM Beauty. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- WhatsApp floating button --}}
    @if(config('services.whatsapp.number'))
    <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text={{ urlencode('Hi KRIEM Beauty! I have a question.') }}"
       target="_blank" rel="noopener"
       class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#1ebe57] text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110"
       aria-label="Chat on WhatsApp">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
    @endif
</body>
</html>
