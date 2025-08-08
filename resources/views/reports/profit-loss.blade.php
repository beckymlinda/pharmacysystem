<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="bi bi-bar-chart-line-fill me-2"></i>Profit / Loss Report
        </h2>
        <form method="GET" action="{{ route('reports.profit-loss') }}" class="d-flex flex-wrap gap-2">
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" required>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" required>
            <button class="btn btn-outline-primary">
                <i class="bi bi-filter-circle me-1"></i> Filter
            </button>
        </form>
    </div>

    <!-- Report Summary -->
    <div class="row">
        <!-- Purchase Summary -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="text-secondary fw-semibold mb-3">
                        <i class="bi bi-cart-check me-2"></i>Purchase Summary
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Opening Stock (Purchase): <span class="float-end text-dark">MWK {{ number_format($openingStockPurchase, 2) }}</span></li>
                        <li class="list-group-item">Opening Stock (Sale): <span class="float-end text-dark">MWK {{ number_format($openingStockSale, 2) }}</span></li>
                        <li class="list-group-item">Total Purchases: <span class="float-end text-dark">MWK {{ number_format($totalPurchase, 2) }}</span></li>
                        <li class="list-group-item">Stock Adjustments: <span class="float-end text-dark">MWK {{ number_format($totalAdjustment, 2) }}</span></li>
                        <li class="list-group-item">Expenses: <span class="float-end text-dark">MWK {{ number_format($totalExpense, 2) }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sales Summary -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="text-secondary fw-semibold mb-3">
                        <i class="bi bi-bag-check me-2"></i>Sales Summary
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Closing Stock (Purchase): <span class="float-end text-dark">MWK {{ number_format($closingStockPurchase, 2) }}</span></li>
                        <li class="list-group-item">Closing Stock (Sale): <span class="float-end text-dark">MWK {{ number_format($closingStockSale, 2) }}</span></li>
                        <li class="list-group-item">Total Sales: <span class="float-end text-success fw-bold">MWK {{ number_format($totalSales, 2) }}</span></li>
                        <li class="list-group-item">Sales Returns: <span class="float-end text-dark">MWK {{ number_format($totalReturns, 2) }}</span></li>
                    </ul>
                    <hr>
                    <h5 class="text-success mt-3">
                        <i class="bi bi-cash-stack me-2"></i>
                        Net Profit / Loss: <span class="float-end">MWK {{ number_format($profit, 2) }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
