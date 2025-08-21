<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
 
 
use App\Http\Controllers\ProductController;
Route::get('/products/check-low-stock', [ProductController::class, 'checkLowStock'])
    ->name('products.check-low-stock');
Route::resource('products', ProductController::class)->middleware('auth');

use App\Http\Controllers\PurchaseController;
Route::resource('purchases', PurchaseController::class);

Route::resource('purchases', PurchaseController::class)->middleware('auth');
 
Route::get('/products/{product}/purchases', [PurchaseController::class, 'productPurchases'])
    ->name('products.purchases');

use App\Http\Controllers\SaleController;
 Route::middleware('auth')->group(function () {

    // POS main page
    Route::get('/pos', [SaleController::class, 'index'])->name('pos.index');

    // Store a sale
    Route::post('/pos/store', [SaleController::class, 'store'])->name('pos.store');

    // Print receipt
    Route::get('/pos/receipt/{sale}', [SaleController::class, 'printReceipt'])->name('pos.receipt');

    // All Sales (for reports/admin)
    Route::get('/pos/saleslist', [SaleController::class, 'allSales'])->name('pos.saleslist');

    // View a single sale
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

});


use App\Http\Controllers\ReportController;
Route::get('/reports/profit-loss', [ ReportController::class, 'profitLoss'])->name('reports.profit_loss');
Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
use App\Http\Controllers\ExpenseController;

Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/', [ExpenseController::class, 'store'])->name('expenses.store');
});

 
 


Route::resource('products', ProductController::class);
Route::get('products-export', [ProductController::class, 'export'])->name('products.export'); // CSV
Route::post('products/{product}/adjust', [ProductController::class, 'adjust'])->name('products.adjust');
Route::get('products/{product}/adjust-form', [ProductController::class, 'adjustForm'])->name('products.adjust.form'); // optional
Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
Route::get('/products/export/{type}', [ProductController::class, 'export'])
    ->name('products.export');
    
Route::get('/products/check-low-stock', [ProductController::class, 'checkLowStock'])
    ->name('products.check-low-stock');
    Route::get('/products/export/{type?}', [ProductController::class, 'export'])
    ->name('products.export');
    