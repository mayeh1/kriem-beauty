<div style="max-width:600px;margin:0 auto;font-family:Figtree,Arial,sans-serif;color:#241A2E">
    <div style="background:#B02A66;padding:24px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:24px">KRIEM Beauty</h1>
    </div>
    <div style="padding:32px 24px;background:#FBF6FA">
        <h2 style="margin:0 0 8px">Order Confirmed!</h2>
        <p style="color:#6B5A6E;margin:0 0 24px">Thank you for your order, {{ $order->name }}.</p>

        <div style="background:#fff;border:1px solid #ECE0EC;border-radius:12px;padding:16px;margin-bottom:24px">
            <p style="margin:0 0 8px;font-weight:bold">Order {{ $order->number }}</p>
            @foreach($order->items as $item)
                <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:14px">
                    <span>{{ $item->name_snapshot }} × {{ $item->qty }}</span>
                    <span>${{ number_format(($item->unit_price_cents * $item->qty) / 100, 2) }}</span>
                </div>
            @endforeach
            <hr style="border:none;border-top:1px solid #ECE0EC;margin:12px 0">
            <div style="display:flex;justify-content:space-between;font-size:14px">
                <span>Subtotal</span><span>{{ $order->subtotalFormatted() }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px">
                <span>Shipping</span><span>{{ $order->shipping_cents === 0 ? 'Free' : $order->shippingFormatted() }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-weight:bold;margin-top:4px">
                <span>Total</span><span>{{ $order->totalFormatted() }}</span>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #ECE0EC;border-radius:12px;padding:16px">
            <p style="margin:0 0 4px;font-weight:bold;font-size:14px">Shipping Address</p>
            <p style="margin:0;font-size:14px;color:#6B5A6E">
                {{ $order->name }}<br>
                {{ $order->address_line1 }}<br>
                @if($order->address_line2){{ $order->address_line2 }}<br>@endif
                {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
            </p>
        </div>
    </div>
    <div style="padding:16px 24px;text-align:center;font-size:12px;color:#6B5A6E">
        <p>&copy; {{ date('Y') }} KRIEM Beauty. All rights reserved.</p>
    </div>
</div>
