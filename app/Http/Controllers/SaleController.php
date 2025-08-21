<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\SaleItem;

class SaleController extends Controller
{
    // Show POS form
    public function index()
    {
        $products = Product::all();
        return view('Pos.index', compact('products'));
    }

    // Store a sale
 
public function store(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:Cash,Advance,Standard Bank,National Bank,Airtel Money,Mpamba',
    ]);

    $total_amount = 0;

    $sale = Sale::create([
        'user_id' => Auth::id(),
        'total_amount' => 0, // temporary, will update after items
        'sale_date' => now(),
        'payment_method' => $validated['payment_method'],
    ]);

    foreach ($validated['items'] as $item) {
        $product = Product::findOrFail($item['product_id']);
        $item_total = $item['quantity'] * $item['price'];
        $total_amount += $item_total;

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total' => $item_total,
        ]);

        // Reduce stock
        $product->quantity -= $item['quantity'];
        if ($product->quantity < 0) $product->quantity = 0;
        $product->save();
    }

    // Update total
    $sale->update(['total_amount' => $total_amount]);

    return response()->json([
        'success' => true,
        'sale_id' => $sale->id,
    ]);
}


    // Optional: Show receipt for printing
    public function printReceipt(Sale $sale)
{
    $sale->load('items.product', 'user'); // eager load products and cashier
    return view('Pos.receipt', compact('sale'));
}
// In SaleController.php
public function allSales(Request $request)
{
    $query = Sale::with('user')->orderBy('sale_date', 'desc');

    // Filter by date range
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('sale_date', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

    // Filter by cashier
    if ($request->filled('cashier_id')) {
        $query->where('user_id', $request->cashier_id);
    }

    // Filter by payment method
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    $sales = $query->get();

    // For cashier filter dropdown
    $cashiers = \App\Models\User::all();

    return view('Pos.sales_list', compact('sales', 'cashiers'));
}



}
