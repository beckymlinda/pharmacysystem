<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        // You can still keep date filtering if you like, otherwise ignore these
        $from = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to   = $request->to_date ? Carbon::parse($request->to_date) : null;

        // Total purchases cost
        $purchaseQuery = Purchase::query();
        if ($from && $to) {
            $purchaseQuery->whereBetween('purchase_date', [$from, $to]);
        }
        $totalPurchase = $purchaseQuery->sum('total_cost');

        // Total sales revenue
        $salesQuery = Sale::query();
        if ($from && $to) {
            $salesQuery->whereBetween('sale_date', [$from, $to]);
        }
        $totalSales = $salesQuery->sum('total_amount');

        // Total expenses
        $expenseQuery = Expense::query();
        if ($from && $to) {
            $expenseQuery->whereBetween('date', [$from, $to]);
        }
        $totalExpense = $expenseQuery->sum('amount');

        // Sales returns (if any, else 0)
        $totalReturns = 0;

        // Net profit/loss
        $profit = ($totalSales - $totalReturns) - ($totalPurchase + $totalExpense);

        // **Starting Capital / Initial Value**
        // If you want this manually set, you can fetch from DB/settings
        $startingCapital = 0; // replace with actual value if you store it

        // **Current stock value at sale price**
        $totalStockValue = Product::sum(\DB::raw('quantity * selling_price'));

        return view('reports.profit_loss', compact(
            'totalPurchase',
            'totalSales',
            'totalReturns',
            'totalExpense',
            'profit',
            'startingCapital',
            'totalStockValue'
        ));
    }
}
