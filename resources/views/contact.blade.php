@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="font-heading text-3xl font-bold text-center">Contact Us</h1>
    <p class="text-kriem-muted dark:text-dark-muted text-center mt-2">Have a question or feedback? We'd love to hear from you.</p>

    @if(session('success'))
        <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.send') }}" method="POST" class="mt-8 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-white dark:bg-dark-surface px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-white dark:bg-dark-surface px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium mb-1">Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                   class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-white dark:bg-dark-surface px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text @error('subject') border-red-500 @enderror">
            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="block text-sm font-medium mb-1">Message</label>
            <textarea name="message" id="message" rows="6" required
                      class="w-full rounded-xl border border-kriem-border dark:border-dark-border bg-white dark:bg-dark-surface px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-kriem-berry dark:text-dark-text resize-y @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full sm:w-auto bg-kriem-berry hover:bg-kriem-berry-dark text-white font-semibold px-8 py-3 rounded-full transition">
            Send Message
        </button>
    </form>
</div>
@endsection
