<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';
    protected const FREE_SHIPPING_THRESHOLD = 5000;
    protected const SHIPPING_COST = 599;

    public function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->items();
        $key = (string) $productId;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price_cents' => $product->price_cents,
                'qty' => $qty,
                'image_path' => $product->image_path,
                'slug' => $product->slug,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $qty): void
    {
        $cart = $this->items();
        $key = (string) $productId;

        if ($qty <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[(string) $productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum(array_column($this->items(), 'qty'));
    }

    public function subtotalCents(): int
    {
        $total = 0;
        foreach ($this->items() as $item) {
            $total += $item['price_cents'] * $item['qty'];
        }
        return $total;
    }

    public function shippingCents(): int
    {
        if ($this->count() === 0) {
            return 0;
        }
        return $this->subtotalCents() >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
    }

    public function totalCents(): int
    {
        return $this->subtotalCents() + $this->shippingCents();
    }

    public function subtotalFormatted(): string
    {
        return '$' . number_format($this->subtotalCents() / 100, 2);
    }

    public function shippingFormatted(): string
    {
        $shipping = $this->shippingCents();
        return $shipping === 0 ? 'Free' : '$' . number_format($shipping / 100, 2);
    }

    public function totalFormatted(): string
    {
        return '$' . number_format($this->totalCents() / 100, 2);
    }
}
