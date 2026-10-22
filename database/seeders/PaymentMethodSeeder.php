<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Credit / Debit Card', 'slug' => 'card', 'description' => 'Visa, Mastercard, Amex via Stripe', 'sort_order' => 1],
            ['name' => 'Apple Pay', 'slug' => 'apple-pay', 'description' => 'Pay with Apple Pay', 'sort_order' => 2],
            ['name' => 'Google Pay', 'slug' => 'google-pay', 'description' => 'Pay with Google Pay', 'sort_order' => 3],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['slug' => $method['slug']], $method);
        }
    }
}
