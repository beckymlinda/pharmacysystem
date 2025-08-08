<div class="container mt-4">
    <h4><i class="bi bi-receipt"></i> Sale Details - Invoice #{{ $sale->invoice_no }}</h4>

    <p><strong>Date:</strong> {{ $sale->created_at->format('d-m-Y H:i') }}</p>
    <p><strong>Customer:</strong> {{ $sale->customer_name }}</p>
    <p><strong>Location:</strong> {{ $sale->location }}</p>
    <p><strong>Status:</strong> {{ ucfirst($sale->payment_status) }}</p>

    <hr>
    <h5>Items</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price (MK)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th>{{ number_format($sale->total_amount, 2) }} MK</th>
            </tr>
        </tfoot>
    </table>

    <a href="#" onclick="window.print()" class="btn btn-outline-primary">Print Receipt</a>
</div>
