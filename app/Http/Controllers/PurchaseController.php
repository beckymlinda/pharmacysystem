<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
{
    $query = Purchase::with('product');

    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    if ($request->filled('supplier')) {
        $query->where('supplier', 'like', '%' . $request->supplier . '%');
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('purchase_date', [$request->from_date, $request->to_date]);
    }

    $purchases = $query->latest()->get();
    $products = Product::all();

    return view('purchases.index', compact('purchases', 'products'));
}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        $totalCost = $request->quantity * $request->price;

        Purchase::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total_cost' => $totalCost,
            'supplier' => $request->supplier,
            'purchase_date' => $request->purchase_date,
        ]);

        return back()->with('success', 'Purchase recorded successfully.');
    }

  

public function update(Request $request, $id)
{
    $request->validate([
        'product_id' => 'required',
        'quantity' => 'required|integer',
        'price' => 'required|numeric',
        'purchase_date' => 'required|date',
        'supplier' => 'nullable|string'
    ]);

    $purchase = Purchase::findOrFail($id);
    $purchase->update([
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'purchase_date' => $request->purchase_date,
        'supplier' => $request->supplier,
        'total_cost' => $request->quantity * $request->price
    ]);

    return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
}

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return back()->with('success', 'Purchase deleted.');
    }
    public function edit($id)
{
    $purchase = Purchase::findOrFail($id);
    $products = Product::all();
    return view('purchases.edit', compact('purchase', 'products'));
}

public function show($id)
{
    $purchase = Purchase::with('product')->findOrFail($id);
    return view('purchases.show', compact('purchase'));
}

     
}
