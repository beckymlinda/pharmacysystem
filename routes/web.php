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
Route::resource('products', ProductController::class)->middleware('auth');

use App\Http\Controllers\PurchaseController;

Route::resource('purchases', PurchaseController::class)->middleware('auth');
use App\Http\Controllers\SaleController;

Route::get('/pos', [SaleController::class, 'index'])->middleware('auth')->name('pos.index');
Route::post('/sales', [SaleController::class, 'store'])->middleware('auth')->name('sales.store');

 
Route::resource('sales', SaleController::class)->middleware('auth');
 

Route::get('/pos', [SaleController::class, 'index'])->name('pos.index');
Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');

Route::prefix('sell')->group(function () {
    Route::get('/list', [SaleController::class, 'allSales'])->name('sell.index');
    Route::get('/returns', [SaleController::class, 'returns'])->name('sell.returns');
    Route::get('/{id}', [SaleController::class, 'show'])->name('sell.show');
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
