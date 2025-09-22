<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StockAdjustment;


class ProductController extends Controller
{

 

    public function updateQuantity(Request $request, Product $product)
    {
        $request->validate([
            'added_stock' => 'required|integer|min:1'
        ]);

        $product->quantity += $request->added_stock;
        $product->purchase_frequency += 1;
        $product->last_restock_date = now();
        $product->save();

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
    // Eager load saleItems to avoid N+1 query problem
    $products = Product::with('saleItems')->get();

    // Calculate total units sold
    foreach ($products as $product) {
        $product->total_units_sold = $product->saleItems->sum('quantity');
        $product->current_cash = $product->total_units_sold * $product->selling_price;
        //unset($product->saleItems); // Remove saleItems to reduce payload
    }
    

    // Low stock products
    $lowStockProducts = Product::whereColumn('quantity', '<=', 'alert_quantity')->get();

    // Units
    $units = \App\Models\Unit::all();

    if (request()->ajax()) {
        return view('products.index', compact('products', 'lowStockProducts', 'units'));
    }

    return view('dashboard', [
        'content' => view('products.index', compact('products', 'lowStockProducts', 'units'))->render()
    ]);
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
            'unit_id' => 'nullable|exists:units,id', // <-- validate unit
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
            'unit_id' => $request->unit_id, // <-- save unit
            'purchase_frequency' => 0,
            'last_restock_date' => now()
        ]);

        if ($request->ajax()) {
            $lowStock = $product->quantity <= $product->alert_quantity;
            return response()->json([
                'success' => true, 
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
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'required|date',
            'order_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'seller' => 'nullable|string|max:255',
            'alert_quantity' => 'required|integer|min:0',
            'unit_id' => 'nullable|exists:units,id', // <-- validate unit
        ]);

        // Update all fields including unit
        $product->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product
            ]);
        }

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

        return redirect()->route('products.index');    }

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
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        $product->quantity += $request->quantity;

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

    public function export($type = 'csv')
    {
        $products = Product::all();
        $filename = 'products_' . date('Y-m-d');

        if ($type === 'pdf') {
            $pdf = PDF::loadView('products.export_pdf', compact('products'));
            return $pdf->download($filename . '.pdf');
        }

        $filename .= '.csv';
        $handle = fopen($filename, 'w+');

        fputcsv($handle, [
            'Name', 'Brand', 'Category', 'Quantity',
            'Alert Quantity', 'Status', 'Order Price',
            'Selling Price', 'Expiry Date', 'Seller',
            'Unit', 'Purchase Frequency', 'Last Restock'
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
                $product->unit->short_name ?? '-',
                $product->purchase_frequency,
                $product->last_restock_date
            ]);
        }

        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }
    public function search(Request $request)
{
    $query = $request->q;

    $products = Product::with('unit')
        ->when($query, function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('brand', 'like', "%{$query}%")
              ->orWhere('category', 'like', "%{$query}%")
              ->orWhere('seller', 'like', "%{$query}%");
              
        })
        ->get();

    return response()->json([
        'products' => $products
    ]);
}

// ProductController.php
public function adjustStockPage()
{
    $products = Product::with('unit')->get();
    return view('products.adjust_stock', compact('products'));
}

public function updateStock(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'adjustment' => 'required|integer',
        'reason' => 'required|string|max:255',
    ]);

    $product = Product::find($request->product_id);
    $product->quantity += $request->adjustment; // can be positive or negative
    $product->save();

    // Optional: log the adjustment
    StockAdjustment::create([
        'product_id' => $product->id,
        'adjustment' => $request->adjustment,
        'reason' => $request->reason,
        'adjusted_by' => auth()->id(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Stock adjusted successfully',
        'new_quantity' => $product->quantity
    ]);
}


}
