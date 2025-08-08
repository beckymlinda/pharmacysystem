<div class="container mt-4">
    <h4><i class="bi bi-receipt-cutoff"></i> All Sales</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Total (MK)</th>
                <th>Payment Status</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->payment_status) }}</td>
                    <td>{{ $sale->location }}</td>
                    <td>
    <a href="#" class="btn btn-sm btn-outline-primary ajax-link"
       data-url="{{ route('sell.show', $sale->id) }}">
        View
    </a>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No sales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
