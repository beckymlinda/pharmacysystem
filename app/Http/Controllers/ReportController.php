<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
   public function profitLoss(Request $request)
{
    // Fetch summary data — dummy example
    $totalSales = Sale::sum('total_amount');
    $totalPurchase = Purchase::sum('total_cost');
    $openingStockPurchase = Product::sum('price'); // Example approximation
    $openingStockSale = Product::sum('price');     // You may adjust logic
    $closingStockPurchase = 0;
    $closingStockSale = 0;
    $totalReturns = 0;
    $totalAdjustment = 0;
    $totalExpense = 0;

    $profit = $totalSales - $totalPurchase;

    return view('reports.profit-loss', compact(
        'totalSales',
        'totalPurchase',
        'openingStockPurchase',
        'openingStockSale',
        'closingStockPurchase',
        'closingStockSale',
        'totalReturns',
        'totalAdjustment',
        'totalExpense',
        'profit'
    ));
}

}
