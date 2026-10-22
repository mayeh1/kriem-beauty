<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return response()->json([
            'items' => $this->cart->items(),
            'count' => $this->cart->count(),
            'subtotal' => $this->cart->subtotalFormatted(),
            'shipping' => $this->cart->shippingFormatted(),
            'shipping_cents' => $this->cart->shippingCents(),
            'total' => $this->cart->totalFormatted(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1|max:99',
        ]);

        $this->cart->add($request->product_id, $request->integer('qty', 1));

        return response()->json([
            'message' => 'Added to cart',
            'count' => $this->cart->count(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:0|max:99',
        ]);

        $this->cart->update($request->product_id, $request->qty);

        return $this->index();
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $this->cart->remove($request->product_id);

        return $this->index();
    }
}
