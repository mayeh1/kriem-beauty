<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

beforeEach(function () {
    $category = Category::create(['name' => 'Test', 'slug' => 'test', 'sort_order' => 1]);
    $brand = Brand::create(['name' => 'TestBrand', 'slug' => 'testbrand']);

    $this->product = Product::create([
        'name' => 'Test Product',
        'slug' => 'test-product',
        'sku' => 'TEST-001',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price_cents' => 1999,
        'stock' => 50,
        'is_active' => true,
    ]);
});

test('stripe webhook marks order as paid and decrements stock', function () {
    $order = Order::create([
        'number' => 'KR-000001',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'address_line1' => '123 Main St',
        'city' => 'Anytown',
        'state' => 'CA',
        'zip' => '90210',
        'subtotal_cents' => 1999,
        'shipping_cents' => 599,
        'total_cents' => 2598,
        'status' => 'pending',
        'stripe_session_id' => 'cs_test_123',
    ]);

    $order->items()->create([
        'product_id' => $this->product->id,
        'name_snapshot' => $this->product->name,
        'unit_price_cents' => $this->product->price_cents,
        'qty' => 2,
    ]);

    $payload = json_encode([
        'id' => 'evt_test_123',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_123',
            ],
        ],
    ]);

    $timestamp = time();
    $secret = config('services.stripe.webhook_secret');
    $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

    $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
        'Stripe-Signature' => "t={$timestamp},v1={$signature}",
    ]);

    $order->refresh();
    expect($order->status)->toBe('paid')
        ->and($order->paid_at)->not->toBeNull();

    $this->product->refresh();
    expect($this->product->stock)->toBe(48);
});
