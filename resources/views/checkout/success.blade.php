@extends('layouts.app')
@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 mt-12 mb-8 text-center">
    <div class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border p-8">
        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-heading text-2xl font-bold">Thank You!</h1>
        <p class="text-kriem-muted dark:text-dark-muted mt-2">Your order has been placed successfully.</p>

        <div class="mt-6 p-4 bg-kriem-surface dark:bg-dark-surface rounded-xl text-left space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-kriem-muted dark:text-dark-muted">Order Number:</span><span class="font-bold">{{ $order->number }}</span></div>
            <div class="flex justify-between"><span class="text-kriem-muted dark:text-dark-muted">Status:</span><span class="capitalize">{{ $order->status }}</span></div>
            <div class="flex justify-between"><span class="text-kriem-muted dark:text-dark-muted">Total:</span><span class="font-bold">{{ $order->totalFormatted() }}</span></div>
        </div>

        <p class="text-sm text-kriem-muted dark:text-dark-muted mt-4">A confirmation email will be sent to <strong>{{ $order->email }}</strong> once payment is verified.</p>

        <a href="{{ route('home') }}" class="inline-block mt-6 bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold px-6 py-2.5 rounded-full transition">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
