@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 mt-6 mb-8">
    <h1 class="font-heading text-3xl font-bold mb-6">Checkout</h1>

    <div class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border p-6">
        {{-- Cart Summary --}}
        <div class="mb-6 pb-6 border-b border-kriem-border dark:border-dark-border">
            <h2 class="font-heading font-semibold mb-3">Order Summary</h2>
            <template x-for="(item, key) in cartItems" :key="key">
                <div class="flex justify-between text-sm py-1">
                    <span><span x-text="item.name"></span> × <span x-text="item.qty"></span></span>
                    <span x-text="'$' + ((item.price_cents * item.qty) / 100).toFixed(2)"></span>
                </div>
            </template>
            <div class="flex justify-between text-sm mt-2 pt-2 border-t border-kriem-border dark:border-dark-border">
                <span>Subtotal</span><span x-text="cartSubtotal"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Shipping</span><span x-text="cartShipping" :class="cartShippingCents === 0 ? 'text-green-600' : ''"></span>
            </div>
            <div class="flex justify-between font-bold mt-1">
                <span>Total</span><span x-text="cartTotal"></span>
            </div>
        </div>

        <form action="{{ route('checkout.create') }}" method="POST" class="space-y-4">
            @csrf
            <h2 class="font-heading font-semibold">Shipping Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Address Line 1 *</label>
                <input type="text" name="address_line1" required value="{{ old('address_line1') }}"
                       class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                @error('address_line1')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Address Line 2</label>
                <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                       class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">City *</label>
                    <input type="text" name="city" required value="{{ old('city') }}"
                           class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                    @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">State *</label>
                    <input type="text" name="state" required value="{{ old('state') }}"
                           class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                    @error('state')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">ZIP *</label>
                    <input type="text" name="zip" required value="{{ old('zip') }}"
                           class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-kriem-bg dark:bg-dark-surface px-3 py-2 text-sm focus:ring-2 focus:ring-kriem-berry dark:text-dark-text">
                    @error('zip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold py-3 rounded-full transition mt-4">
                Pay with Stripe
            </button>

            @php($paymentMethods = \App\Models\PaymentMethod::active()->get())
            @if($paymentMethods->isNotEmpty())
                <div class="mt-4 text-center">
                    <p class="text-xs text-kriem-muted dark:text-dark-muted mb-2">We accept</p>
                    <div class="flex items-center justify-center flex-wrap gap-2">
                        @foreach($paymentMethods as $pm)
                            @if($pm->icon_path)
                                <img src="{{ file_exists(public_path('images/' . $pm->icon_path)) ? asset('images/' . $pm->icon_path) : asset('storage/' . $pm->icon_path) }}" alt="{{ $pm->name }}" title="{{ $pm->name }}" class="h-8 w-auto rounded border border-kriem-border dark:border-dark-border">
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <p class="text-xs text-center text-kriem-muted dark:text-dark-muted mt-3">Secure payment powered by Stripe. You will be redirected to complete your purchase.</p>
        </form>
    </div>
</div>
@endsection
