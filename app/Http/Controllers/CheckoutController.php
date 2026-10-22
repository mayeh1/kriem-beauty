<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
        ]);

        $items = $this->cart->items();
        if (empty($items)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $order = Order::create([
            'number' => Order::generateNumber(),
            'email' => $request->email,
            'name' => $request->name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'subtotal_cents' => $this->cart->subtotalCents(),
            'shipping_cents' => $this->cart->shippingCents(),
            'total_cents' => $this->cart->totalCents(),
            'status' => 'pending',
        ]);

        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'name_snapshot' => $item['name'],
                'unit_price_cents' => $item['price_cents'],
                'qty' => $item['qty'],
            ]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount' => $item['price_cents'],
                ],
                'quantity' => $item['qty'],
            ];
        }

        if ($this->cart->shippingCents() > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Shipping'],
                    'unit_amount' => $this->cart->shippingCents(),
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['order' => $order->number]),
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $request->email,
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request, string $order)
    {
        $order = Order::where('number', $order)->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response('Webhook error', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order && $order->status === 'pending') {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->where('stock', '>=', $item->qty)
                        ->decrement('stock', $item->qty);
                }

                $this->cart->clear();

                Mail::to($order->email)->send(new OrderConfirmation($order));
            }
        }

        return response('OK', 200);
    }

    public function showForm()
    {
        return view('checkout.form');
    }
}
