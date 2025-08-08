<div class="container mt-4">
    <h4><i class="bi bi-arrow-counterclockwise"></i> Sell Returns</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th>Parent Sale</th>
                <th>Customer</th>
                <th>Total Refunded (MK)</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $return)
                <tr>
                    <td>{{ $return->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $return->invoice_no }}</td>
                    <td>{{ $return->parent_invoice }}</td>
                    <td>{{ $return->customer_name }}</td>
                    <td>{{ number_format($return->total_amount, 2) }}</td>
                    <td>{{ $return->location }}</td>
                    <td>
                        <a href="{{ route('sell.return.show', $return->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No returns found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
