@extends('layouts.app')
@section('title', 'Payment Cancelled')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 mt-12 mb-8 text-center">
    <div class="bg-white dark:bg-dark-card rounded-2xl border border-kriem-border dark:border-dark-border p-8">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <h1 class="font-heading text-2xl font-bold">Payment Cancelled</h1>
        <p class="text-kriem-muted dark:text-dark-muted mt-2">Your payment was cancelled. No charge was made.</p>
        <p class="text-sm text-kriem-muted dark:text-dark-muted mt-1">Your cart items are still saved if you'd like to try again.</p>

        <a href="{{ route('checkout.form') }}" class="inline-block mt-6 bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold px-6 py-2.5 rounded-full transition">
            Try Again
        </a>
    </div>
</div>
@endsection
