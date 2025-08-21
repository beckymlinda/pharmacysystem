<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use Barryvdh\DomPDF\Facade\Pdf; // Correct import for Laravel 9+


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
        $product->purchase_frequency += 1;
        $product->last_restock_date = now();
        $product->save();

        // Check if stock is still below alert level after update
        $lowStock = $product->quantity <= $product->alert_quantity;
        
        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
            'low_stock' => $lowStock,
            'new_quantity' => $product->quantity
        ]);
    }

    public function index()
    {
        $products = Product::all();
        
        // Check for low stock products
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'alert_quantity')->get();
        
        if (request()->ajax()) {
            return view('products.index', compact('products', 'lowStockProducts'));
        }

        return view('layout.dashboard', [
            'content' => view('products.index', compact('products', 'lowStockProducts'))->render()
        ]);
    }

    public function export($type = 'csv')
    {
        $products = Product::all();
        $filename = 'products_' . date('Y-m-d');
        
        if ($type === 'pdf') {
            $pdf = PDF::loadView('products.export_pdf', compact('products'));
            return $pdf->download($filename . '.pdf');
        }
        
        // Default CSV export
        $filename .= '.csv';
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, [
            'Name', 'Brand', 'Category', 'Quantity', 
            'Alert Quantity', 'Status', 'Order Price', 
            'Selling Price', 'Expiry Date', 'Seller',
            'Purchase Frequency', 'Last Restock'
        ]);

        foreach ($products as $product) {
            $status = $product->quantity <= $product->alert_quantity ? 'Low Stock' : 'In Stock';
            
            fputcsv($handle, [
                $product->name,
                $product->brand,
                $product->category,
                $product->quantity,
                $product->alert_quantity,
                $status,
                $product->order_price,
                $product->selling_price,
                $product->expiry_date,
                $product->seller,
                $product->purchase_frequency,
                $product->last_restock_date
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
            'expiry_date' => 'required|date',
            'order_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'seller' => 'nullable|string|max:255',
            'alert_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'expiry_date' => $request->expiry_date,
            'order_price' => $request->order_price,
            'selling_price' => $request->selling_price,
            'brand' => $request->brand,
            'seller' => $request->seller,
            'alert_quantity' => $request->alert_quantity,
            'purchase_frequency' => 0,
            'last_restock_date' => now()
        ]);

        if ($request->ajax()) {
            $lowStock = $product->quantity <= $product->alert_quantity;
            return response()->json([
                'message' => 'Product created',
                'low_stock' => $lowStock,
                'product' => $product
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product added')
            ->with('low_stock', $product->quantity <= $product->alert_quantity);
    }
public function update(Request $request, Product $product)
{
    // Validate incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'quantity' => 'nullable|integer|min:0',
         'expiry_date' => 'required|date',
        'order_price' => 'nullable|numeric|min:0',
        'selling_price' => 'nullable|numeric|min:0',
        'brand' => 'nullable|string|max:255',
        'seller' => 'nullable|string|max:255',
        'alert_quantity' => 'nullable|integer|min:0',
        // purchase_frequency is readonly
    ]);

    // Calculate quantity difference
    $oldQuantity = $product->quantity;
    $newQuantity = $request->input('quantity');
    $quantityDifference = $newQuantity - $oldQuantity;

    // Update product fields except quantity first
    $product->update($request->except('quantity'));

    // Now adjust quantity
    $product->quantity = $oldQuantity + $quantityDifference;
    $product->save();

    // Return AJAX JSON if needed
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    // Normal form submission fallback
    return redirect()->route('products.index')
                     ->with('success', 'Product updated successfully.');
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
        'quantity' => 'required|integer|min:1', // must add at least 1
        'selling_price' => 'nullable|numeric|min:0', // optional update
    ]);

    // Increment quantity
    $product->quantity += $request->quantity;

    // Optional: update selling price if provided
    if ($request->filled('selling_price')) {
        $product->selling_price = $request->selling_price;
    }

    $product->save();

    if ($request->ajax()) {
        return response()->json([
            'message' => 'Stock adjusted successfully',
            'quantity' => $product->quantity,
            'selling_price' => $product->selling_price,
        ]);
    }

    return redirect()->route('products.index')
        ->with('success', 'Stock adjusted successfully.');
}
public function checkLowStock()
{
    $products = Product::whereColumn('quantity', '<=', 'alert_quantity')->get();
    return response()->json([
        'count' => $products->count(),
        'products' => $products
    ]);
}

}
