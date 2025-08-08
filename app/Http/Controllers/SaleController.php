<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;

class SaleController extends Controller
{
    // POS screen
    public function index()
    {
        $products = Product::all();
        return view('pos.index', compact('products'));
    }

    // Save Sale
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        $sale = Sale::create(['total_amount' => $total]);

        foreach ($data['items'] as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }

        return response()->json(['message' => 'Sale completed successfully']);
    }

    // View All Sales
    public function allSales()
    {
        $sales = Sale::with('items')->latest()->get();
        return view('sell.index', compact('sales'));
    }

    // View Single Sale
    public function show($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);
        return view('sell.show', compact('sale'));
    }

    // Return placeholder view
    public function returns()
    {
        $returns = []; // You can add logic later if you track returns
        return view('sell.returns', compact('returns'));
    }
}
