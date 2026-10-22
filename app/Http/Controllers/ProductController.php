<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load('brand', 'category', 'images');
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('brand', 'images')
            ->take(4)
            ->get();

        return view('product', compact('product', 'related'));
    }
}
