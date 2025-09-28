<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StockManagementController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('stock.index', compact('products'));
    }

    // Show a single product (for "View" button)
    public function show(Product $product)
    {
        return view('stock.show', compact('product'));
    }

    // Show edit stock form (optional if using modal)
    public function edit(Product $product)
    {
        return view('stock.edit', compact('product'));
    }

    // Adjust stock via AJAX
   public function adjust(Request $request, Product $product)
{
    $request->validate([
        'quantity' => 'nullable|integer|min:0',
        'selling_price' => 'nullable|numeric|min:0'
    ]);

    // If user typed a quantity, ADD it to the existing stock
    if ($request->filled('quantity')) {
        $product->quantity += $request->quantity;
    }

    // If user typed a new price, update it
    if ($request->filled('selling_price')) {
        $product->selling_price = $request->selling_price;
    }

    $product->save();

    return response()->json([
        'message' => 'Stock updated successfully!',
        'quantity' => $product->quantity,
        'selling_price' => $product->selling_price,
    ]);
}

}
