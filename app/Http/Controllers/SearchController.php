<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = $request->get('q', '');

        $products = collect();

        if (strlen($q) >= 2) {
            $products = Product::active()
                ->with('brand', 'category', 'images')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('model_no', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$q}%"));
                })
                ->paginate(12)
                ->withQueryString();
        }

        return view('search', compact('products', 'q'));
    }
}
