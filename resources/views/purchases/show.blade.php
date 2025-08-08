<div class="container mt-4">
    <h4>View Purchase Details</h4>

    <table class="table table-bordered">
        <tr><th>Product</th><td>{{ $purchase->product->name ?? 'N/A' }}</td></tr>
        <tr><th>Quantity</th><td>{{ $purchase->quantity }}</td></tr>
        <tr><th>Price</th><td>MK {{ number_format($purchase->price, 2) }}</td></tr>
        <tr><th>Total Cost</th><td>MK {{ number_format($purchase->total_cost, 2) }}</td></tr>
        <tr><th>Supplier</th><td>{{ $purchase->supplier }}</td></tr>
        <tr><th>Date</th><td>{{ $purchase->purchase_date }}</td></tr>
    </table>

    <button class="btn btn-secondary ajax-link" data-url="{{ route('purchases.index') }}">
        <i class="bi bi-arrow-left"></i> Back to List
    </button>
</div>
