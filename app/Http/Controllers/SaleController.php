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

        'payments' => 'required|array|min:1',
        'payments.*.method' => 'required|string|in:Cash,Advance,Standard Bank,National Bank,Airtel Money,Mpamba',
        'payments.*.amount' => 'required|numeric|min:0',
    ]);

    $total_amount = 0;

    // Create sale
    $sale = Sale::create([
        'user_id' => Auth::id(),
        'total_amount' => 0, // update later
        'sale_date' => now(),
    ]);

    // Save sale items
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
        $product->quantity = max(0, $product->quantity - $item['quantity']);
        $product->save();
    }

    // Save payments (assuming you make a new SalePayment model & table)
    foreach ($validated['payments'] as $payment) {
        \App\Models\SalePayment::create([
            'sale_id' => $sale->id,
            'method' => $payment['method'],
            'amount' => $payment['amount'],
        ]);
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

public function salesPage()
{
    $sales = Sale::with(['items.product', 'user', 'payments'])->latest()->get();
    $sellers = \App\Models\User::all();

    return view('sales.index', compact('sales', 'sellers'));
}

    // AJAX method to fetch sales
public function fetchSales(Request $request)
{
    $query = Sale::with(['items.product', 'payments', 'user']);

    // Date filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('sale_date', [
            $request->start_date,
            $request->end_date
        ]);
    }

    // Seller filter
    if ($request->filled('seller_id')) {
        $query->where('user_id', $request->seller_id);
    }

    // Payment method filter
    if ($request->filled('payment_method')) {
        $query->whereHas('payments', function($q) use ($request) {
            $q->where('method', $request->payment_method);
        });
    }

    $sales = $query->latest()->get();

    return view('sales._table', compact('sales')); // partial reload
}


public function allSales()
{
    $sales = \App\Models\Sale::with(['items.product', 'user', 'payments'])->get();

    if(request()->ajax()){
        return view('sales._table', compact('sales'));
    }

    return view('dashboard', [
        'content' => view('sales._table', compact('sales'))->render()
    ]);
}
public function show(Sale $sale)
{
    $sale->load(['items.product', 'payments', 'user']);

    // If you want a dedicated detail blade
    return view('sales.show', compact('sale'));

    // Or, if you only need this inside dashboard dynamically:
    // return view('sales._detail', compact('sale'));
}

public function searchProducts(Request $request)
{
    $query = $request->get('q', '');

    $products = Product::where('name', 'like', "%{$query}%")
                        ->where('quantity', '>', 0) // optional: only in-stock
                        ->limit(10)
                        ->get(['id', 'name', 'quantity', 'selling_price']);

    return response()->json($products);
}

}
