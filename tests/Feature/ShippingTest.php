<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $category = Category::create(['name' => 'Test', 'slug' => 'test', 'sort_order' => 1]);
    $brand = Brand::create(['name' => 'TestBrand', 'slug' => 'testbrand']);

    $this->cheapProduct = Product::create([
        'name' => 'Cheap Product',
        'slug' => 'cheap-product',
        'sku' => 'CHEAP-001',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price_cents' => 4999,
        'stock' => 50,
        'is_active' => true,
    ]);

    $this->expensiveProduct = Product::create([
        'name' => 'Expensive Product',
        'slug' => 'expensive-product',
        'sku' => 'EXP-001',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price_cents' => 5000,
        'stock' => 50,
        'is_active' => true,
    ]);
});

test('cart under $50 shows $5.99 shipping', function () {
    $this->postJson('/cart/add', ['product_id' => $this->cheapProduct->id, 'qty' => 1]);

    $response = $this->getJson('/cart');

    $response->assertOk()
        ->assertJson([
            'shipping' => '$5.99',
            'shipping_cents' => 599,
        ]);
});

test('cart at $50 shows free shipping', function () {
    $this->postJson('/cart/add', ['product_id' => $this->expensiveProduct->id, 'qty' => 1]);

    $response = $this->getJson('/cart');

    $response->assertOk()
        ->assertJson([
            'shipping' => 'Free',
            'shipping_cents' => 0,
        ]);
});
