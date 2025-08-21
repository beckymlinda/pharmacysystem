<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    /**
     * Show all purchases.
     */
    public function index()
    {
        $purchases = Purchase::with('product')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show form to create a purchase.
     */
    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

    /**
     * Store a new purchase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'quantity'       => 'required|integer|min:1',
            'price'          => 'required|numeric|min:0',
            'supplier'       => 'nullable|string|max:255',
            'purchase_date'  => 'required|date',
            'batch_number'   => 'nullable|string|max:100',
            'expiry_date'    => 'required|date|after_or_equal:today',
            'invoice_number' => 'nullable|string|max:100',
            'remarks'        => 'nullable|string'
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity'] * $validated['price'];

        // 1. Save purchase history
        $purchase = Purchase::create($validated);

        // 2. Update product stock & purchase frequency
        $product = Product::findOrFail($validated['product_id']);
        $product->quantity += $validated['quantity'];
        $product->purchase_frequency += 1;
        $product->save();

        return redirect()->route('purchases.index')
                         ->with('success', 'Purchase recorded & stock updated successfully.');
    }

    /**
     * Delete a purchase and adjust stock.
     */
    public function destroy(Purchase $purchase)
    {
        // Reduce stock when purchase is deleted
        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->quantity -= $purchase->quantity;
            if ($product->quantity < 0) {
                $product->quantity = 0; // prevent negative stock
            }
            $product->save();
        }

        $purchase->delete();

        return back()->with('success', 'Purchase deleted and stock updated.');
    }
    public function expiryAlerts()
{
    $today = now();
    $threshold = now()->addDays(30);

    $expiring = Purchase::with('product')
        ->whereBetween('expiry_date', [$today, $threshold])
        ->get();

    $expired = Purchase::with('product')
        ->where('expiry_date', '<', $today)
        ->get();

    return view('alerts.expiry', compact('expiring', 'expired'));
}

public function update(Request $request, Purchase $purchase)
{
    $request->validate([
        'product_id'    => 'required|exists:products,id',
        'quantity'      => 'required|integer|min:1',
        'price'         => 'required|numeric|min:0',
        'supplier'      => 'nullable|string|max:255',
        'purchase_date' => 'required|date',
        'expiry_date'   => 'required|date',
    ]);

    // Recalculate total cost
    $totalCost = $request->quantity * $request->price;

    // Adjust product stock if quantity changes
    $product = Product::find($request->product_id);
    if ($product) {
        // Subtract old purchase quantity, then add new
        $product->quantity -= $purchase->quantity;
        $product->quantity += $request->quantity;

        if ($product->quantity < 0) {
            $product->quantity = 0; // prevent negative stock
        }

        $product->save();
    }

    // Update purchase record
    $purchase->update([
        'product_id'    => $request->product_id,
        'quantity'      => $request->quantity,
        'price'         => $request->price,
        'total_cost'    => $totalCost,
        'supplier'      => $request->supplier,
        'purchase_date' => $request->purchase_date,
        'expiry_date'   => $request->expiry_date,
        'batch_number'  => $request->batch_number ?? $purchase->batch_number,
        'invoice_number'=> $request->invoice_number ?? $purchase->invoice_number,
        'remarks'       => $request->remarks ?? $purchase->remarks,
    ]);

    // Return AJAX response
    return response()->json([
        'success'    => true,
        'message'    => 'Purchase updated successfully!',
        'edited_id'  => $purchase->id,
    ]);
}


public function edit(Purchase $purchase)
{
    // Get all products for the dropdown
    $products = Product::all();

    // If AJAX, return the edit form Blade
    if (request()->ajax()) {
        return view('purchases.edit', compact('purchase', 'products'));
    }

    // Fallback in case someone visits URL directly
    return redirect()->route('purchases.index');
}
public function productPurchases(Product $product)
{
    $purchases = Purchase::where('product_id', $product->id)
                         ->orderBy('purchase_date', 'desc')
                         ->get();

    // Return a small Blade partial
    return view('purchases.partials.product_purchases', compact('purchases', 'product'));
}


}
