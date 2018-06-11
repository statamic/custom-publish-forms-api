<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function slug($slug)
    {
        return Product::where('slug', $slug)->firstOrFail();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'content' => 'required',
        ]);

        return Product::create($data);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'content' => 'required',
        ]);

        $product->update($data);

        return $product;
    }
}
