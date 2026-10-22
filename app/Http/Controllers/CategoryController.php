<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $sort = $request->get('sort', 'name');

        $query = $category->products()->active()->with('brand', 'images');

        $query = match ($sort) {
            'price_asc' => $query->orderBy('price_cents', 'asc'),
            'price_desc' => $query->orderBy('price_cents', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('name', 'asc'),
        };

        $products = $query->paginate(12);

        return view('category', compact('category', 'products', 'sort'));
    }
}
