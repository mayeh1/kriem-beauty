<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::orderBy('sort_order')->get();
        $featured = Product::active()->featured()->with('brand', 'category', 'images')->take(8)->get();
        $products = Product::active()->with('brand', 'category', 'images')->latest()->take(12)->get();

        return view('home', compact('categories', 'featured', 'products'));
    }
}
