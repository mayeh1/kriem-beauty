<?php

use App\Models\Brand;
use App\Models\Category;
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

test('can add item to cart', function () {
    $response = $this->postJson('/cart/add', [
        'product_id' => $this->product->id,
        'qty' => 2,
    ]);

    $response->assertOk()
        ->assertJson(['count' => 2]);
});

test('can update cart quantity', function () {
    $this->postJson('/cart/add', ['product_id' => $this->product->id, 'qty' => 1]);

    $response = $this->patchJson('/cart/update', [
        'product_id' => $this->product->id,
        'qty' => 5,
    ]);

    $response->assertOk()
        ->assertJson(['count' => 5]);
});

test('can remove item from cart', function () {
    $this->postJson('/cart/add', ['product_id' => $this->product->id, 'qty' => 1]);

    $response = $this->deleteJson('/cart/remove', [
        'product_id' => $this->product->id,
    ]);

    $response->assertOk()
        ->assertJson(['count' => 0]);
});

test('cart returns correct totals', function () {
    $this->postJson('/cart/add', ['product_id' => $this->product->id, 'qty' => 1]);

    $response = $this->getJson('/cart');

    $response->assertOk()
        ->assertJson([
            'count' => 1,
            'subtotal' => '$19.99',
        ]);
});
