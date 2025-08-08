<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function updateQuantity(Request $request, Product $product)
{
    $request->validate([
        'added_stock' => 'required|integer|min:1'
    ]);

    $product->quantity += $request->added_stock;
    $product->purchase_frequency += 1; // track restock events
    $product->save();

    return response()->json(['success' => true, 'message' => 'Stock updated successfully!']);
}

    public function index()
{
     $products = Product::all();

    // Return partial if AJAX
    if (request()->ajax()) {
        return view('products.index', compact('products'));
    }

    // Fallback if accessed normally
    return view('layout.dashboard', [
        'content' => view('products.index', compact('products'))->render()
    ]);
}
public function export()
{
    $products = Product::all();
    $filename = 'products_' . date('Y-m-d') . '.csv';
    $handle = fopen($filename, 'w+');

    // Header row
    fputcsv($handle, ['Name', 'Brand', 'Category', 'Quantity', 'Order Price', 'Selling Price', 'Expiry Date', 'Seller', 'Alert Quantity', 'Purchase Frequency']);

    foreach ($products as $product) {
        fputcsv($handle, [
            $product->name,
            $product->brand,
            $product->category,
            $product->quantity,
            $product->order_price,
            $product->selling_price,
            $product->expiry_date,
            $product->seller,
            $product->alert_quantity,
            $product->purchase_frequency
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}

 

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'expiry_date' => 'nullable|date',
        'order_price' => 'nullable|numeric|min:0',
        'selling_price' => 'nullable|numeric|min:0',
        'brand' => 'nullable|string|max:255',
        'seller' => 'nullable|string|max:255',
        'alert_quantity' => 'nullable|integer|min:0',
        // purchase_frequency usually managed by system, so not validated here
    ]);

    Product::create($request->only(
        'name', 'category', 'quantity', 'price',
        'expiry_date', 'order_price', 'selling_price',
        'brand', 'seller', 'alert_quantity'
    ));

    if ($request->ajax()) {
        return response()->json(['message' => 'Product created']);
    }

    return redirect()->route('products.index')->with('success', 'Product added');
}


public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'expiry_date' => 'nullable|date',
        'order_price' => 'nullable|numeric|min:0',
        'selling_price' => 'nullable|numeric|min:0',
        'brand' => 'nullable|string|max:255',
        'seller' => 'nullable|string|max:255',
        'alert_quantity' => 'nullable|integer|min:0',
        // purchase_frequency is readonly and not updated here
    ]);

    // Calculate stock adjustment:
    // New quantity entered is the total stock desired
    // So, calculate difference between new and old quantity
    // then add that difference to the existing stock in DB
    // (assuming this is how you want it)
    $newQuantity = $request->input('quantity');
    $oldQuantity = $product->quantity;
    $quantityDifference = $newQuantity - $oldQuantity;

    // Update product fields except quantity first
    $product->update($request->except('quantity'));

    // Now adjust quantity by adding difference
    // (this effectively sets quantity to the new value)
    $product->quantity = $oldQuantity + $quantityDifference;
    $product->save();

    if ($request->ajax()) {
        return response()->json(['message' => 'Product updated']);
    }

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}


public function destroy(Product $product)
{
    $product->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Product deleted!'
        ]);
    }

    return back()->with('success', 'Product deleted!');
}

public function edit(Product $product)
{
    if (request()->ajax()) {
        return view('products.edit', compact('product'));
    }

    return redirect()->route('products.index');
}
public function show(Product $product)
{
    if (request()->ajax()) {
        return view('products.show', compact('product'));
    }

    return redirect()->route('products.index');
}

public function adjust(Request $request, Product $product)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $newQuantity = $request->input('quantity');
    $oldQuantity = $product->quantity;
    $difference = $newQuantity - $oldQuantity;

    // Update stock quantity
    $product->quantity = $oldQuantity + $difference;
    $product->save();

    if ($request->ajax()) {
        return response()->json(['message' => 'Stock adjusted successfully']);
    }

    return redirect()->route('products.index')->with('success', 'Stock adjusted successfully.');
}

}
