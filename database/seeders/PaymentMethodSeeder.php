<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Visa', 'slug' => 'visa', 'description' => 'Pay with Visa', 'icon_path' => 'payment-methods/visa.svg', 'sort_order' => 1],
            ['name' => 'Mastercard', 'slug' => 'mastercard', 'description' => 'Pay with Mastercard', 'icon_path' => 'payment-methods/mastercard.svg', 'sort_order' => 2],
            ['name' => 'American Express', 'slug' => 'amex', 'description' => 'Pay with Amex', 'icon_path' => 'payment-methods/amex.svg', 'sort_order' => 3],
            ['name' => 'Apple Pay', 'slug' => 'apple-pay', 'description' => 'Pay with Apple Pay', 'icon_path' => 'payment-methods/apple-pay.svg', 'sort_order' => 4],
            ['name' => 'Google Pay', 'slug' => 'google-pay', 'description' => 'Pay with Google Pay', 'icon_path' => 'payment-methods/google-pay.svg', 'sort_order' => 5],
            ['name' => 'PayPal', 'slug' => 'paypal', 'description' => 'Pay with PayPal', 'icon_path' => 'payment-methods/paypal.svg', 'sort_order' => 6],
            ['name' => 'Cash App', 'slug' => 'cash-app', 'description' => 'Pay with Cash App', 'icon_path' => 'payment-methods/cash-app.svg', 'sort_order' => 7],
            ['name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'description' => 'Direct bank transfer', 'instructions' => "Please transfer the total amount to:\n\nBank: [Your Bank Name]\nAccount: [Account Number]\nRouting: [Routing Number]\nReference: Your order number\n\nYour order will be processed once payment is confirmed.", 'icon_path' => 'payment-methods/bank-transfer.svg', 'sort_order' => 8],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }

        // Remove old generic "card" entry if it exists
        PaymentMethod::where('slug', 'card')->delete();
    }
}
