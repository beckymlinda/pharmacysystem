{{-- resources/views/sales/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Sale #{{ $sale->id }} Details</h4>
        </div>
        <div class="card-body">

            {{-- Sale Info --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Date:</strong> {{ $sale->sale_date->format('Y-m-d H:i') }}
                </div>
                <div class="col-md-4">
                    <strong>Seller:</strong> {{ $sale->user->name ?? 'N/A' }}
                </div>
                <div class="col-md-4">
                    <strong>Total Amount:</strong> MK {{ number_format($sale->total_amount, 2) }}
                </div>
            </div>

            {{-- Products Table --}}
            <h5 class="mt-3">Products</h5>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>MK {{ number_format($item->price, 2) }}</td>
                        <td>MK {{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Grand Total:</th>
                    <th>MK {{ number_format($sale->total_amount, 2) }}</th>
                </tr>
                </tfoot>
            </table>

            {{-- Payment Methods --}}
            <h5 class="mt-4">Payments</h5>
            <ul class="list-group">
                @foreach($sale->payments as $payment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $payment->method }}
                        <span class="badge bg-success">MK {{ number_format($payment->amount, 2) }}</span>
                    </li>
                @endforeach
            </ul>

            {{-- Back Button --}}
            <div class="mt-4 text-end">
                <a href="{{ route('pos.saleslist') }}" class="btn btn-secondary">← Back to All Sales</a>
                <a href="{{ route('pos.receipt', $sale->id) }}" target="_blank" class="btn btn-primary">🖨 Print Receipt</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
